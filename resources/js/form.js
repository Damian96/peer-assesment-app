document.addEventListener('DOMContentLoaded', function (e) {
    // @TODO: extend jQuery add highlight effect
    // $.fn.highlight = function()

    let old_data = sessionStorage.getItem(document.querySelector('form').getAttribute('name'));
    old_data = new URLSearchParams(old_data);
    window.old_data = old_data;
    let card_template = $('.card.template').remove();
    window.card_template = card_template;
    let choice_template = card_template.find('.choice').first().clone();
    window.choice_template = choice_template;

    window.storeFormData = function () {
        let form = $('form');
        let key = form.attr('name');
        let data = new URLSearchParams(new FormData(form[0])).toString();
        sessionStorage.setItem(key, data);
        return true;
    };

    window.count = $('.card').length;

    /**
     * @param type The card's type
     * @param id The card's id
     * @param title The card's title
     * @param data The card's question data
     * @returns {boolean}
     */
    window.createCard = function (type, id, title = null, data = null) {
        console.debug(arguments);

        // Initialise variables
        window.count++;
        let card = card_template.clone();
        let max = 5;
        let subtitle = '';
        let minlbl = 'Highly Disagree';
        let maxlbl = 'Highly Agree';
        if (data) {
            title = (title ? title : data.title);
            max = data.hasOwnProperty('max') && data.max != null ? data.max : 5;
            subtitle = ((data.hasOwnProperty('subtitle') && data.subtitle != null) ? data.subtitle : '');
            minlbl = data.hasOwnProperty('minlbl') && data.minlbl != null ? data.minlbl : 'Highly Disagree';
            maxlbl = data.hasOwnProperty('maxlbl') && data.maxlbl != null ? data.maxlbl : 'Highly Agree';
        } else {
            title = (title ? title : `${type} - #${window.count}`);
        }

        // Add Question type
        card.find("input[type=hidden][name*=type]").val(type);
        card.attr('data-type', type)
            .removeAttr('id')
            .removeClass('template')
            .removeClass('d-none');

        // Add card title
        card.find('.btn-block[data-title]')
            .data('title', title)
            .text(title)
            .end()
            .find('input[name*="title"')
            .val(title)
            .end()
            .find('input[name*="subtitle"')
            .val(subtitle);

        // Add bs-collapse data
        let target = id.replace(/[\s#]/, '');
        card.find('.close-question')
            .attr('data-target', '#' + target)
            .attr('aria-controls', target);
        card.find('.collapse').attr('id', target);

        // Show
        card.removeClass('d-none')
            .attr('data-type', type);

        // Replace [#/counts]
        card.find('input[name]').each(function () {
            $(this).attr('name', this.getAttribute('name').replace(/#/i, (window.count).toString()));
        });
        card.find('label[for]').each(function () {
            $(this).attr('for', this.getAttribute('for').replace(/#/i, (window.count).toString()));
        });

        // remove extras / show specifics
        switch (type) {
            case 'linear-scale':
                card.find('.scale.d-none').removeClass('d-none').addClass('d-block');
                card.find('.multiple').remove();
                card.find('input[name*="[max]"]').val(max);
                card.find('input[name*="[minlbl]"]').val(minlbl);
                card.find('input[name*="[maxlbl]"]').val(maxlbl);
                card.find('input[name*=max]').first()[0].onchange();
                break;
            case 'multiple-choice':
                card.find('.multiple.d-none').removeClass('d-none').addClass('d-block');
                card.find('.scale').remove();
                card.find('.add-choice').on('click', addChoice); // @TODO: add addChoice args
                card.find('.delete-choice').on('click', deleteChoice);
                break;
            case 'eval':
            case 'paragraph':
                card.find('.d-none').remove();
                card.find('.multiple').remove();
                card.find('.scale').remove();
                break;
            default:
                throw Error('Invalid card type: ' + type);
        }

        // Add Event Listeners
        card.find('.card-body').on('shown.bs.collapse', function (card, e) {
            card.find('.close-question').text('keyboard_arrow_up');
            return true;
        }).bind(null, card);
        card.find('.card-body').on('hidden.bs.collapse', function (card, e) {
            card.find('.close-question').text('keyboard_arrow_down');
            return true;
        }.bind(null, card));
        card.find('.moveup-question').on('click', function (card, e) {
            let prev = card.prev();
            if (prev.hasClass('card')) {
                prev.before(card);
                card.effect('highlight', {}, 1000);
            }
            return true;
        }.bind(null, card));
        card.find('.movedown-question').on('click', function (card, e) {
            let next = card.next();
            if (next.hasClass('card')) {
                next.after(card);
                card.effect('highlight', {}, 1000);
            }
            return true;
        }.bind(null, card));
        card.find('.delete-question').on('click', function (card, e) {
            if (window.count == 1) {
                $('#session_id').combobox('enable');
                $('button.question-type').removeAttr('disabled');
                $('button[type=submit]').attr('disabled', true);
            }
            card.slideUp('fast', function () {
                this.remove();
                window.count--;
            });
        }.bind(null, card));

        // Append & Blink & Expand
        $('#card-container').append(card);
        card.effect('highlight', {}, 1000);
        if (!card.find('.card-body').hasClass('show'))
            card.find('.close-question')[0].click();

        // Re-enable submit
        $('form').find('button[type=submit]')[0].removeAttribute('disabled');

        // Store data to sessionStorage
        storeFormData();

        return true;
    };

    let addChoice = function (e) {
        let choice = choice_template.clone();
        let name = choice.find('label').first()[0].getAttribute('for').replace(/#/i, count);
        choice.find('label').attr('for', name);
        choice.find('input').attr('name', name);
        choice.find('label').first()[0].lastChild.textContent = 'OPTION';
        choice.find('input').val('OPTION');
        $('.choice-container').append(choice);

        choice.find('.add-choice').on('click', addChoice);
        choice.find('.delete-choice').on('click', deleteChoice);
    };
    window.addChoice = addChoice;
    let deleteChoice = function (e) {
        $(this).closest('.choice').slideUp('fast', function () {
            $(this).remove();
        });
        return true;
    };
    window.deleteChoice = deleteChoice;

    // course autocomplete
    $('#session_id').combobox();
    $('#session_id').next().on('autocompleteselect', function (e, ui) {
        $('input[name=session_id]').val(ui.item.option.getAttribute('value'));
        $('button.question-type').removeAttr('disabled');
        $('form').find('.col-md-12').first().effect('highlight', {}, 2000);
        return true;
    });
    // reset value for caching
    if (document.getElementById('update-form') != null) {
        $('button.question-type').removeAttr('disabled');
    } else { // create
        $('button.question-type').attr('disabled', true);
    }

    $('button.question-type').on('click', function () {
        $('#session_id').combobox('disable');
        createCard(this.id, `${this.id}-${window.count}`, `${this.id} - #${window.count}`);
        return true;
    });
    $('button[type=submit]').on('click', function (e) {
        storeFormData();
        return true;
    });

    let array_data = []; // @TODO: make array data to Object
    array_data['question'] = [];
    // map old_data to array_data
    for (let item of old_data) {
        // console.debug(item);
        if (item[0].includes('question')) { // questions
            let i = item[0].split('[')[1][0];
            let key = item[0].split('[')[2].split(']')[0];
            // console.debug(i, key);
            // console.debug(i, key, item);
            if (array_data['question'][i] == null) {
                array_data['question'][i] = [];
            }
            if (item[0].includes('max') || item[0].includes('min')) { // all other types
                array_data['question'][i][key] = item[1];
            } else if (array_data['question'][i]['choices'] != null) {
                array_data['question'][i][key].push(item[1]); // push choices array
            } else if (item[0].includes('choices')) {
                array_data['question'][i][key] = [item[1]]; // initialize choices array
            } else { // title, subtitle, others...
                array_data['question'][i][key] = item[1];
            }
        } else if (item[0].includes('title') || item[0].includes('footnote')) { // form title / subtitle / footnote
            array_data[item[0]] = item[1];
        }
        // console.debug(array_data);
    }
    // add old cards from 'array_data'
    array_data['question'].forEach((val, i) => {
        console.debug(val, i);
        val = new Object(val);
        createCard(val.type, val.type + '-' + window.count, val.title, val);
        return true;
    });
    window.array_data = array_data;
    window.addEventListener('unload', storeFormData);
});
