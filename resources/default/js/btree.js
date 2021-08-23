$(document).ready(function () {

    $('.avatar').on('click', function (e) {
        const distributorWrap = $(e.target).closest('.distributor-wrap');
        const isOpenPosition = distributorWrap.hasClass('open-position') || distributorWrap.hasClass('pending-position');

        if (isOpenPosition) { return; }

        $('#distributor-details').modal({ 'show': true });
    });

    $('.legend-header .circle-btn').on('click', function (e) {
        const legend = $(e.target).closest('.legend-wrap');
        legend.toggleClass('open');
    });

    $('.search-header .circle-btn').on('click', function (e) {
        const legend = $(e.target).closest('.search-wrap');
        legend.toggleClass('open');
    });
});