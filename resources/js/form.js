document.addEventListener('DOMContentLoaded', function (e) {
    if (!document.querySelector('.btn.question-type')) {
        return false;
    }

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
        while ($(`input[name^='question[${count}]`).length > 0) {
            count++;
            replace = `[${(count).toString()}]`
        }
        // console.debug(count, replace, $(`input[name^='question[${count}]`));

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
        // card.find('.moveup-question').on('click', function (card, e) {
        //     // console.debug(card, e);
        //     let cCount = parseInt(card[0].getAttribute('data-count'));
        //     let prev = card.prev();
        //     replaceCardNames(card, cCount - 1);
        //     replaceCardNames(prev, cCount);
        //     if (prev.hasClass('card')) {
        //         prev.before(card);
        //         card.highlight(500);
        //     }
        //
        //     return true;
        // }.bind(null, card));
        // card.find('.movedown-question').on('click', function (card, e) {
        //     // console.debug(card);
        //     let cCount = parseInt(card[0].getAttribute('data-count'));
        //     let next = card.next();
        //     replaceCardNames(card, cCount + 1);
        //     replaceCardNames(next, cCount);
        //     if (next.hasClass('card')) {
        //         next.after(card);
        //         card.highlight(500);
        //         card.find('.bg-info').first().highlight(500);
        //     }
        //
        //     return true;
        // }.bind(null, card));
        card.find('.delete-question').on('click', function (card, e) {
            // let i = $(this).data('count');
            console.debug(arguments);

            window.count--;

            card.slideUp('fast', function () {
                this.remove();
            });

            return true;
        }.bind(null, card));
        card.find('input[name*="[title]"]').on('change', function () {
            $(this).closest('.card').find('.card-title-content').text(` ${this.value}`);
        });
    };

    if (window.count > 0) {
        $('.form-group.d-none').remove();
        $('.card.col-sm-12').each((i, item) => {
            addCardListeners($(item));
        });
    }

    /**
     * @param {String} type The card's type
     * @param {String} id The card's id
     * @param {String} title The card's title
     * @param {object} data The card's question data
     * @returns {boolean}
     */
    window.createCard = function (type, id, title = null, data = null) {
        // console.debug(arguments);

        // Initialise variables
        let card = card_template.clone();
        // let subtitle = '';
        if (data)
            title = (title ? title : data.title);

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
            case 'likert-scale':
                card.find('.card-title > i.material-icons').text('linear_scale');
                card.find('.scale.d-none').removeClass('d-none').addClass('d-block');
                card.find('.multiple').remove();
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
            case 'criterion':
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

    /**
     * @param {String} label
     * @return void
     */
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
    /**
     * @return {boolean}
     */
    window.deleteChoice = function () {
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
        createCard(this.id, `${this.id}-${window.count + 1}`, `${this.id}#${window.count + 1}`);
        return true;
    });
    $('#collapse-all').on('click', () => {
        $('.close-question').each((i, btn) => {
            if (btn.getAttribute('aria-expanded') === 'true')
                btn.click();
        });
    });
    $('#expand-all').on('click', () => {
        $('.close-question').each((i, btn) => {
            if (btn.getAttribute('aria-expanded') !== 'true')
                btn.click();
        });
    });
    $('.delete-choice').on('click', deleteChoice);
    $('.add-choice').on('click', addChoice);
    $(document).on('submit', 'form', function () {
        window.formData = storeFormData();
        window.onbeforeunload = null;
        return true;
    });
    window.formData = storeFormData();
    window.onbeforeunload = function () {
        if (formData.includes('question')) {
            return 'Are you sure you want to leave? If you leave you will lose all your changes!';
        }
    };
});
