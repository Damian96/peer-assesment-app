document.addEventListener('DOMContentLoaded', function (e) {
    if (!document.querySelector('.btn.question-type')) {
        return false;
    }

    // let old_data = sessionStorage.getItem(document.querySelector('form').getAttribute('name'));
    // old_data = new URLSearchParams(old_data);
    // window.old_data = old_data;
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
        // console.log('replace', count);
        card.attr('data-count', count);
        card.find('input[name]').each(function (i, item) {
            // console.debug($(this), count);
            $(item).attr('name', this.getAttribute('name').replace(patt, replace));
        });
        card.find('label[for]').each(function (i, item) {
            $(item).attr('for', this.getAttribute('for').replace(patt, replace));
        });
    };

    window.storeFormData = function () {
        let form = $('form');
        let key = form.attr('name');
        let data = new URLSearchParams(new FormData(form[0])).toString();
        sessionStorage.setItem(key, data);
        return data;
    };

    window.count = $(".card.col-sm-12").length;

    window.addCardListeners = function (card) {
        // console.debug(card);
        card.find('.card-body').on('shown.bs.collapse', function (e) {
            $(this).closest('.card').find('.close-icon.material-icons').text('keyboard_arrow_up');
            return true;
        });
        card.find('.card-body').on('hidden.bs.collapse', function (e) {
            $(this).closest('.card').find('.close-icon.material-icons').text('keyboard_arrow_down');
            return true;
        });
        card.find('.moveup-question').on('click', function (card, e) {
            // console.debug(card, e);
            let cCount = parseInt(card[0].getAttribute('data-count'));
            let prev = card.prev();
            replaceCardNames(card, cCount - 1);
            replaceCardNames(prev, cCount);
            if (prev.hasClass('card')) {
                prev.before(card);
                card.highlight(500);
            }
            // if (!window.location.href.includes('edit'))
            //     storeFormData();
            return true;
        }.bind(null, card));
        card.find('.movedown-question').on('click', function (card, e) {
            // console.debug(card);
            let cCount = parseInt(card[0].getAttribute('data-count'));
            let next = card.next();
            replaceCardNames(card, cCount + 1);
            replaceCardNames(next, cCount);
            if (next.hasClass('card')) {
                next.after(card);
                card.highlight(500);
                card.find('.bg-info').first().highlight(500);
            }
            // if (!window.location.href.includes('edit'))
            //     storeFormData();
            return true;
        }.bind(null, card));
        card.find('.delete-question').on('click', function (card, e) {
            let i = $(this).data('count');
            // console.debug(card, e, i);
            // array_data.question = array_data.question.splice(i, 1);
            window.count--;
            if (window.count == 1) {
                // $('#session_id').combobox('enable');
                // $('button.question-type').removeAttr('disabled');
                // $('button[type=submit]').attr('disabled', true);
            }
            card.slideUp('fast', function () {
                this.remove();
            });
            // if (!window.location.href.includes('edit'))
            //     storeFormData();
            return true;
        }.bind(null, card));
        card.find('input[name*="[title]"]').on('change', function () {
            $(this).closest('.card').find('.card-title-content').text(` ${this.value}`);
        });
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
        // let subtitle = '';
        let minlbl = 'Highly Disagree';
        let maxlbl = 'Highly Agree';
        if (data) {
            title = (title ? title : data.title);
            max = data.hasOwnProperty('max') && data.max != null ? data.max : 5;
            // subtitle = ((data.hasOwnProperty('subtitle') && data.subtitle != null) ? data.subtitle : '');
            minlbl = data.hasOwnProperty('minlbl') && data.minlbl != null ? data.minlbl : 'Highly Disagree';
            maxlbl = data.hasOwnProperty('maxlbl') && data.maxlbl != null ? data.maxlbl : 'Highly Agree';
        }

        // Add Question type
        card.find("input[name*=type]").val(type);
        card.attr('data-type', type)
            .removeAttr('id')
            .removeClass('template')
            .removeClass('d-none');

        // Add card title
        card.find('.card-title-content').text(title);
        card.find('input[name*="[title]"]')
            .val(title);
        // .find('input[name*="subtitle"]');
        // .val(subtitle);

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
                card.find('.card-title > i.material-icons').text('linear_scale');
                card.find('.scale.d-none').removeClass('d-none').addClass('d-block');
                card.find('.multiple').remove();
                card.find('input[name*="[max]"]').val(max);
                card.find('input[name*="[minlbl]"]').val(minlbl);
                card.find('input[name*="[maxlbl]"]').val(maxlbl);
                card.find('input[name*=max]').first()[0].onchange();
                break;
            case 'multiple-choice':
                card.find('.card-title > i.material-icons').text('radio_button_checked');
                card.find('.multiple.d-none').removeClass('d-none').addClass('d-block');
                card.find('.scale').remove();
                card.find('.add-choice').on('click', addChoice);
                card.find('.delete-choice').on('click', deleteChoice);
                if (data && data.hasOwnProperty('choices') && data.choices.length > 0) {
                    card.find('.choice').remove();
                    data.choices.forEach((choice) => {
                        addChoice.call(card.find('.add-choice')[0], choice);
                    });
                }
                break;
            case 'eval':
                card.find('.card-title > i.material-icons').text('filter_5');
                card.find('.multiple').remove();
                card.find('.scale').remove();
                break;
            case 'paragraph':
                card.find('.card-title > i.material-icons').text('format_align_justify');
                card.find('.multiple').remove();
                card.find('.scale').remove();
                break;
            case 'criteria':
                card.find('.card-title > i.material-icons').text('account_circle');
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
        card.highlight(500);
        if (!card.find('.card-body').hasClass('show'))
            card.find('.close-question')[0].click();

        // Re-enable submit
        $('form').find('button[type=submit]')[0].removeAttribute('disabled');

        window.count++;
        window.formData = storeFormData();
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
            choice.highlight(500);
        }
        return true;
    };

    $('button.question-type').on('click', function () {
        createCard(this.id, `${this.id}-${window.count + 1}`, `${this.id} - #${window.count + 1}`);
        return true;
    });
    $(document).on('submit', 'form', function () {
        window.formData = storeFormData();
        return true;
    });
    window.formData = storeFormData();
    if (window.location.href.includes('forms/edit') || window.location.href.includes('forms/create')) {
        window.onbeforeunload = function () {
            if (formData.includes('question')) {
                return 'Are you sure you want to leave? If you leave you will lose all your changes!';
            }
        };
    }
});
