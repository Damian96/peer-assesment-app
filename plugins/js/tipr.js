/*
Tipr 4.1
Copyright (c) 2019 Tipue
Tipr is released under the MIT License
http://www.tipue.com/tipr
*/

(function ($) {

    $.fn.tipr = function (options) {

        var set = $.extend({

            'alt': false,
            'marginAbove': -65,
            'marginBelow': 7,
            'mode': 'below',
            'space': 70,
            'speed': 300

        }, options);

        return this.each(function () {

            var mouseY = -1;
            $(document).on('mousemove', function (event) {
                mouseY = event.clientY;
            });
            var viewY = $(window).height();

            $(this).hover(
                function () {
                    var m = set.mode;
                    var m_alt = set.alt;
                    var a = '';
                    var m_a = set.marginAbove;
                    var m_b = set.marginBelow;

                    $(window).on('resize', function () {
                        viewY = $(window).height();
                    });

                    if (viewY - mouseY < set.space) {
                        m = 'above';
                    } else {
                        m = set.mode;

                        if ($(this).attr('data-mode')) {
                            m = $(this).attr('data-mode')
                        }
                    }

                    if ($(this).attr('data-alt')) {
                        m_alt = $(this).attr('data-alt')
                    }
                    if ($(this).attr('data-marginAbove')) {
                        m_a = $(this).attr('data-marginAbove')
                    }
                    if ($(this).attr('data-marginBelow')) {
                        m_b = $(this).attr('data-marginBelow')
                    }

                    if (m_alt) {
                        a = '_alt';
                    }
                    tipr_cont = '.tipr_container_' + m + a;

                    var out = '<div class="tipr_container_' + m + a + '" style="margin-top: ';

                    if (m == 'above') {
                        out += m_a + 'px;">';
                    } else {
                        out += m_b + 'px;">';
                    }

                    out += '<div class="tipr_point_' + m + a + '"><div class="tipr_content' + a + '">' + $(this).attr('data-tip') + '</div></div></div>';

                    $(this).after(out);

                    // var c_l = ($(tipr_cont).offset()).left;
                    // var c_l = $(this).offsetRelative(this.parentElement);
                    var nbsp = 2 * ($(this).index() + 1);
                    var w_t = $(tipr_cont).outerWidth();
                    var w_e = $(this).width();
                    var c_l = this.offsetLeft;
                    var m_l = ((w_e / 2) * ($(this).index() + 1)) - (w_t / 2);
                    // var p_l = c_l - (w_e * 2) + nbsp + (w_t / 2);
                    // var p_l = this.offsetParent.offsetWidth + c_l + ($(this).index() * w_e);
                    var p_l = c_l - (w_t / 2) + (w_e / 2);

                    // console.debug('tipr:', p_l, $(this).index());
                    $(tipr_cont).css('left', `${p_l}px`);
                    // $(tipr_cont).css('transform', `translateX(${m_l}px)`);
                    // $(tipr_cont).css('margin-left', m_l + 'px');
                    // $(tipr_cont)[0].style.marginLeft = m_l + 'px';
                    // console.debug($(tipr_cont)[0].style.marginLeft);
                    $(this).removeAttr('title alt');

                    $(tipr_cont).fadeIn(set.speed);
                },
                function () {
                    $(tipr_cont).remove();
                }
            );
        });
    };

})(jQuery);
