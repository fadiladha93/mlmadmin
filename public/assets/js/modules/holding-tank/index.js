jQuery(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json;',
        }
    });

    var placementInformation = $("#placementInfo");
    var placeButton = $('#select-placement-btn');
    var placementSelect = $('#placeOptionsControl');
    var pendingSection = $('#pending-section');
    var successModal = $('#holdingTankSuccessModal');
    var warningModal = $('#holdingTankWarningModal');
    var resetIcon = $('.table-search .reset-icon');

    var placementData = {
        direction: 'right',
        distributors: [],
        nodeId: null,
    };

    var pendingUser = {
        id: null,
        distid: null,
        firstname: null,
        lastname: null,
        username: null,
        enrollment: null,
        classname: null,
        rank: null,
    };

    $('.table-search input').on('change paste keyup', onSearchChange);

    function onSearchChange(e) {
        toggleResetIcon(e.target.value);
    }

    function toggleResetIcon(value) {
        var visibility;

        if (value) {
            visibility = 'visible';
        } else {
            visibility = 'hidden';
        }

        resetIcon.css('visibility', visibility);
    }

    function addPendingUser(user) {
        var pendingRow = pendingSection.find('.pending-user-row');

        // clear the previous pending user's data
        pendingRow.empty();

        // update the data with the new one
        Object.keys(user).forEach(function (key) {
            if (key !== 'id') {
                pendingRow.append('<td><div>'+ user[key] +'</div></td>')
            }
        });
    }

    // click the table header checkbox (select/deselect all)
    $('#checkbox-dist-thead').on('click', function (e) {
        e.preventDefault();
        if ($('#checkbox-dist-thead input').is(':checked')) {
            $('#checkbox-dist-thead input').prop('checked', false);
            $('.check-dist-row').prop('checked', false);
        } else {
            $('#checkbox-dist-thead input').prop('checked', true);
            $('.check-dist-row').prop('checked', true);
        }

        onCheckboxChange();
    });

    // click the table checkbox
    $('.check-dist-row').on('change', onCheckboxChange);
    $('#warningModalContinueBtn').on('click', onContinueBtnClick);

    function onCheckboxChange() {
        reCalculateDistributors();
        $("#placeOptionsControl option").removeAttr('disabled');

        // no any pending user
        if (placementData.distributors.length === 0) {
            $('.pending-table').hide();
            pendingSection.find('.no-select-placeholder').show();
            pendingSection.find('.multiple-select-placeholder').hide();
            setPlacementOption('right');
        }

        // if one user - add it to the pending section via table
        if (placementData.distributors.length === 1) {
            $('#js-holding-tank-distrib-table tbody')
                .find('tr[data-id=' + placementData.distributors[0] + '] td')
                .each(function (idx, value) {
                    var pair = $(value).data();
                    for (var key in pair) {
                        if (key !== 'checkbox') {
                            pendingUser[key] = pair[key];
                        }
                    }
                });

            addPendingUser(pendingUser);
            pendingSection.find('.multiple-select-placeholder').hide();
            pendingSection.find('.no-select-placeholder').hide();
            $('.pending-table').show();

            $("#placeOptionsControl option[value=auto]").attr('disabled','disabled');

            if (placementData.direction === 'auto') {
                setPlacementOption('right');
            }
        }

        // multiple selection
        if (placementData.distributors.length > 1) {
            $('.pending-table').hide();
            pendingSection.find('.multiple-select-placeholder').show();
            pendingSection.find('.no-select-placeholder').hide();
        }
    }

    function setPlacementOption(place) {
        setPlacementData(place);
        placementInformation.attr("placeholder", placementOptions[place].label);
        placementSelect.val(place);
    }

    placementSelect.on('change', function () {
        var selectedPlace = this.value;

        // update the control current state (placeholder)
        setPlacementOption(selectedPlace);
    });

    function setPlacementData(option) {
        placementData = {
            direction: option,
            nodeId: placementOptions[option].id,
            distributors: placementData.distributors
        }
    }

    function reCalculateDistributors() {
        var distributors = [];

        $('input:checked.check-dist-row')
            .each(function (item) {
                distributors.push($(this).data('id'));
            });

        placementData.distributors = distributors;
    }

    function removeDistributors() {
        var pendingRow = pendingSection.find('.pending-user-row');

        pendingUser = {
            id: null,
            distid: null,
            firstname: null,
            lastname: null,
            username: null,
            enrollment: null,
            classname: null,
            rank: null,
        };

        // clear the Pending Section
        pendingRow.empty();
        $('.pending-table').hide();
        pendingSection.find('.no-select-placeholder').show();
        pendingSection.find('.multiple-select-placeholder').hide();

        placementData.distributors.map(function (id) {
            $("#js-holding-tank-distrib-table").find(`tr[data-id='${id}']`).remove();
        });
    }

    placeButton.on('click', function (e) {
        e.preventDefault();

        if (placementData.distributors.length < 1) { return; }

        warningModal.modal('show');
    });

    function onContinueBtnClick() {
        warningModal.modal('hide');
        $('.modal-backdrop').remove();

        // yes, it's not optimized for the large array
        reCalculateDistributors();

        sendPostAjax(
            baseUrl + '/placement-lounge/distributors/place',
            placementData,
            function (result) {
                var message = '';
                placementOptions = result.options;
                successModal.find('.modal-header').hide();

                if (result.message) {
                    message = result.message;
                } else {
                    message = 'Your placement has been completed.';
                }

                removeDistributors();
                successModal.find('.modal-body').text(message);

                setTimeout(function () {
                    successModal.modal('show');
                }, 100);

                // re-init page data
                init();
                $("#placeOptionsControl option").removeAttr('disabled');
                placementData.distributors = [];
            },
            function (result) {
                successModal.find('.modal-header').show();
                successModal.find('.modal-body').text(result.responseJSON.error || result.responseJSON.message);

                setTimeout(function () {
                    successModal.modal('show');
                }, 100);
            }
        )
    }

    function sendPostAjax(url, data, callback, callbackError) {
        $.ajax({
            type: 'POST',
            url: url,
            data: JSON.stringify(data),
            cache: false,
            dataType: 'json',
            success: callback,
            error: callbackError
        });
    }

    function toggleNoDistributorsMessage() {
        var isEmptyTable = $('#js-holding-tank-distrib-table').length === 0 || $("#js-holding-tank-distrib-table tbody tr").length === 0;

        if (!isEmptyTable) { return; }

        $('.no-available-distributors').show();
        $('#js-holding-tank-distrib-table').hide();
    }

    // do any default things
    function init() {
        setPlacementOption('right');
        pendingSection.find('.no-select-placeholder').show();
        toggleNoDistributorsMessage();
        toggleResetIcon($('.table-search input')[0].value);
    }

    init();
});