// document.addEventListener('DOMContentLoaded', function () {
//     // setTimeout(function () {
//     //     // console.debug(document.getElementById('ui-datepicker-div'));
//     //     let dpDiv = $(document.getElementById('ui-datepicker-div'));
//     //     if (dpDiv.length) {
//     //         // for (let dpDiv of document.getElementById('ui-datepicker-div')) {
//     //         // console.debug(dpDiv.find('.ui-datepicker-header a[title]'), dpDiv.find('td[data-event'));
//     //         dpDiv = $(dpDiv);
//     //         dpDiv.attr('tabindex', '-1');
//     //         dpDiv.find('.ui-datepicker-header a[title]').each(function (i, item) {
//     //             console.debug(this, i, item);
//     //             // item = item[0];
//     //             item.dataset.title = item.title;
//     //             item.setAttribute('aria-label', item.title);
//     //             item.tabIndex = this.find('.ui-datepicker-year')[0].innerText;
//     //         }.bind(dpDiv));
//     //         dpDiv.find('td[data-event]').each((i, item) => {
//     //             let date = $(item).data('year') + '-' + $(item).data('month') + '-' + $(item).find('a')[0].innerText;
//     //             $(item).attr('aria-label', `Pick date: ${date}`);
//     //             $(item).attr('title', `Pick date: ${date}`);
//     //             $(item).attr('tabindex', `${parseInt($(item).data('year')) + parseInt($(item).data('month')) + parseInt($(item).find('a')[0].innerText)}`);
//     //             $(item).find('a').attr('tabindex', '-1');
//     //             // console.debug(item);
//     //         });
//     //         // }
//     //     }, 500);
//
//     $.datepicker.setDefaults({
//         beforeShow: function (input, inst) {
//             console.debug(this, input, inst);
//         }
//         //     inst.dpDiv[0].addEventListener('focusout', function (input, e) {
//         //         if (window.getSelection().focusNode.nodeType === Node.ELEMENT_NODE && window.getSelection().focusNode.classList.includes('navbar-brand')) {
//         //             $(input).focus();
//         //             return true;
//         //         }
//         //     }.bind(this, input));
//         //     setTimeout(function (dpDiv) {
//         //         dpDiv.focus();
//         //     }.bind(inst.dpDiv, inst.dpDiv), 500);
//         //     return inst.settings;
//         // },
//         // onSelect: function (date, inst) {
//         //     // console.debug(this, date, inst);
//         //     $(this).focus();
//         // }
//     });
//     // }, 2000);
// });

window.addEventListener('load', function () {
    // $.extend($.ui, {
    //     datepicker: {
    //         _updateDatepicker: function (inst) {
    //             console.debug(inst, this);
    //             $.datepicker._updateDatepicker(inst);
    //         }
    //     }
    // });

    /* Invoke the datepicker functionality.
   @param  options  string - a command, optionally followed by additional parameters or
					Object - settings for attaching new datepicker functionality
   @return  jQuery object */
    // $.fn.datepicker = function (options) {
    //
    //     /* Verify an empty collection wasn't passed - Fixes #6976 */
    //     if (!this.length) {
    //         return this;
    //     }
    //
    //     /* Initialise the date picker. */
    //     if (!$.datepicker.initialized) {
    //         $(document).on("mousedown", $.datepicker._checkExternalClick);
    //         $.datepicker.initialized = true;
    //     }
    //
    //     /* Append datepicker main container to body if not exist. */
    //     if ($("#" + $.datepicker._mainDivId).length === 0) {
    //         $("body").append($.datepicker.dpDiv);
    //     }
    //
    //     var otherArgs = Array.prototype.slice.call(arguments, 1);
    //     if (typeof options === "string" && (options === "isDisabled" || options === "getDate" || options === "widget")) {
    //         return $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this[0]].concat(otherArgs));
    //     }
    //     if (options === "option" && arguments.length === 2 && typeof arguments[1] === "string") {
    //         return $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this[0]].concat(otherArgs));
    //     }
    //     return this.each(function () {
    //         typeof options === "string" ?
    //             $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this].concat(otherArgs)) :
    //             $.datepicker._attachDatepicker(this, options);
    //     });
    // };

    // $.widget("ui.datepicker", {
    /* Generate the date picker content. */
    //     this.maxRows = 4; //Reset the max number of rows being displayed (see #7043)
    //     datepicker_instActive = inst; // for delegate hover events
    //     inst.dpDiv.empty().append(this._generateHTML(inst));
    //     this._attachHandlers(inst);
    //
    //     var origyearshtml,
    //         numMonths = this._getNumberOfMonths(inst),
    //         cols = numMonths[1],
    //         width = 17,
    //         activeCell = inst.dpDiv.find("." + this._dayOverClass + " a");
    //
    //     if (activeCell.length > 0) {
    //         datepicker_handleMouseover.apply(activeCell.get(0));
    //     }
    //
    //     inst.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width("");
    //     if (cols > 1) {
    //         inst.dpDiv.addClass("ui-datepicker-multi-" + cols).css("width", (width * cols) + "em");
    //     }
    //     inst.dpDiv[(numMonths[0] !== 1 || numMonths[1] !== 1 ? "add" : "remove") +
    //     "Class"]("ui-datepicker-multi");
    //     inst.dpDiv[(this._get(inst, "isRTL") ? "add" : "remove") +
    //     "Class"]("ui-datepicker-rtl");
    //
    //     if (inst === $.datepicker._curInst && $.datepicker._datepickerShowing && $.datepicker._shouldFocusInput(inst)) {
    //         inst.input.trigger("focus");
    //     }
    //
    //     // Deffered render of the years select (to avoid flashes on Firefox)
    //     if (inst.yearshtml) {
    //         origyearshtml = inst.yearshtml;
    //         setTimeout(function () {
    //
    //             //assure that inst.yearshtml didn't change.
    //             if (origyearshtml === inst.yearshtml && inst.yearshtml) {
    //                 inst.dpDiv.find("select.ui-datepicker-year:first").replaceWith(inst.yearshtml);
    //             }
    //             origyearshtml = inst.yearshtml = null;
    //         }, 0);
    //     }
    // }
// })
//     ;
});
