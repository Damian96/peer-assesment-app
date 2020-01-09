document.addEventListener('DOMContentLoaded', function (e) {
    // @TODO: extend jQuery add highlight effect
    // $.fn.highlight = function()

    if (!document.querySelector('.btn.question-type')) {
        return false;
    }

    let old_data = sessionStorage.getItem(document.querySelector('form').getAttribute('name'));
    old_data = new URLSearchParams(old_data);
    window.old_data = old_data;
    let card_template = $('.card.template').remove();
    window.card_template = card_template;
    let choice_template = card_template.find('.choice').first().clone();
    window.choice_template = choice_template;

    /**
     * @param card jQuery object of .card
     * @param count int The card's data-count
     * @return void
     */
    window.replaceCardNames = function (card, count = window.count) {
        let replace = `[${(count).toString()}]`;
        let patt = /\[[#\d]\]/i;
        console.log('replace', count);
        card.attr('data-count', count);
        card.find('input[name]').each(function () {
            console.debug($(this), count);
            $(this).attr('name', this.getAttribute('name').replace(patt, replace));
        });
        card.find('label[for]').each(function () {
            $(this).attr('for', this.getAttribute('for').replace(patt, replace));
        });
    };

    window.storeFormData = function () {
        let form = $('form');
        let key = form.attr('name');
        let data = new URLSearchParams(new FormData(form[0])).toString();
        sessionStorage.setItem(key, data);
        return true;
    };

    window.count = $('.card').length;

    window.addCardListeners = function (card) {
        card.find('.card-body').on('shown.bs.collapse', function (card, e) {
            card.find('.close-question').text('keyboard_arrow_up');
            return true;
        }).bind(null, card);
        card.find('.card-body').on('hidden.bs.collapse', function (card, e) {
            card.find('.close-question').text('keyboard_arrow_down');
            return true;
        }.bind(null, card));
        card.find('.moveup-question').on('click', function (card, e) {
            console.debug(card);
            let cCount = parseInt(card[0].getAttribute('data-count'));
            let prev = card.prev();
            replaceCardNames(card, cCount - 1);
            replaceCardNames(prev, cCount);
            if (prev.hasClass('card')) {
                prev.before(card);
                card.effect('highlight', {}, 1000);
            }
            storeFormData();
            return true;
        }.bind(null, card));
        card.find('.movedown-question').on('click', function (card, e) {
            console.debug(card);
            let cCount = parseInt(card[0].getAttribute('data-count'));
            let next = card.next();
            replaceCardNames(card, cCount + 1);
            replaceCardNames(next, cCount);
            if (next.hasClass('card')) {
                next.after(card);
                card.effect('highlight', {}, 1000);
            }
            storeFormData();
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
            storeFormData();
            return true;
        }.bind(null, card));
    };

    /**
     * @param type The card's type
     * @param id The card's id
     * @param title The card's title
     * @param data The card's question data
     * @returns {boolean}
     */
    window.createCard = function (type, id, title = null, data = null) {
        // console.debug(arguments);

        // Initialise variables
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
        card.find("input[name*=type]").val(type);
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
            .attr('data-type', type)
            .attr('data-count', count);

        // Replace [#/counts]
        replaceCardNames(card, count);

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
                if (data && data.hasOwnProperty('choices') && data.choices.length > 0) {
                    card.find('.choice').remove();
                    data.choices.forEach((choice, i) => {
                        // console.debug(choice, card.find('.add-choice')[0]);
                        addChoice.call(card.find('.add-choice')[0], choice);
                    });
                }
                break;
            case 'eval':
            case 'paragraph':
                card.find('.multiple').remove();
                card.find('.scale').remove();
                break;
            default:
                throw Error('Invalid card type: ' + type);
        }

        // Add Event Listeners
        addCardListeners(card);

        // Append & Blink & Expand
        $('#card-container').append(card);
        card.effect('highlight', {}, 1000);
        if (!card.find('.card-body').hasClass('show'))
            card.find('.close-question')[0].click();

        // Re-enable submit
        $('form').find('button[type=submit]')[0].removeAttribute('disabled');

        // Store data to sessionStorage
        storeFormData();

        window.count++;
        return true;
    };

    let addChoice = function (label = null) {
        label = (typeof label !== 'string') ? 'OPTION' : label;
        let card = $(this).closest('.card');
        let choice = choice_template.clone();

        // replace names
        let name = choice.find('label').first()[0].getAttribute('for').replace(/#/i, card.attr('data-count'));
        choice.find('label').attr('for', name);
        choice.find('input').attr('name', name);

        // add labels
        choice.find('label').first()[0].lastChild.textContent = label;
        choice.find('input').val(label);

        // add event listeners
        choice.find('.add-choice').on('click', addChoice);
        choice.find('.delete-choice').on('click', deleteChoice);

        card.find('.choice-container').append(choice);
    };
    window.addChoice = addChoice;
    window.deleteChoice = function (e) {
        let choice = $(this).closest('.choice');
        if (choice.siblings().length > 0) {
            choice.slideUp('fast', function () {
                $(this).remove();
            });
        } else {
            choice.effect('highlight', {}, 1000);
        }
        return true;
    };

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
        $('button.question-type').removeAttr('disabled');
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
    let c = 0;
    for (let item of old_data) {
        console.debug('old_data', item, c);
        if (item[0].includes('question')) { // questions
            let i = parseInt(item[0].split('[')[1][0]);
            let key = item[0].split('[')[2].split(']')[0];
            // console.debug(i, key);
            if (!array_data['question'][i]) {
                array_data['question'][i] = [];
            }
            if (item[0].includes('max') || item[0].includes('min')) { // all other types
                array_data['question'][i][key] = item[1];
            } else if (item[0].includes('choices') && array_data['question'][i]['choices'] != null) {
                array_data['question'][i][key].push(item[1]); // push choices array
            } else if (item[0].includes('choices') && !array_data['question'][i]['choices']) {
                array_data['question'][i][key] = [item[1]]; // initialize choices array
            } else { // title, subtitle, others...
                array_data['question'][i][key] = item[1];
            }
            c++;
        } else if (item[0].includes('title') || item[0].includes('footnote')) { // form title / subtitle / footnote
            array_data[item[0]] = item[1];
        }
    }
    // console.debug(array_data);
    if (count == 0) {
        // add old cards from 'array_data'
        array_data['question'].forEach((val, i) => {
            // console.debug('array_data', val, i);
            if (val.hasOwnProperty('type') && val.hasOwnProperty('title')) {
                createCard(val.type, val.type + '-' + window.count, val.title, val);
                return true;
            }
        });
    } else { // cards already exists
        // console.debug('array_data', array_data);
        $('.card').each(function () {
            addCardListeners($(this));
        });
        $('.add-choice').on('click', addChoice);
        $('.delete-choice').on('click', deleteChoice);

        // new edited questions
        if (array_data['question'].slice(count).length > 0) {
            array_data['question'].slice(count).forEach(function (val, i) {
                if (val.hasOwnProperty('type') && val.hasOwnProperty('title')) {
                    createCard(val.type, val.type + '-' + window.count, val.title, val);
                    return true;
                }
            });
        }
        // console.debug(array_data['question'].slice($('.card').length));
        // console.debug(q.choices.slice(card.find('.choice').length));
        // array_data['question'].forEach((q, i) => {
        //     // console.debug(val, i);
        //     if (q.type === 'multiple-choice' && q.hasOwnProperty('choices')) {
        //         let card = $($('.card').get(i));
        //         let add = card.find('.add-choice');
        //         let cCount = card.find('.choice').length;
        //         // console.debug(card.find('.choice').length, q.choices, q, i);
        //         // if (q.choices.length < cCount) { // choice deleted
        //         //
        //         // } else if (q.choices.length > cCount) { // choice added
        //         //
        //         // }
        //         // q.choices.slice().forEach(function (label) {
        //         //     addChoice.call(add, label);
        //         //     return true;
        //         // });
        //     }
        //     return true;
        // });
    }
    window.array_data = array_data;
    window.addEventListener('unload', storeFormData);
});
