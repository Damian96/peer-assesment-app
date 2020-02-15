document.addEventListener('DOMContentLoaded', function () {
    let fadeOutRM = function (el) {
        $(el).fadeOut('fast', function () {
            el.remove();
        });
    };
    for (let alert of document.querySelectorAll('.alert')) {
        alert.classList.remove('hidden');
        alert.classList.add('d-inline-block');
        alert.style.top = alert.getClientRects()[0].y - alert.offsetHeight + 'px';
        alert.style.left = alert.getClientRects()[0].x - alert.offsetWidth + 'px';
        let timeID = setTimeout(function () {
            if (!isHover(alert))
                fadeOutRM(alert);
            else
                clearTimeout(timeID)
        }, 5000);
    }
    for (let close of document.querySelectorAll('.alert-close')) {
        close.addEventListener('click', function () {
            $(this.parentElement).fadeOut('fast', function () {
                this.remove();
            });
        }, {once: true});
    }
});
