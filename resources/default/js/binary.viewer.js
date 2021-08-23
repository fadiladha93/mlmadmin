$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json;',
        }
    });

    $('.legend-header .circle-btn').on('click', function (e) {
        const legend = $(e.target).closest('.legend-wrap');
        legend.toggleClass('open');
    });

    $('.search-header .circle-btn').on('click', function (e) {
        const search = $(e.target).closest('.search-wrap');
        search.toggleClass('open');
    });
    $('.avatar-wrap').on('click', function (e) {
        e.preventDefault();
        window.location.href = $(this).data('href');
    });
});