document.addEventListener('DOMContentLoaded', function (e) {
    // @TODO: extend jQuery add highlight effect
    // $.fn.highlight = function()


    /**
     * @param card jQuery Object of .card
     * @return void
     */
    let addCardListeners = function (card) {
        card.find('.card-body').on('shown.bs.collapse', function (e) {
            $(this).closest('.card').find('.close-question').text('keyboard_arrow_up');
            return true;
        });
        card.find('.card-body').on('hidden.bs.collapse', function (e) {
            $(this).closest('.card').find('.close-question').text('keyboard_arrow_down');
            return true;
        });
        if (card.data('type') === 'multiple-choice') {
            card.find('.add-choice').on('click', addChoice);
            card.find('.delete-choice').on('click', deleteChoice);
        } else if (card.data('type') === 'linear-scale') {

        }
    };
    window.addCardListeners = addCardListeners;

    let addChoice = function (e) {
        let choice = $(this).closest('.form-group').find('.choice').first().clone();
        choice.find('label').first()[0].lastChild.textContent = 'OPTION';
        choice.find('input').val('OPTION');
        $(this).closest('.form-group').find('.choice').last().after(choice);

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

    let cards = $('.card');
    let count = cards.length;

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

    $('button.question-type').on('click', function (e) {
        $('#session_id').combobox('disable');

        // Initialise variables
        let card = $('.card.template').clone();
        let title = `${this.lastChild.textContent} #${count}`;
        let id = title.replace(/[\/#\s\(\)]/g, '-');

        // Add Question type
        card.find("input[type=hidden][name*=type]").val(this.id);
        card.attr('data-type', this.id)
            .removeAttr('id')
            .removeClass('template');

        // Add card title
        card.find('.btn-block[data-title]')
            .data('title', title)
            .text(title);

        // Add bs-collapse data
        card.find('.close-question')
            .attr('data-target', '#' + id)
            .attr('aria-controls', id);
        card.find('.collapse').attr('id', id);

        // Show
        card.removeClass('d-none')
            .attr('data-type', this.id);

        // Replace session_id
        card.find('input[name=session_id]').val(this.id);
        // Replace [#/counts]
        card.find('input[name],label[for]').each(function () {
            if (this.tagName == 'input') {
                $(this).attr('name', this.getAttribute('name').replace(/#/i, count));
            } else if (this.tagName == 'label') {
                $(this).attr('for', this.getAttribute('ofr').replace(/#/i, count));
            }
        });

        // remove extras / show specifics
        switch (this.id) {
            case 'linear-scale':
                card.find('.scale.d-none').removeClass('d-none').addClass('d-block');
                card.find('.multiple').remove();
                break;
            case 'multiple-choice':
                card.find('.multiple.d-none').removeClass('d-none').addClass('d-block');
                card.find('.scale').remove();
                break;
            case 'eval':
            case 'paragraph':
                card.find('.d-none').remove();
                break;
            default:
                return false;
        }

        // Add Event Listeners
        addCardListeners(card);

        // Append to end of cards
        cards.last().after(card);
        // Blink Effect
        card.effect('highlight', {}, 1000);
        // Expand Card
        card.find('.close-question')[0].click();

        $('form').find('button[type=submit]')[0].removeAttribute('disabled');
        // console.debug(card);

        return true;
    });
    $('button[type=submit]').on('click', function (e) {
        $('.card.template').remove();
        return true;
    });

    cards.each(function () {
        console.debug($(this));
        addCardListeners($(this));
    });
});
