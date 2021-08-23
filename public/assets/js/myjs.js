/*  if you're looking for variables you cannot find look on line 4985 and look in the kitchen sink */
var js_myjs = (function() {
    var burl = $("#burl").text();
    var _tok = $('meta[name="_token"]').attr("content");

    var h_default = function() {
        $(".date_picker").datepicker({
            format: "yyyy/mm/dd"
        });

        $(".date_picker2").datepicker({
            format: "yyyy-mm-dd"
        });

        if ($(".summernote").length) {
            $(".summernote").summernote({
                height: 150
            });
        }

        $(".date_picker3").datepicker({
            format: "yyyy-mm-dd",
            startDate: "tomorrow",
            endDate: "+2m"
        });
    };

    var h_login = function() {
        $("#btnLogin").click(function() {
            doLogin();
        });

        $("#frmLogin input").keypress(function(e) {
            if (e.which == 13) {
                doLogin();
                return false;
            }
        });

        function doLogin() {
            var u = $("#login_url").val();
            ajPost($("#frmLogin").serialize(), "/" + u, "login");
        }

        $("#btnIgo4Less").click(function() {
            ajPost($("#frmLogin").serialize(), "/login-to-igo4less");
        });

        $("#frm2FALogin input").keypress(function(e) {
            if (e.which == 13) {
                do2FALogin();
                return false;
            }
        });

        $("#btn2FALogin").click(function() {
            do2FALogin();
        });

        function do2FALogin() {
            ajPost($("#frm2FALogin").serialize(), "/login-2fa");
        }
    };

    var h_register = function() {
        $("#btnSignUp").click(function() {
            doRegister();
        });

        $("#frmSignup input").keypress(function(e) {
            if (e.which == 13) {
                doRegister();
                return false;
            }
        });

        if ($("#dd_thankyou").length) {
            $("#dd_thankyou").modal("show");
        }

        function doRegister() {
            ajPost($("#frmSignup").serialize(), "/sign-up", "sign-up");
        }
    };

    var h_user = function() {
        $("#billingAddressSelect").change(function() {
            var value = $(this)
                .find(":selected")
                .val();

            if (value == -1) {
                $("#addressForm").show();
                $("#btnAddCard").text("Add card & address");
            } else {
                $("#addressForm").hide();
                $("#btnAddCard").text("Add card");
            }
        });

        $("#btnNewIntern").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmNewIntern")
                    .find(":input")
                    .serialize(),
                "/add-new-ambassador",
                "add-new-ambassador"
            );
        });

        $("#btnUpdateIntern").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmUpdateIntern")
                    .find(":input")
                    .serialize(),
                "/update-ambassador",
                "update-ambassador"
            );
        });

        $("#btnChangePass").click(function() {
            ajPost($("#frmChangePass").serialize(), "/change-password");
        });

        $("#btnEditCustomer").click(function() {
            ajPost(
                $("#frmEditCustomer")
                    .find(":input")
                    .serialize(),
                "/update-customers"
            );
        });

        $("#btnAddAdminUser").click(function() {
            ajPost(
                $("#frmNewAdmin")
                    .find(":input")
                    .serialize(),
                "/add-new-admin"
            );
        });

        $("#btnSavePlacements").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmPlacements").serialize(),
                "/save-placements",
                "save-placements"
            );
        });

        $("#btnSaveProfile").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmMyProfile").serialize(),
                "/save-profile",
                "save-profile"
            );
        });

        $("#btnSaveAddress").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmMyPrimaryAddress").serialize(),
                "/save-primary-address",
                "save-primary-address"
            );
        });

        $("#btnSaveBillingAddress").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmMyBillingAddress").serialize(),
                "/save-billing-address",
                "save-billing-address"
            );
        });

        $("#btnSaveIdecidePassword").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmIdecidePassword").serialize(),
                "/reset-idecide-password",
                "reset-idecide-password"
            );
        });

        $("#btnSaveIdecideEmail").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmIdecideEmail").serialize(),
                "/reset-idecide-mail",
                "reset-idecide-mail"
            );
        });

        $("#btnSavePayap").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost($("#frmMyPayap").serialize(), "/save-payap", "save-payap");
        });

        $("#btnSavePayapSSN").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmMyPayapSSN").serialize(),
                "/save-payap-ssn",
                "save-payap-ssn"
            );
        });

        $("#btnSavePrimaryCard").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmMyPrimaryCard").serialize(),
                "/save-primary-card",
                "save-primary-card"
            );
        });

        $("#btnAddCard").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmAddNewCard").serialize(),
                "/billing-add-new-card",
                "billing-add-new-card"
            );
        });

        $(".deletePaymentMethodButton").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Deleting...");
            let form = $(this).closest("#delete-payment-method");

            ajPost(
                form.serialize(),
                "/delete-payment-method",
                "delete-payment-method"
            );
        });

        $("#btnUpdateBoomerang").click(function() {
            ajPost(
                $("#frmBoomerang")
                    .find(":input")
                    .serialize(),
                "/set-new-boom-total"
            );
        });

        $("#btnUpdateMaxBoomerang").click(function() {
            ajPost(
                $("#frmMaxBoomerang")
                    .find(":input")
                    .serialize(),
                "/set-max-boom-available"
            );
        });

        $("#btnUpdateSubscriptionProduct").click(function() {
            ajPost(
                $("#frmSubscriptionProduct")
                    .find(":input")
                    .serialize(),
                "/save-subscription-product"
            );
        });

        $("#btnClearCoApplicantInfo").click(function() {
            $("#inputCoApplicantName").val("");
            $("#inputCoApplicantEmail").val("");
            $("#inputCoApplicantPhone").val("");
            $("#inputCoApplicantMobile").val("");
        });

        $("#btnSorTransfer").click(function() {
            $(this).prop("disabled", true);
            ajPost(
                $("#frmSorTransfer")
                    .find(":input")
                    .serialize(),
                "/save-on-transfer",
                "save-on-transfer"
            );
        });

        $("#btnRemoveFromMailgun").click(function(e) {
            e.preventDefault();
            $(this).prop("disabled", true);
            $.ajax({
                type: "GET",
                url:
                    burl + "/remove-from-mailgun/" + $('[name="distid"]').val(),
                success: function(data) {
                    $("#btnRemoveFromMailgun").prop("disabled", false);

                    if (data.error == 1) {
                        errMsg(data.msg);

                        return;
                    }
                    okMsg(data.msg);
                },
                error: function(data) {
                    $("#btnRemoveFromMailgun").prop("disabled", false);
                    errMsg("An unexpected error occured.");
                }
            });
        });

        $("#btnResendWelcomeEmail").click(function(e) {
            e.preventDefault();
            $(this).prop("disabled", true);
            $.ajax({
                type: "GET",
                url:
                    burl +
                    "/resend-welcome-email/" +
                    $('[name="distid"]').val(),
                success: function(data) {
                    $("#btnResendWelcomeEmail").prop("disabled", false);

                    if (data.error == 1) {
                        errMsg(data.msg);

                        return;
                    }
                    okMsg(data.msg);
                },
                error: function(data) {
                    $("#btnResendWelcomeEmail").prop("disabled", false);
                    errMsg("An unexpected error occured.");
                }
            });
        });

        $("#btnCreateIDecide").click(function() {
            $(this).prop("disabled", true);
            ajPost(
                $("#frmIdecide")
                    .find(":input")
                    .serialize(),
                "/create-idecide",
                "create-idecide"
            );
        });

        $("#btnCreateTVIDecide").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost("", "/create-tv-idecide", "create-tv-idecide");
        });

        if ($("#dt_countries").length) {
            $("#dt_countries").DataTable({
                ajax: "dt-countries",
                columns: [
                    { data: "payment_type", name: "payment Type" },
                    { data: "country", name: "Country" },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }
        if ($("#dt_merchants").length) {
            $("#dt_merchants").DataTable({
                ajax: "dt_merchants",
                columns: [
                    { data: "pay_method_name", name: "Payment Type" },
                    { data: "type", name: "Type", defaultContent: "-" },
                    {
                        data: "limit_coach",
                        name: "Coach Limit",
                        defaultContent: "<i>Not set</i>"
                    },
                    {
                        data: "limit_business_class",
                        name: "Business Limit",
                        defaultContent: "<i>Not set</i>"
                    },
                    {
                        data: "limit_first_class",
                        name: "First_Class Limit",
                        defaultContent: "<i>Not set</i>"
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }
        if ($("#dt_interns").length) {
            var dt_interns = $("#dt_interns").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: {
                    url: burl + "/dt-ambassador",
                    data: function(d) {
                        return $.extend({}, d, {
                            filterByEnrollmentpack: $(
                                "#filterByEnrollmentpack"
                            ).val()
                        });
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "email" },
                    { data: "username" },
                    { data: "monthly_rank_desc" },
                    { data: "country_code" },
                    { data: "current_product_id" },
                    { data: "account_status" },
                    { data: "mobilenumber" },
                    { data: "created_dt" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" target="_blank" href="` +
                                burl +
                                `/ambassador/` +
                                full.distid +
                                `"><i class="la la-edit"></i> Edit Details</a>
                                <a class="dropdown-item" target="_blank" href="` +
                                burl +
                                `/enrollements/` +
                                full.distid +
                                `"><i class="la la-edit"></i> Enrollments</a>
                                <a class="dropdown-item login-as-ambassador" data-distid="` +
                                full.distid +
                                `" href="#"><i class="la la-leaf"></i> Login as ambassador</a>
                                <a href="#" class="dropdown-item showDlgHistory" tag2="update-history" tag="` +
                                burl +
                                `/dlg-update-history/USER/` +
                                full.id +
                                `"><i class="la la-book"></i> Update History</a>
                            </div>
                        </span>`
                            );

                            //<a class="dropdown-item" href="` + burl + `/login-as-user/` + full.distid + `"><i class="la la-leaf"></i> Login as ambassador</a>
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            return data;
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, full, meta) {
                            if (full.email_verified == 0) return data;
                            else
                                return (
                                    '<i class="fa fa-check" title="Email Verified" style="color:green;"></i> ' +
                                    data
                                );
                        }
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            var en_pack = {
                                "2": {	
                                    title: "Basic Pack",	
                                    icon: "EOR_pack_icon_basic.png"	
                                },	
                                "3": {	
                                    title: "Visionary Pack",	
                                    icon: "EOR_pack_icon_visionary.png"	
                                }
                            };
                            if (typeof en_pack[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<a href="#" data-toggle="tooltip" title="' +	
                                en_pack[data].title +	
                                '"><img src="' +	
                                burl +	
                                "/assets/images/" +	
                                en_pack[data].icon +	
                                '" /></a>'	
                            );
                        }
                    },
                    {
                        targets: 8,
                        render: function(data, type, full, meta) {
                            var status = {
                                PENDING: {
                                    title: "ON-HOLD",
                                    class: "m-badge--primary"
                                },
                                APPROVED: {
                                    title: "ACTIVE",
                                    class: " m-badge--success"
                                },
                                SUSPENDED: {
                                    title: "SUSPENDED",
                                    class: " m-badge--danger"
                                },
                                TERMINATED: {
                                    title: "TERMINATED",
                                    class: " m-badge--danger"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });

            $("#exp_intern").on("click", function(e) {
                var fr_by_en = $("#filterByEnrollmentpack").val();
                if (fr_by_en === "") {
                    fr_by_en = -1;
                }
                var f = $("#dt_interns")
                    .dataTable()
                    .fnSettings();
                var q = $("#dt_interns_filter input").val();
                var i = f.aaSorting[0][0];
                var o = f.aaSorting[0][1];
                var u =
                    burl +
                    "/exp-ambassador/" +
                    f.aoColumns[i].data +
                    "/" +
                    o +
                    "/" +
                    fr_by_en +
                    "/" +
                    q;
                window.location.replace(u);
            });

            if ($("#filterByEnrollmentpack").length) {
                $("#filterByEnrollmentpack").change(function() {
                    dt_interns.draw();
                });
            }

            $(document).on("click", ".login-as-ambassador", function() {
                console.log("Getting distid -> " + jQuery(this).data("distid"));
                $.ajax({
                    type: "GET",
                    url:
                        "/auth-token-ambassador/" + jQuery(this).data("distid"),
                    success: function(data) {
                        if (data.error == 1) {
                            errMsg(data.msg);

                            return;
                        }

                        window.open(data.ambassador_url);
                        // okMsg(data.msg);
                    },
                    error: function(data) {
                        console.log("An unexpected error occured.");
                        errMsg("An unexpected error occured.");
                    }
                });
            });
        }

        if ($("#dt_terminated_users").length) {
            var dt_interns = $("#dt_terminated_users").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: {
                    url: burl + "/dt-terminated-users"
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "monthly_rank_desc" },
                    { data: "email" },
                    { data: "username" },
                    { data: "sponsorid" },
                    { data: "current_product_id" },
                    { data: "account_status" },
                    { data: "mobilenumber" },
                    { data: "country_code" },
                    { data: "created_dt" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" target="_blank" href="` +
                                burl +
                                `/ambassador/` +
                                full.distid +
                                `"><i class="la la-edit"></i> Edit Details</a>
                                <a class="dropdown-item" target="_blank" href="` +
                                burl +
                                `/enrollements/` +
                                full.distid +
                                `"><i class="la la-edit"></i> Enrollments</a>
                                <a class="dropdown-item login-as-ambassador" data-distid="` +
                                full.distid +
                                `" href="#"><i class="la la-leaf"></i> Login as Distributor</a>
                                <a href="#" class="dropdown-item showDlgHistory" tag2="update-history" tag="` +
                                burl +
                                `/dlg-update-history/USER/` +
                                full.id +
                                `"><i class="la la-book"></i> Update History</a>
                            </div>
                        </span>`
                            );
                        }
                    },
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            if (full.entered_by == 0) return data;
                            else
                                return (
                                    '<i class="fa fa-info-circle" title="Added by an admin" style="color:#36a3f7;"></i> ' +
                                    data
                                );
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            return data;
                        }
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            var en_pack = {
                                "2": {	
                                    title: "Basic Pack",	
                                    icon: "EOR_pack_icon_basic.png"	
                                },	
                                "3": {	
                                    title: "Visionary Pack",	
                                    icon: "EOR_pack_icon_visionary.png"	
                                }
                            };
                            if (typeof en_pack[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<a href="#" data-toggle="tooltip" title="' +	
                                en_pack[data].title +	
                                '"><img src="' +	
                                burl +	
                                "/assets/images/" +	
                                en_pack[data].icon +	
                                '" /></a>'	
                            );
                        }
                    },
                    {
                        targets: 8,
                        render: function(data, type, full, meta) {
                            var status = {
                                PENDING: {
                                    title: "ON-HOLD",
                                    class: "m-badge--primary"
                                },
                                APPROVED: {
                                    title: "ACTIVE",
                                    class: " m-badge--success"
                                },
                                SUSPENDED: {
                                    title: "SUSPENDED",
                                    class: " m-badge--danger"
                                },
                                TERMINATED: {
                                    title: "TERMINATED",
                                    class: " m-badge--danger"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });

            $(document).on("click", ".login-as-ambassador", function() {
                console.log("Getting distid -> " + jQuery(this).data("distid"));
                $.ajax({
                    type: "GET",
                    url:
                        "/auth-token-ambassador/" + jQuery(this).data("distid"),
                    success: function(data) {
                        if (data.error == 1) {
                            errMsg(data.msg);

                            return;
                        }

                        window.open(data.ambassador_url);
                        // okMsg(data.msg);
                    },
                    error: function(data) {
                        console.log("An unexpected error occured.");
                        errMsg("An unexpected error occured.");
                    }
                });
            });
        }

        if ($("#dt_enrollements").length) {
            var distid = $("#distid").text();
            $("#dt_enrollements").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-enrollements/" + distid,
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "email" },
                    { data: "username" },
                    { data: "sponsorid" },
                    { data: "current_product_id" },
                    { data: "account_status" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            if (full.basic_info_updated > 0)
                                return (
                                    `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/ambassador/` +
                                    full.distid +
                                    `"><i class="la la-edit"></i> Edit Details</a>
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/enrollements/` +
                                    full.distid +
                                    `"><i class="la la-edit"></i> Enrollments</a>
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/login-as-user/` +
                                    full.distid +
                                    `"><i class="la la-leaf"></i> Login as ambassador</a>
                            </div>
                        </span>`
                                );
                            else
                                return (
                                    `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/ambassador/` +
                                    full.distid +
                                    `"><i class="la la-edit"></i> Edit Details</a>
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/enrollements/` +
                                    full.distid +
                                    `"><i class="la la-edit"></i> Enrollments</a>
                            </div>
                        </span>`
                                );
                        }
                    },
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            if (full.entered_by == 0) return data;
                            else
                                return (
                                    '<i class="fa fa-info-circle" title="Added by an admin" style="color:#36a3f7;"></i> ' +
                                    data
                                );
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            if (full.email_verified == 0) return data;
                            else
                                return (
                                    '<i class="fa fa-check" title="Email Verified" style="color:green;"></i> ' +
                                    data
                                );
                        }
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            var status = {
                                PENDING: {
                                    title: "ON-HOLD",
                                    class: "m-badge--primary"
                                },
                                APPROVED: {
                                    title: "ACTIVE",
                                    class: " m-badge--success"
                                },
                                SUSPENDED: {
                                    title: "SUSPENDED",
                                    class: " m-badge--danger"
                                },
                                TERMINATED: {
                                    title: "TERMINATED",
                                    class: " m-badge--danger"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 6,
                        render: function(data, type, full, meta) {
                            var en_pack = {
                                "2": {	
                                    title: "Basic Pack",	
                                    icon: "EOR_pack_icon_basic.png"	
                                },	
                                "3": {	
                                    title: "Visionary Pack",	
                                    icon: "EOR_pack_icon_visionary.png"	
                                }
                            };
                            if (typeof en_pack[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<a href="#" data-toggle="tooltip" title="' +	
                                en_pack[data].title +	
                                '"><img src="' +	
                                burl +	
                                "/assets/images/" +	
                                en_pack[data].icon +	
                                '" /></a>'	
                            );
                        }
                    },
                    {
                        targets: 8,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }

        if ($("#dt_customers").length) {
            $("#dt_customers").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-customers",
                columns: [
                    { data: "distid" },
                    { data: "custid" },
                    { data: "name" },
                    { data: "email" },
                    { data: "mobile" },
                    { data: "created_date" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                burl +
                                `/customer/edit-customer/` +
                                full.id +
                                `"><i class="la la-edit"></i> Edit Details</a>
                                <a href="#" class="dropdown-item showDlgHistory" tag2="update-history" tag="` +
                                burl +
                                `/dlg-update-history/CUSTOMER/` +
                                full.id +
                                `"><i class="la la-book"></i> Update History</a>
                            </div>
                        </span>`
                            );
                        }
                    }
                ]
            });

            $("#exp_customers").on("click", function(e) {
                var f = $("#dt_customers")
                    .dataTable()
                    .fnSettings();
                var q = $("#dt_customers_filter input").val();
                var i = f.aaSorting[0][0];
                var o = f.aaSorting[0][1];
                var u =
                    burl +
                    "/exp-customers/" +
                    f.aoColumns[i].data +
                    "/" +
                    o +
                    "/" +
                    q;
                window.location.replace(u);
            });
        }

        if ($("#dt_admin_leads").length) {
            $("#dt_admin_leads").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-lead",
                columns: [
                    { data: "name" },
                    { data: "email" },
                    { data: "phone" },
                    { data: "contact_date" },
                    { data: "status" },
                    { data: "intern_detail" }
                ],
                columnDefs: [
                    {
                        targets: 4,
                        render: function(data, type, full, meta) {
                            var status = {
                                "Not Interested": {
                                    title: "Not Interested",
                                    class: "m-badge--primary"
                                },
                                Interested: {
                                    title: "Interested",
                                    class: " m-badge--success"
                                },
                                "Not Contacted": {
                                    title: "Not Contacted",
                                    class: " m-badge--danger"
                                },
                                Contacted: {
                                    title: "Contacted",
                                    class: " m-badge--warning"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });

            $("#exp_admin_leads").on("click", function(e) {
                var f = $("#dt_admin_leads")
                    .dataTable()
                    .fnSettings();
                var q = $("#dt_admin_leads_filter input").val();
                var i = f.aaSorting[0][0];
                var o = f.aaSorting[0][1];
                var u =
                    burl +
                    "/exp-lead/" +
                    f.aoColumns[i].data +
                    "/" +
                    o +
                    "/" +
                    q;
                window.location.replace(u);
            });
        }

        if ($("#dt_admins").length) {
            $("#dt_admins").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-admin",
                columns: [
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "email" },
                    { data: "mobilenumber" },
                    { data: "secondary_auth_enabled" },
                    { data: "admin_role" },
                    { data: "Action" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                            <a class="btn btn-info btn-sm m-btn--air" href="` +
                                burl +
                                `/admin-user-edit/` +
                                full.id +
                                `">Edit</a>
                            <a class="btn btn-danger btn-sm m-btn--air btnToggle2FA" tag="` +
                                full.id +
                                `">Toggle 2FA</a>
                            `
                            );
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            if (full["mobilenumber"] != null)
                                return (
                                    "(" +
                                    full["phone_country_code"] +
                                    ") " +
                                    full["mobilenumber"]
                                );
                            else return "";
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--danger" },
                                0: { title: "No", class: " m-badge--success" },
                                null: {
                                    title: "No",
                                    class: " m-badge--success"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 5,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: {
                                    title: "Super Admin",
                                    class: "m-badge--primary"
                                },
                                2: {
                                    title: "Super Exec",
                                    class: " m-badge--success"
                                },
                                3: {
                                    title: "Sales",
                                    class: " m-badge--danger"
                                },
                                4: {
                                    title: "CS Manager",
                                    class: " m-badge--warning"
                                },
                                5: { title: "CS", class: " m-badge--brand" },
                                6: {
                                    title: "CS Exec",
                                    class: " m-badge--warning"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }

        $("#btnUpdateAdminUser").click(function() {
            ajPost(
                $("#frmEditAdminUser")
                    .find(":input")
                    .serialize(),
                "/update-admin-user"
            );
        });

        $("#btnUpdateAdminUserLogin").click(function() {
            ajPost(
                $("#frmEditAdminUserLogin")
                    .find(":input")
                    .serialize(),
                "/update-admin-user-login"
            );
        });

        $("#btnUpdateCSUser").click(function() {
            ajPost(
                $("#frmEditAdminUser")
                    .find(":input")
                    .serialize(),
                "/update-cs-user"
            );
        });

        $("#btnUpdateCSUserLogin").click(function() {
            ajPost(
                $("#frmEditAdminUserLogin")
                    .find(":input")
                    .serialize(),
                "/update-cs-user-login"
            );
        });

        $("#dt_admins").on("click", ".btnToggle2FA", function() {
            var id = $(this).attr("tag");
            ajPost("user_id=" + id, "/authy-toggle");
        });

        if ($("#select2_sponsor").length) {
            $("#select2_sponsor").select2({
                placeholder: "Search for distributor",
                allowClear: true,
                ajax: {
                    url: burl + "/all-ambassador",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page,
                            status: "ALL"
                        };
                    },
                    processResults: function(d, params) {
                        params.page = params.page || 1;

                        return {
                            results: d.data,
                            pagination: {
                                more: params.page * 10 < d.total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        }

        if ($("#select3_sponsor").length) {
            $("#select3_sponsor").select2({
                placeholder: "Search for distributor",
                allowClear: true,
                ajax: {
                    url: burl + "/all-ambassador",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page,
                            status: "SUSPENDED"
                        };
                    },
                    processResults: function(d, params) {
                        params.page = params.page || 1;

                        return {
                            results: d.data,
                            pagination: {
                                more: params.page * 10 < d.total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        }

        if ($("#select4_sponsor").length) {
            $("#select4_sponsor").select2({
                placeholder: "Search for distributor",
                allowClear: true,
                ajax: {
                    url: burl + "/all-ambassador",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page,
                            status: "ALL"
                        };
                    },
                    processResults: function(d, params) {
                        params.page = params.page || 1;

                        return {
                            results: d.data,
                            pagination: {
                                more: params.page * 10 < d.total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        }

        $("#btnToggleIDecideStatus").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmIdecideAccountStatus")
                    .find(":input")
                    .serialize(),
                "/idecide-toggle-status",
                "idecide-toggle-status"
            );
        });

        $("#btnToggleSORStatus").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmSORAccountStatus")
                    .find(":input")
                    .serialize(),
                "/sor-toggle-status",
                "sor-toggle-status"
            );
        });

        $("#btnActiveOverride").click(function() {
            ajPost(
                $("#frmActiveOverride")
                    .find(":input")
                    .serialize(),
                "/active-override"
            );
        });

        $("#btnFindVoucher").click(function() {
            ajPost(
                $("#frmFindVoucher")
                    .find(":input")
                    .serialize(),
                "/refund-voucher",
                "refund-voucher"
            );
        });

        $(".m-content").on("click", "#btnRefundVoucher", function() {
            $.get(burl + "/refund-order/" + $("#orderId").val(), function(
                data
            ) {
                if (data.error == 0) {
                    okMsg(data.msg);
                } else {
                    errMsg(data.msg);
                }
            });
        });

        $("#frmActiveOverrideUpload").submit(function(event) {
            $("#btnActiveOverrideUpload").prop("disabled", true);
            $("#btnActiveOverrideUpload").text("Please wait...");
            event.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "/active-override-csv",
                type: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.error == 0) {
                        okMsg(data.msg);
                    } else {
                        errMsg(data.msg);
                    }
                    $("#btnActiveOverrideUpload").prop("disabled", false);
                    $("#btnActiveOverrideUpload").text("Submit");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#btnActiveOverrideUpload").prop("disabled", false);
                    $("#btnActiveOverrideUpload").text("Submit");
                }
            });

            $("#tsa_override_csv").val("");
        });
    };

    var h_training = function() {};

    var h_lead = function() {
        $("#exp_admin_leads_ind").on("click", function(e) {
            var f = $("#dt_admin_leads_ind")
                .dataTable()
                .fnSettings();
            var q = $("#dt_admin_leads_ind_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/exp-admin-leads-ind/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q;
            window.location.replace(u);
        });

        $("#exp_admin_leads_grp").on("click", function(e) {
            var f = $("#dt_admin_leads_grp")
                .dataTable()
                .fnSettings();
            var q = $("#dt_admin_leads_grp_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/exp-admin-leads-grp/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q;
            window.location.replace(u);
        });

        if ($("#dt_admin_leads_grp").length) {
            $("#dt_admin_leads_grp").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-leads-grp",
                columns: [
                    { data: "distid" },
                    { data: "group_campaign" },
                    { data: "group_no_of_uses" },
                    { data: "group_available" },
                    { data: "boomerang_code" },
                    { data: "date_created" },
                    { data: "exp_dt" }
                ]
            });
        }

        if ($("#dt_admin_leads_ind").length) {
            $("#dt_admin_leads_ind").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-leads-ind",
                columns: [
                    { data: "distid", name: "distid" },
                    { data: "lead_firstname" },
                    { data: "lead_lastname" },
                    { data: "lead_email" },
                    { data: "lead_mobile" },
                    { data: "boomerang_code" },
                    { data: "date_created" },
                    { data: "exp_dt" },
                    { data: "is_used" },
                    { data: "seen" },
                    { data: "user_type" },
                    { data: "customer_id" }
                ],
                columnDefs: [
                    {
                        targets: 8,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--danger" },
                                0: { title: "No", class: " m-badge--success" }
                            };

                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 9,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--danger" },
                                false: {
                                    title: "No",
                                    class: " m-badge--success"
                                }
                            };

                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }

        if ($("#dt_intern_leads").length) {
            $("#dt_intern_leads").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-ambassador-lead",
                columns: [
                    { data: "name" },
                    { data: "email" },
                    { data: "phone" },
                    { data: "contact_date" },
                    { data: "status" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a style="cursor:pointer" class="dropdown-item showDlg_s" tag="` +
                                burl +
                                `/edit-lead/` +
                                full.id +
                                `"><i class="la la-edit"></i> Edit Details</a>
                            </div>
                        </span>`
                            );
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, full, meta) {
                            var status = {
                                "Not Interested": {
                                    title: "Not Interested",
                                    class: "m-badge--primary"
                                },
                                Interested: {
                                    title: "Interested",
                                    class: " m-badge--success"
                                },
                                "Not Contacted": {
                                    title: "Not Contacted",
                                    class: " m-badge--danger"
                                },
                                Contacted: {
                                    title: "Contacted",
                                    class: " m-badge--warning"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }
    };

    var h_report = function() {
        var srtBy;
        if ($("#dt_pre_enrollments_selections").length) {
            $("#dt_pre_enrollments_selections").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[1, "desc"]],
                ajax: burl + "/report/dt-pre-enrollment-selection",
                columns: [
                    { data: "distid" },
                    { data: "productname" },
                    { data: "idecide_user" },
                    { data: "saveon_user" },
                    { data: "is_processed" },
                    { data: "is_process_success" },
                    { data: "process_msg" }
                ],
                columnDefs: [
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 5,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }

        if ($("#dt_dist_by_country").length) {
            $("#dt_dist_by_country").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[1, "desc"]],
                ajax: burl + "/report/dt-dist-by-country",
                columns: [{ data: "country" }, { data: "users_count" }],
                columnDefs: []
            });
        }
        $("#exp_dist_by_country").on("click", function(e) {
            e.preventDefault();
            var f = $("#dt_dist_by_country")
                .dataTable()
                .fnSettings();
            var q = $("#dt_dist_by_country_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-dist-by-country/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q;
            window.location.replace(u);
        });
        if ($("#dt_distributor_by_rank").length) {
            $("#dt_distributor_by_rank").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/report/dt-distributor-by-rank",
                columns: [
                    { data: "achieved_rank" },
                    { data: "total" },
                    { data: "Action" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <button class="btn btn-info btn-sm m-btn--air btnDistByRankDetail" tag="` +
                                burl +
                                `/report/dlg-dist-by-rank/` +
                                encodeURI(full.achieved_rank) +
                                `" tag2="dist-by-rank">Detail</a>
                        </span>`
                            );
                        }
                    }
                ]
            });
        }
        $("#exp_distributor_by_rank").on("click", function(e) {
            e.preventDefault();
            var f = $("#dt_distributor_by_rank")
                .dataTable()
                .fnSettings();
            var q = $("#dt_distributor_by_rank_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-distributor-by-rank/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q;
            window.location.replace(u);
        });

        if ($("#dt_distributors_by_level").length) {
            var tree_list_distributors = $(
                "#dt_distributors_by_level"
            ).DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[3, "asc"]],
                ajax: {
                    url: burl + "/reports/dt-distributors-by-level",
                    data: function(d) {
                        return $.extend({}, d, {
                            sortByLevel: $("#sortByLevel").val()
                        });
                    }
                },
                columns: [
                    { data: "level" },
                    { data: "distributors" },
                    { data: "Action" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                burl +
                                `/ambassador/` +
                                full.distid +
                                `"><i class="la la-edit"></i> Details</a>
                            </div>
                        </span>`
                            );
                        }
                    }
                ]
            });

            if ($("#sortByLevel").length) {
                $("#sortByLevel").change(function() {
                    tree_list_distributors.draw();
                });
            }
        }
        if ($("#dt_vip_distributors").length) {
            $("#dt_vip_distributors").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-vip-distributors",
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "email" },
                    { data: "username" },
                    { data: "sponsorid" },
                    { data: "basic_info_updated" },
                    { data: "account_status" },
                    { data: "phonenumber" },
                    { data: "countrycode" },
                    { data: "stateprov" },
                    { data: "created_dt" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            if (full.basic_info_updated > 0)
                                return (
                                    `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/ambassador/` +
                                    full.distid +
                                    `"><i class="la la-edit"></i> Edit Details</a>
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/enrollements/` +
                                    full.distid +
                                    `"><i class="la la-edit"></i> Enrollments</a>
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/login-as-user/` +
                                    full.distid +
                                    `"><i class="la la-leaf"></i> Login as ambassador</a>
                            </div>
                        </span>`
                                );
                            else
                                return (
                                    `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/ambassador/` +
                                    full.distid +
                                    `"><i class="la la-edit"></i> Edit Details</a>
                                <a class="dropdown-item" href="` +
                                    burl +
                                    `/enrollements/` +
                                    full.distid +
                                    `"><i class="la la-edit"></i> Enrollments</a>
                            </div>
                        </span>`
                                );
                        }
                    },
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            if (full.entered_by == 0) return data;
                            else
                                return (
                                    '<i class="fa fa-info-circle" title="Added by an admin" style="color:#36a3f7;"></i> ' +
                                    data
                                );
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            if (full.email_verified == 0) return data;
                            else
                                return (
                                    '<i class="fa fa-check" title="Email Verified" style="color:green;"></i> ' +
                                    data
                                );
                        }
                    },
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            var status = {
                                PENDING: {
                                    title: "ON-HOLD",
                                    class: "m-badge--primary"
                                },
                                APPROVED: {
                                    title: "ACTIVE",
                                    class: " m-badge--success"
                                },
                                SUSPENDED: {
                                    title: "SUSPENDED",
                                    class: " m-badge--danger"
                                },
                                TERMINATED: {
                                    title: "TERMINATED",
                                    class: " m-badge--danger"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 6,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }
        if ($("#dt_personal_enrollments").length) {
            var table = $("#dt_personal_enrollments").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[3, "desc"]],
                ajax: burl + "/dt-personal-enrollments",
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "sponsees" },
                    { data: "current_product_id" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                burl +
                                `/report/enrollments/` +
                                full.distid +
                                `"><i class="la la-edit"></i> Details</a>
                            </div>
                        </span>`
                            );
                        }
                    },
                    {	
                        targets: 4,	
                        render: function(data, type, full, meta) {	
                            var en_pack = {
                                "2": {	
                                    title: "Basic Pack",	
                                    icon: "EOR_pack_icon_basic.png"	
                                },	
                                "3": {	
                                    title: "Visionary Pack",	
                                    icon: "EOR_pack_icon_visionary.png"	
                                }
                            };
                            if (typeof en_pack[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<a href="#" data-toggle="tooltip" title="' +	
                                en_pack[data].title +	
                                '"><img src="' +	
                                burl +	
                                "/assets/images/" +	
                                en_pack[data].icon +	
                                '" /></a>'	
                            );	
                        }	
                    }

                ]
            });
            table.on("draw", function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        if ($("#dt_sales_report").length) {
            $("#dt_sales_report").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-sales-report",
                columns: [
                    { data: "customer_name" },
                    { data: "email" },
                    { data: "product_info" },
                    { data: "product_price" },
                    { data: "total" }
                ]
            });
        }

        if ($("#dt_enrolled_interns").length) {
            $("#dt_enrolled_interns").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-enrolled-ambassador",
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "email" },
                    { data: "username" },
                    { data: "current_product_id" }
                ],
                columnDefs: [
                    {
                        targets: 5,
                        render: function(data, type, full, meta) {
                            var en_pack = {
                                "2": {	
                                    title: "Basic Pack",	
                                    icon: "EOR_pack_icon_basic.png"	
                                },	
                                "3": {	
                                    title: "Visionary Pack",	
                                    icon: "EOR_pack_icon_visionary.png"	
                                }
                            };
                            if (typeof en_pack[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<a href="#" data-toggle="tooltip" title="' +	
                                en_pack[data].title +	
                                '"><img src="' +	
                                burl +	
                                "/assets/images/" +	
                                en_pack[data].icon +	
                                '" /></a>'	
                            );
                        }
                    }
                ]
            });
        }
        if ($("#dt_binary_tree_report").length) {
            $("#viewOption").on("change", function() {
                if (
                    $(this)
                        .children("option:selected")
                        .val() == "full"
                ) {
                    $("#levelFrom").val("");
                    $("#levelTo").val("");
                }
            });

            $("#viewByLevel").on("click", function() {
                table.search("").draw();
            });

            $("#dt_binary_tree_report thead tr.m--hide").removeClass("m--hide");

            var table = $("#dt_binary_tree_report").DataTable({
                serverSide: true,
                processing: true,
                scrollX: true,
                dom: "<prfl<t>ip>",
                searchDelay: 500,
                order: [[1, "asc"]],
                ajax: {
                    url: burl + "/dt-entire-organization-report",
                    data: function(data) {
                        data.levelFrom = $("#levelFrom").val();
                        data.levelTo = $("#levelTo").val();
                        data.viewOption = $("#viewOption").val();

                        return data;
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "level" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "username" },
                    { data: "enrollment_date" },
                    { data: "countrycode" },
                    { data: "stateprov" },
                    { data: "current_product_id" },
                    { data: "sponsorid" },
                    { data: "sponser_name" },
                    { data: "lifetime_rank", name: "rd_lifetime.rankdesc" },
                    { data: "previous_month_rank" },
                    { data: "is_active" },
                    { data: "binary_q_l" }
                ],
                columnDefs: [
                    {
                        targets: 8,
                        render: function(data, type, full, meta) {
                            var en_pack = {
                                "2": {	
                                    title: "Basic Pack",	
                                    icon: "EOR_pack_icon_basic.png"	
                                },	
                                "3": {	
                                    title: "Visionary Pack",	
                                    icon: "EOR_pack_icon_visionary.png"	
                                }
                            };
                            if (typeof en_pack[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<a href="#" data-toggle="tooltip" title="' +
                                en_pack[data].title +
                                '"><img src="' +
                                burl +
                                "/assets/images/" +
                                en_pack[data].icon +
                                '" /></a>'
                            );
                        }
                    },
                    {
                        targets: 13,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" },
                                null: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 14,
                        render: function(data, type, full, meta) {
                            var r = `<div class='eop_cell_container'>`;
                            if (full.binary_q_l > 1) {
                                r +=
                                    `<div class='eop_img_container'>
                                    <span>L</span>
                                    <img src="` +
                                    burl +
                                    `/assets/images/binary_active.png" class="icon_active y_n_icon"/>
                                    </div>`;
                            } else {
                                r +=
                                    `<div class='eop_img_container'>
                                    <span>L</span>
                                      <img src="` +
                                    burl +
                                    `/assets/images/binary_inactive.png" class="icon_inactive y_n_icon"/>
                                      </div>`;
                            }
                            if (full.binary_q_r > 1) {
                                r +=
                                    `<div class='eop_img_container'>
                                    <span>R</span>
                                    <img src="` +
                                    burl +
                                    `/assets/images/binary_active.png" class="icon_active y_n_icon"/>
                                    </div>`;
                            } else {
                                r +=
                                    `<div class='eop_img_container'>
                                    <span>R</span>
                                      <img src="` +
                                    burl +
                                    `/assets/images/binary_inactive.png" class="icon_inactive y_n_icon"/>
                                      </div>`;
                            }
                            r += `</div>`;
                            return r;
                        }
                    },
                    {
                        targets: 0,
                        searchable: true
                    }
                ],
                fnDrawCallback: function() {
                    if (
                        !$("#labelSortDiv #dt_binary_tree_report_length").length
                    ) {
                        $("#dt_binary_tree_report_length label").addClass(
                            "col-form-label"
                        );
                        let el = $("#dt_binary_tree_report_length").detach();
                        $("#tableLengthDiv").append(el);
                        $("#dt_binary_tree_report_filter").addClass(
                            "col-lg-12"
                        );
                        $("#dt_binary_tree_report_filter").css("width", "100%");
                        $("#dt_binary_tree_report_filter label").addClass(
                            "col-form-label col-lg-6 offset-lg-6"
                        );
                        $("#dt_binary_tree_report_filter input").addClass(
                            "form-control form-control-sm col-lg-6 m--pull-right"
                        );

                        let el3 = $("#dt_binary_tree_report_filter").detach();
                        $("#divForSearch").append(el3);
                        $(
                            "#dt_binary_tree_report_filter, #dt_binary_tree_report_length"
                        ).css("display", "block");
                        $("#dt_binary_tree_report_length select").addClass(
                            "col-lg-4"
                        );
                        $("#controlsFormGroup").css("margin-bottom", "-20px");
                    }

                    $(".top-scroll").width($("#dt_binary_tree_report").width());

                    $(".top-scroll-wrapper").scroll(function() {
                        $(".dataTables_scrollBody").scrollLeft(
                            $(".top-scroll-wrapper").scrollLeft()
                        );
                    });

                    $(".dataTables_scrollBody").scroll(function() {
                        $(".top-scroll-wrapper").scrollLeft(
                            $(".dataTables_scrollBody").scrollLeft()
                        );
                    });

                    var api = this.api();

                    let jsonData = api.data().context[0].json;
                    let levelContent = "";

                    if (
                        $("#viewOption")
                            .children("option:selected")
                            .val() == "selected"
                    ) {
                        levelContent =
                            " - levels " +
                            $("#levelFrom")
                                .children("option:selected")
                                .val() +
                            " to " +
                            $("#levelTo")
                                .children("option:selected")
                                .val();
                    }
                    $("#recordsTotal div p").html(
                        "TOTAL AMBASSADORS" + levelContent
                    );
                    $("#countActiveUsers div p").html(
                        "ACTIVE AMBASSADORS" + levelContent
                    );
                    $("#recordsTotal div span").html(jsonData.recordsTotal);
                    $("#countActiveUsers div span").html(
                        jsonData.countActiveUsers
                    );
                    $("#ambassadorCounts").css("display", "block");
                }
            });

            $(".dataTables_scroll").prepend(`
                        <div class="top-scroll-wrapper">
                        <div class="top-scroll"></div>
                        </div>
                    `);
            table.on("draw", function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        if ($("#dt_weekly_binary_view").length) {
            var from = $("#d_from").val();
            var to = $("#d_to").val();
            var table = $("#dt_weekly_binary_view").DataTable({
                serverSide: true,
                processing: true,
                scrollX: true,
                searchDelay: 500,
                order: [[1, "asc"]],
                ajax: {
                    url: burl + "/dt-weekly-binary-view",
                    data: function(d) {
                        d.from = from;
                        d.to = to;
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "username" },
                    { data: "qv" },
                    { data: "created_dt" },
                    { data: "direction" }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        searchable: true
                    }
                ],
                fnDrawCallback: function() {
                    $(".top-scroll").width($("#dt_weekly_binary_view").width());
                    $(".top-scroll-wrapper").scroll(function() {
                        $(".dataTables_scrollBody").scrollLeft(
                            $(".top-scroll-wrapper").scrollLeft()
                        );
                    });
                    $(".dataTables_scrollBody").scroll(function() {
                        $(".top-scroll-wrapper").scrollLeft(
                            $(".dataTables_scrollBody").scrollLeft()
                        );
                    });
                }
            });

            $(".dataTables_scroll").prepend(`
                        <div class="top-scroll-wrapper">
                        <div class="top-scroll"></div>
                        </div>
                    `);
            table.on("draw", function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        }
        if ($("#dt_weekly_enrollment").length) {
            $("body").css("background-image", "");
            var table = $("#dt_weekly_enrollment").DataTable({
                serverSide: true,
                processing: true,
                scrollX: true,
                dom: "<pfl<t>ip>",
                searchDelay: 500,
                order: [[1, "asc"]],
                ajax: burl + "/dt-weekly-enrollment-report",
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "username" },
                    { data: "countrycode" },
                    { data: "stateprov" },
                    { data: "current_product_id" },
                    { data: "sponsorid" },
                    { data: "sponser_name" },
                    { data: "created_dt" }
                ],

                columnDefs: [
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            var en_pack = {
                                "2": {	
                                    title: "Basic Pack",	
                                    icon: "EOR_pack_icon_basic.png"	
                                },	
                                "3": {	
                                    title: "Visionary Pack",	
                                    icon: "EOR_pack_icon_visionary.png"	
                                }
                            };
                            if (typeof en_pack[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<a href="#" data-toggle="tooltip" title="' +
                                en_pack[data].title +
                                '"><img src="' +
                                burl +
                                "/assets/images/" +
                                en_pack[data].icon +
                                '" /></a>'
                            );
                        }
                    },
                    {
                        targets: 0,
                        searchable: true
                    }
                ],
                fnDrawCallback: function() {
                    $(".top-scroll").width($("#dt_weekly_enrollment").width());
                    $(".top-scroll-wrapper").scroll(function() {
                        $(".dataTables_scrollBody").scrollLeft(
                            $(".top-scroll-wrapper").scrollLeft()
                        );
                    });
                    $(".dataTables_scrollBody").scroll(function() {
                        $(".top-scroll-wrapper").scrollLeft(
                            $(".dataTables_scrollBody").scrollLeft()
                        );
                    });
                }
            });

            $(".dataTables_scroll").prepend(`
                        <div class="top-scroll-wrapper">
                        <div class="top-scroll"></div>
                        </div>
                    `);
            table.on("draw", function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        if ($("#subscription-details-table").length) {
            var table = $("#subscription-details-table").DataTable({
                serverSide: true,
                processing: true,
                scrollX: true,
                dom: "<pfl<t>ip>",
                searchDelay: 500,
                order: [[1, "asc"]],
                ajax: burl + "/subscription-details-table",
                columnDefs: [
                    {
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return full.firstname + " " + full.lastname;
                        }
                    },
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return full.distid;
                        }
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return full.username;
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            return full.created_dt;
                        }
                    }
                ],
                fnDrawCallback: function() {
                    $(".top-scroll").width(
                        $("#subscription-details-table").width()
                    );
                    $(".top-scroll-wrapper").scroll(function() {
                        $(".dataTables_scrollBody").scrollLeft(
                            $(".top-scroll-wrapper").scrollLeft()
                        );
                    });
                    $(".dataTables_scrollBody").scroll(function() {
                        $(".top-scroll-wrapper").scrollLeft(
                            $(".dataTables_scrollBody").scrollLeft()
                        );
                    });
                },
                fnServerParams: function(data) {
                    data.userId = $("#sponsor_id").val();
                    data.subscriptionType = $("#subscription_type").val();
                }
            });

            $(".dataTables_scroll").prepend(`
                        <div class="top-scroll-wrapper">
                        <div class="top-scroll"></div>
                        </div>
                    `);
            table.on("draw", function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        if ($("#subscription-details-traverus-table").length) {
            var table = $("#subscription-details-traverus-table").DataTable({
                serverSide: true,
                processing: true,
                scrollX: true,
                dom: "<pfl<t>ip>",
                searchDelay: 500,
                order: [[1, "asc"]],
                ajax: burl + "/subscription-details-table",
                columnDefs: [
                    {
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return full.firstname + " " + full.lastname;
                        }
                    },
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return full.distid;
                        }
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            return full.username;
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            if (full.current_product_id === 14) {
                                return full.created_dt;
                            }
                            return null;
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, full, meta) {
                            if (full.current_product_id === 12) {
                                return full.created_dt;
                            }
                            return null;
                        }
                    }
                ],
                fnDrawCallback: function() {
                    $(".top-scroll").width(
                        $("#subscription-details-table").width()
                    );
                    $(".top-scroll-wrapper").scroll(function() {
                        $(".dataTables_scrollBody").scrollLeft(
                            $(".top-scroll-wrapper").scrollLeft()
                        );
                    });
                    $(".dataTables_scrollBody").scroll(function() {
                        $(".top-scroll-wrapper").scrollLeft(
                            $(".dataTables_scrollBody").scrollLeft()
                        );
                    });
                },
                fnServerParams: function(data) {
                    data.userId = $("#sponsor_id").val();
                    data.subscriptionType = $("#subscription_type").val();
                }
            });

            $(".dataTables_scroll").prepend(`
                        <div class="top-scroll-wrapper">
                        <div class="top-scroll"></div>
                        </div>
                    `);
            table.on("draw", function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        if ($("#dt_highest_achieved_rank").length) {
            $("#dt_highest_achieved_rank").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: {
                    url: burl + "/report/dt-highest-achieved-rank",
                    data: function(d) {
                        d.from = $("#d_from").val();
                        d.to = $("#d_to").val();
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "achieved_rank" },
                    { data: "created_dt" }
                ],
                columnDefs: []
            });
        }
        if ($("#dt_subscription_history").length) {
            $("#dt_subscription_history").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [["3", "desc"]],
                ajax: {
                    url: burl + "/report/dt-subscription-history",
                    data: function(d) {}
                },
                columns: [
                    { data: "distid", name: "u.distid" },
                    { data: "status", name: "sh.status" },
                    { data: "response", name: "sh.response" },
                    { data: "attempted_date", name: "sh.attempted_date" }
                ],
                columnDefs: [
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: {
                                    title: "Success",
                                    class: "m-badge--success"
                                },
                                0: { title: "Fail", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }
        $("#exp_subscription_history").on("click", function(e) {
            e.preventDefault();
            var f = $("#dt_subscription_history")
                .dataTable()
                .fnSettings();
            var q = $("#dt_subscription_history_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-subscription-history/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q;
            window.location.replace(u);
        });
        if ($("#dt_rank_advancement_report").length) {
            var dt_rank_advancement = $(
                "#dt_rank_advancement_report"
            ).DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[6, "desc"]],
                ajax: {
                    url: burl + "/report/dt-rank-advancement-report",
                    data: function(d) {
                        d.month = $("#month").val();
                        d.year = $("#year").val();
                        d.rank = $("#rank").val();
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "email" },
                    { data: "achieved_rank" },
                    { data: "country" },
                    { data: "created_dt" }
                ],
                columnDefs: []
            });

            if ($("#rankAdvancementFilterBtn").length) {
                $("#rankAdvancementFilterBtn").click(function(e) {
                    e.preventDefault();
                    if ($("#month").val().length && $("#year").val() == "") {
                        errMsg("If month is selected, year is required");
                        return false;
                    }
                    dt_rank_advancement.draw();
                });
            }
        }
        if ($("#dt_fsb_commission").length) {
            var dt_fsb_commission = $("#dt_fsb_commission").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                // order: [[6, "desc"]],
                ajax: {
                    url: burl + "/report/dt-fsb-commission-report",
                    data: function(d) {
                        d.order_number = $("#order_number").val();
                        d.tsa = $("#tsa").val();
                        d.volume_type = $("#volume_type").val();
                        d.d_from = $("#d_from").val();
                        d.d_to = $("#d_to").val();
                    }
                },
                columns: [
                    { data: "percentage" },
                    { data: "created_dt" },
                    { data: "firstname" },
                    { data: "username" },
                    { data: "typedesc" },
                    { data: "cv" },
                    { data: "memo" },
                    { data: "amount" }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        data: "percentage",
                        render: function(data, type, row, meta) {
                            return "% " + data;
                        }
                    },
                    {
                        targets: 2,
                        data: "firstname",
                        render: function(data, type, row, meta) {
                            return row.firstname + " " + row.lastname;
                        }
                    },
                    {
                        targets: 7,
                        data: "price",
                        render: function(data, type, row, meta) {
                            return "$ " + data;
                        }
                    }
                ]
            });
        }
        $("#exp_rank_advancement").on("click", function(e) {
            e.preventDefault();
            var f = $("#dt_rank_advancement_report")
                .dataTable()
                .fnSettings();
            var q = $("#dt_rank_advancement_report_filter input").val();
            var d = $("#frmRankAdvancementFilter")
                .find(":input")
                .serialize();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-rank-advancement/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "?" +
                d;
            window.location.replace(u);
        });
        if ($("#dt_enrolled_interns_admin").length) {
            var _distid = $("#distid").val();
            $("#dt_enrolled_interns_admin").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-personally-enrolled-detail/" + _distid,
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "email" },
                    { data: "username" },
                    { data: "current_product_id" },
                    { data: "account_status" }
                ],
                columnDefs: [
                    {
                        targets: 6,
                        render: function(data, type, full, meta) {
                            var status = {
                                PENDING: {
                                    title: "ON-HOLD",
                                    class: "m-badge--primary"
                                },
                                APPROVED: {
                                    title: "ACTIVE",
                                    class: " m-badge--success"
                                },
                                SUSPENDED: {
                                    title: "SUSPENDED",
                                    class: " m-badge--danger"
                                },
                                TERMINATED: {
                                    title: "TERMINATED",
                                    class: " m-badge--danger"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 5,
                        render: function(data, type, full, meta) {
                            var en_pack = {
                                "2": {	
                                    title: "Basic Pack",	
                                    icon: "EOR_pack_icon_basic.png"	
                                },	
                                "3": {	
                                    title: "Visionary Pack",	
                                    icon: "EOR_pack_icon_visionary.png"	
                                }
                            };
                            if (typeof en_pack[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<a href="#" data-toggle="tooltip" title="' +	
                                en_pack[data].title +	
                                '"><img src="' +	
                                burl +	
                                "/assets/images/" +	
                                en_pack[data].icon +	
                                '" /></a>'	
                            );
                        }
                    }
                ]
            });
        }

        if ($("#dt_admin_report_sales").length) {
            $("#dt_admin_report_sales").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-admin-report-sales",
                columns: [
                    { data: "customer" },
                    { data: "product_info" },
                    { data: "total" },
                    { data: "sponsor_detail" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                            <a class="btn btn-danger btn-sm m-btn--air" href="` +
                                burl +
                                `/transaction-detail/` +
                                full.id +
                                `">Detail</a>
                            `
                            );
                        }
                    }
                ]
            });
        }

        $("#exp_admin_report_sales").on("click", function(e) {
            var f = $("#dt_admin_report_sales")
                .dataTable()
                .fnSettings();
            var q = $("#dt_admin_report_sales_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/exp-admin-report-sales/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q;
            window.location.replace(u);
        });

        $("#exp_personal_enrollments").on("click", function(e) {
            var f = $("#dt_personal_enrollments")
                .dataTable()
                .fnSettings();
            var q = $("#dt_personal_enrollments_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/exp-personal-enrollments/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q;
            window.location.replace(u);
        });

        $("#exp_vip_distributors").on("click", function(e) {
            var f = $("#dt_vip_distributors")
                .dataTable()
                .fnSettings();
            var q = $("#dt_vip_distributors_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/exp-vip-distributors/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q;
            window.location.replace(u);
        });

        if ("#m_sortable_portlets".length) {
            $("#m_sortable_portlets").sortable({
                connectWith: ".m-portlet__head",
                items: ".m-portlet",
                opacity: 0.8,
                handle: ".m-portlet__head",
                coneHelperSize: true,
                placeholder: "m-portlet--sortable-placeholder",
                forcePlaceholderSize: true,
                tolerance: "pointer",
                helper: "clone",
                tolerance: "pointer",
                forcePlaceholderSize: !0,
                helper: "clone",
                cancel: ".m-portlet--sortable-empty", // cancel dragging if portlet is in fullscreen mode
                revert: 250, // animation in milliseconds
                update: function(b, c) {
                    if (c.item.prev().hasClass("m-portlet--sortable-empty")) {
                        c.item.prev().before(c.item);
                    }
                }
            });
        }

        if ($("#dt_enrollments_by_date").length) {
            var from = $("#d_from").val();
            var to = $("#d_to").val();
            var tree_list_distributors = $("#dt_enrollments_by_date").DataTable(
                {
                    serverSide: true,
                    processing: true,
                    responsive: true,
                    searchDelay: 500,
                    order: [[4, "desc"]],
                    ajax: {
                        url: burl + "/report/dt-enrollments-by-date",
                        data: function(d) {
                            d.from = from;
                            d.to = to;
                        }
                    },
                    columns: [
                        { data: "distid" },
                        { data: "firstname" },
                        { data: "lastname" },
                        { data: "username" },
                        { data: "created_dt" },
                        { data: "countrycode" },
                        { data: "sponsorid" },
                        { data: "current_product_id" },
                        { data: "account_status" },
                        { data: "email" },
                        { data: "phonenumber" },
                        { data: "stateprov" }
                    ],
                    columnDefs: [
                        {
                            targets: 7,
                            render: function(data, type, full, meta) {
                                var en_pack = {
                                    "2": {	
                                        title: "Basic Pack",	
                                        icon: "EOR_pack_icon_basic.png"	
                                    },	
                                    "3": {	
                                        title: "Visionary Pack",	
                                        icon: "EOR_pack_icon_visionary.png"	
                                    }
                                };
                                if (typeof en_pack[data] === "undefined") {
                                    return data;
                                }
                                return (
                                    '<a href="#" data-toggle="tooltip" title="' +	
                                    en_pack[data].title +	
                                    '"><img src="' +	
                                    burl +	
                                    "/assets/images/" +	
                                    en_pack[data].icon +	
                                    '" /></a>'	
                                );
                            }
                        },
                        {
                            targets: 8,
                            render: function(data, type, full, meta) {
                                var status = {
                                    PENDING: {
                                        title: "ON-HOLD",
                                        class: "m-badge--primary"
                                    },
                                    APPROVED: {
                                        title: "ACTIVE",
                                        class: " m-badge--success"
                                    },
                                    SUSPENDED: {
                                        title: "SUSPENDED",
                                        class: " m-badge--danger"
                                    },
                                    TERMINATED: {
                                        title: "TERMINATED",
                                        class: " m-badge--danger"
                                    }
                                };
                                if (typeof status[data] === "undefined") {
                                    return data;
                                }
                                return (
                                    '<span class="m-badge ' +
                                    status[data].class +
                                    ' m-badge--wide">' +
                                    status[data].title +
                                    "</span>"
                                );
                            }
                        }
                    ]
                }
            );
        }
        $("#exp_enrollments_by_date").on("click", function(e) {
            e.preventDefault();
            if (
                $("#dt_enrollments_by_date_filter select[name=type]").val() !=
                "enrollments"
            ) {
                errMsg("Select Enrollments to export report.");
                return false;
            }
            var f = $("#dt_enrollments_by_date")
                .dataTable()
                .fnSettings();
            var q = $("#dt_enrollments_by_date_filter input").val();
            var d = $("#frmEnrollmentByDateFilter")
                .find(":input")
                .serialize();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-enrollments-by-date/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "?" +
                d;
            window.location.replace(u);
        });
        if ($("#dt_sales_by_payment_method").length) {
            var from = $("#d_from").val();
            var to = $("#d_to").val();
            $("#dt_sales_by_payment_method").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: {
                    url: burl + "/report/dt-sales-by-payment-method",
                    data: function(d) {
                        d.from = from;
                        d.to = to;
                    }
                },
                columns: [{ data: "pay_method_name" }, { data: "amount" }],
                columnDefs: [
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            return numberWithCommas(data);
                        }
                    }
                ]
            });
        }
        $("#exp_sales_by_paymentmethod").on("click", function(e) {
            e.preventDefault();
            var f = $("#dt_sales_by_payment_method")
                .dataTable()
                .fnSettings();
            var q = $("#dt_sales_by_payment_method_filter input").val();
            var d = $("#frmSalesByPaymentMethodFilter")
                .find(":input")
                .serialize();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-sales-by-payment-method/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "?" +
                d;
            window.location.replace(u);
        });
        if ($("#dt_subscripttion_report").length) {
            var from = $("#d_from").val();
            var to = $("#d_to").val();
            $("#dt_subscripttion_report").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: {
                    url: burl + "/report/dt-subscription-report",
                    data: function(d) {
                        d.from = from;
                        d.to = to;
                    }
                },
                columns: [
                    { data: "tran_date" },
                    { data: "total_successes" },
                    { data: "total_fails" }
                ],
                columnDefs: [
                    {
                        targets: 1,
                        searchable: false
                    },
                    {
                        targets: 2,
                        searchable: false
                    }
                ]
            });
        }
        $("#exp_subscription_report").on("click", function(e) {
            e.preventDefault();
            var f = $("#dt_subscripttion_report")
                .dataTable()
                .fnSettings();
            var q = $("#dt_subscripttion_report_filter input").val();
            var d = $("#frmSubscriptionFilter")
                .find(":input")
                .serialize();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-subscription-report/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "/?" +
                d;
            window.location.replace(u);
        });

        if ($("#dt_subscripttion_by_payment_method").length) {
            var from = $("#d_from").val();
            var to = $("#d_to").val();
            $("#dt_subscripttion_by_payment_method").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: {
                    url: burl + "/report/dt-subscription-by-payment-method",
                    data: function(d) {
                        d.from = from;
                        d.to = to;
                    }
                },
                columns: [
                    { data: "tran_date" },
                    { data: "credit_card" },
                    { data: "ewallet" },
                    { data: "admin" },
                    { data: "bitpay" },
                    { data: "skrill" },
                    { data: "secondary_cc" }
                ],
                columnDefs: [
                    {
                        targets: 1,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data === null) {
                                return (
                                    '<span class="m-badge m-badge--default m-badge--wide">' +
                                    "n/a" +
                                    "</span>"
                                );
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        targets: 2,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data === null) {
                                return (
                                    '<span class="m-badge m-badge--default m-badge--wide">' +
                                    "n/a" +
                                    "</span>"
                                );
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        targets: 3,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data === null) {
                                return (
                                    '<span class="m-badge m-badge--default m-badge--wide">' +
                                    "n/a" +
                                    "</span>"
                                );
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        targets: 4,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data === null) {
                                return (
                                    '<span class="m-badge m-badge--default m-badge--wide">' +
                                    "n/a" +
                                    "</span>"
                                );
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        targets: 5,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data === null) {
                                return (
                                    '<span class="m-badge m-badge--default m-badge--wide">' +
                                    "n/a" +
                                    "</span>"
                                );
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        targets: 6,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data === null) {
                                return (
                                    '<span class="m-badge m-badge--default m-badge--wide">' +
                                    "n/a" +
                                    "</span>"
                                );
                            } else {
                                return data;
                            }
                        }
                    }
                ]
            });
            $("#exp_subscription_by_payment_method").on("click", function(e) {
                e.preventDefault();
                var d = $("#frmSubscriptionByPaymentMethodFilter")
                    .find(":input")
                    .serialize();
                var f = $("#dt_subscripttion_by_payment_method")
                    .dataTable()
                    .fnSettings();
                var q = $(
                    "#dt_subscripttion_by_payment_method_filter input"
                ).val();
                var i = f.aaSorting[0][0];
                var o = f.aaSorting[0][1];
                var u =
                    burl +
                    "/report/exp-subscription-by-payment-method/" +
                    f.aoColumns[i].data +
                    "/" +
                    o +
                    "/" +
                    q +
                    "?" +
                    d;
                window.location.replace(u);
            });
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        if ($("#dt_sapphire").length) {
            var country_code = $("#filterByCountry").val();
            var dt_sapphire = $("#dt_sapphire").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: {
                    url: burl + "/report/dt-all-sapphires-by-country/",
                    data: function(d) {
                        d.country_code = $("#filterByCountry").val();
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "country" },
                    { data: "email" },
                    { data: "phonenumber" }
                ],
                columnDefs: []
            });

            if ($("#btnFilterSapphire").length) {
                $("#btnFilterSapphire").click(function() {
                    dt_sapphire.draw();
                });
            }
        }
        $("#exp_sapphires").on("click", function(e) {
            e.preventDefault();
            var f = $("#dt_sapphire")
                .dataTable()
                .fnSettings();
            var q = $("#dt_sapphire_filter input").val();
            var d = $("#frmSapphireFilter")
                .find(":input")
                .serialize();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-sapphire-by-country/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "?" +
                d;
            window.location.replace(u);
        });
        if ($("#dt_diamond").length) {
            var country_code = $("#filterByCountry").val();
            var dt_sapphire = $("#dt_diamond").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: {
                    url: burl + "/report/dt-all-diamonds-by-country/",
                    data: function(d) {
                        d.country_code = $("#filterByCountry").val();
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "country" },
                    { data: "email" },
                    { data: "phonenumber" }
                ],
                columnDefs: []
            });

            if ($("#btnFilterDiamond").length) {
                $("#btnFilterDiamond").click(function() {
                    dt_sapphire.draw();
                });
            }
        }
        $("#exp_diamond").on("click", function(e) {
            e.preventDefault();
            var f = $("#dt_diamond")
                .dataTable()
                .fnSettings();
            var q = $("#dt_diamond_filter input").val();
            var d = $("#frmDiamondFilter")
                .find(":input")
                .serialize();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-diamond-by-country/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "?" +
                d;
            window.location.replace(u);
        });
        if ($("#dt_monthly_income_earnings").length) {
            var dt_sapphire_monthly = $(
                "#dt_monthly_income_earnings"
            ).DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: {
                    url: burl + "/report/dt-monthly-income-earnings",
                    data: function(d) {
                        d.year = $("#year").val();
                        d.month = $("#month").val();
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "monthly_total_amount" },
                    { data: "total_amount" }
                ],
                columnDefs: [
                    {
                        targets: 3,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return "$" + data;
                        }
                    },
                    {
                        targets: 4,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return "$" + data;
                        }
                    }
                ]
            });

            if ($("#btnFilterMonthlyEarnings").length) {
                $("#btnFilterMonthlyEarnings").click(function(e) {
                    e.preventDefault();
                    dt_sapphire_monthly.draw();
                });
            }
        }
        $("#exp_monthly_income_earnings").on("click", function(e) {
            e.preventDefault();
            var d = $("#frmMonthlyIncomeFilter")
                .find(":input")
                .serialize();
            var f = $("#dt_monthly_income_earnings")
                .dataTable()
                .fnSettings();
            var q = $("#dt_monthly_income_earnings_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-monthly-income-earnings/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "?" +
                d;
            window.location.replace(u);
        });

        if ($("#dt_monthly_top_recruiters").length) {
            var dt_sapphire_monthly = $("#dt_monthly_top_recruiters").DataTable(
                {
                    serverSide: true,
                    processing: true,
                    responsive: true,
                    searchDelay: 500,
                    order: [[6, "desc"]],
                    ajax: {
                        url: burl + "/report/dt-monthly-top-recruiters",
                        data: function(d) {
                            d.year = $("#year").val();
                            d.month = $("#month").val();
                        }
                    },
                    columns: [
                        { data: "distid" },
                        { data: "firstname" },
                        { data: "lastname" },
                        { data: "country" },
                        { data: "email" },
                        { data: "achieved_rank" },
                        { data: "sponsees" }
                    ],
                    columnDefs: []
                }
            );

            if ($("#btnFilterTopRecruiters").length) {
                $("#btnFilterTopRecruiters").click(function(e) {
                    e.preventDefault();
                    dt_sapphire_monthly.draw();
                });
            }
        }
        $("#exp_monthly_top_recruiters").on("click", function(e) {
            e.preventDefault();
            var d = $("#frmMonthlyTopRecruitersFilter")
                .find(":input")
                .serialize();
            var f = $("#dt_monthly_top_recruiters")
                .dataTable()
                .fnSettings();
            var q = $("#dt_monthly_top_recruiters_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-monthly-top-recruiters/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "?" +
                d;
            window.location.replace(u);
        });

        if ($("#dt_monthly_top_customers").length) {
            var dt_sapphire_monthly = $("#dt_monthly_top_customers").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[6, "desc"]],
                ajax: {
                    url: burl + "/report/dt-monthly-top-customers",
                    data: function(d) {
                        d.year = $("#year").val();
                        d.month = $("#month").val();
                    }
                },
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "country" },
                    { data: "email" },
                    { data: "achieved_rank" },
                    { data: "activated_customers" }
                ],
                columnDefs: []
            });

            if ($("#btnFilterTopCustomers").length) {
                $("#btnFilterTopCustomers").click(function(e) {
                    e.preventDefault();
                    dt_sapphire_monthly.draw();
                });
            }
        }
        $("#exp_monthly_top_customers").on("click", function(e) {
            e.preventDefault();
            var d = $("#frmMonthlyTopCustomersFilter")
                .find(":input")
                .serialize();
            var f = $("#dt_monthly_top_customers")
                .dataTable()
                .fnSettings();
            var q = $("#dt_monthly_top_customers_filter input").val();
            var i = f.aaSorting[0][0];
            var o = f.aaSorting[0][1];
            var u =
                burl +
                "/report/exp-monthly-top-customers/" +
                f.aoColumns[i].data +
                "/" +
                o +
                "/" +
                q +
                "?" +
                d;
            window.location.replace(u);
        });

        if ($("#btnCalculateVolume").length) {
            $("#btnCalculateVolume").click(function(e) {
                e.preventDefault();
                var tsa = $("#tsa").val();
                var from = $("#frmCalculateVolume input[name=d_from]").val();
                var to = $("#frmCalculateVolume input[name=d_to]").val();
                var type = $(
                    "#frmCalculateVolume select[name=volume_type]"
                ).val();
                if (tsa == "") {
                    errMsg("TSA cannot be empty");
                    return false;
                } else if (
                    (from == "" && to != "") ||
                    (from != "" && to == "")
                ) {
                    errMsg("Date range cannot be empty");
                    return false;
                } else if (type == null) {
                    errMsg("Type of volume cannot be empty");
                    return false;
                }

                $.ajax({
                    type: "GET",
                    url:
                        burl +
                        "/report/dt-fsb-commission-report?" +
                        "valitaion=true&order_number=" +
                        $("#order_number").val() +
                        "&" +
                        "tsa=" +
                        $("#tsa").val() +
                        "&volume_type=" +
                        $("#volume_type").val() +
                        "" +
                        "&d_from=" +
                        $("#d_from").val() +
                        "&d_to=" +
                        $("#d_to").val(),
                    success: function(data) {
                        if (data.error == 1) {
                            errMsg(data.msg);
                            return false;
                        }
                        dt_fsb_commission.draw();
                    }
                });
            });
        }

        if ($("#salesPaymentMethodFilterBtn").length) {
            $("#salesPaymentMethodFilterBtn").click(function(e) {
                e.preventDefault();
                var from = $("#d_from").val();
                var to = $("#d_to").val();
                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }
                window.location.replace(
                    burl + "/report/sales-by-payment-method/" + from + "/" + to
                );
            });
        }

        if ($("#binaryViewDateRangeReportFiterBtn").length) {
            $("#binaryViewDateRangeReportFiterBtn").click(function(e) {
                e.preventDefault();
                var from = $("#d_from").val();
                var to = $("#d_to").val();
                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }
                window.location.replace(
                    burl + "/weekly-binary-report/" + from + "/" + to
                );
            });
        }
        if ($("#subscriptionReportFiterBtn").length) {
            $("#subscriptionReportFiterBtn").click(function(e) {
                e.preventDefault();
                var from = $("#d_from").val();
                var to = $("#d_to").val();
                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }
                window.location.replace(
                    burl + "/report/subscription-report/" + from + "/" + to
                );
            });
        }

        if ($("#subscriptionByPMFiterBtn").length) {
            $("#subscriptionByPMFiterBtn").click(function(e) {
                e.preventDefault();
                var from = $("#d_from").val();
                var to = $("#d_to").val();
                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }
                window.location.replace(
                    burl +
                        "/report/subscription-by-payment-method/" +
                        from +
                        "/" +
                        to
                );
            });
        }

        if ($("#highestAchievedRankFilterBtn").length) {
            $("#highestAchievedRankFilterBtn").click(function() {
                var from = $("#d_from").val();
                var to = $("#d_to").val();
                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }
                window.location.replace(
                    burl + "/report/dt-highest-achieved-rank/" + from + "/" + to
                );
            });
        }
        if ($("#idecideSorFilterBtn").length) {
            $("#idecideSorFilterBtn").click(function(e) {
                e.preventDefault();
                var from = $("#d_from").val();
                var to = $("#d_to").val();
                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }
                window.location.replace(
                    burl + "/report/idecide-and-sor/" + from + "/" + to
                );
            });
        }
        if ($("#enrollmentsByDateFilterBtn").length) {
            $("#enrollmentsByDateFilterBtn").click(function(e) {
                e.preventDefault();
                if (
                    $("#frmEnrollmentByDateFilter select[name=type]").val() ==
                    null
                ) {
                    errMsg("Please select Enrollments / Customers");
                    return false;
                }
                var from = $("#d_from").val();
                var to = $("#d_to").val();

                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }

                if (
                    $("#frmEnrollmentByDateFilter select[name=type]").val() ==
                    "customers"
                ) {
                    $.ajax({
                        type: "GET",
                        url:
                            burl +
                            "/report/customer/enrollments-by-date?from=" +
                            from +
                            "&to=" +
                            to,
                        dataType: "json",
                        success: function(data) {
                            okMsg(data.msg);
                        }
                    });
                    return false;
                }
                window.location.replace(
                    burl + "/report/erollments-by-date/" + from + "/" + to
                );
            });
        }

        if ($("#approved_commission_date").length) {
            $("#approved_commission_date").select2({
                placeholder: "Approved Date",
                allowClear: true,
                ajax: {
                    url: burl + "/select2-approved-commission-dates",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(d, params) {
                        params.page = params.page || 1;

                        return {
                            results: d.data,
                            pagination: {
                                more: params.page * 10 < d.total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });
        }
        $("#exp_idecide_and_sor").on("click", function(e) {
            e.preventDefault();
            var d = $("#frmIDecideAndSorFilter")
                .find(":input")
                .serialize();
            var u = burl + "/report/exp-idecide-and-sor/?" + d;
            window.location.replace(u);
        });
    };

    var h_promo = function() {
        $("#btnSavePromo").click(function() {
            ajPost(
                $("#frmPromo").serialize(),
                "/validate-promo",
                "validate-promo"
            );
        });
    };

    var h_media = function() {
        $("#selFrom").change(function() {
            var v = $(this).val();
            if (v == "web") {
                $("#from_local").hide();
                $("#from_web").show();
            } else {
                $("#from_local").show();
                $("#from_web").hide();
            }
        });

        $("#btnNewMedia").click(function() {
            ajPost(
                $("#frmMedia").serialize(),
                "/validate-media",
                "validate-media"
            );
        });

        $("#btnUpdateMedia").click(function() {
            ajPost(
                $("#frmMedia").serialize(),
                "/validate-media",
                "validate-media"
            );
        });

        $("#media_vid_search").keyup(function(e) {
            if (e.key == "Enter") {
                ajPost(
                    "q=" + $(this).val(),
                    "/media-vid-view",
                    "media-vid-view"
                );
            }
        });

        $("#media_img_search").keyup(function(e) {
            if (e.key == "Enter") {
                ajPost(
                    "q=" + $(this).val(),
                    "/media-img-view",
                    "media-img-view"
                );
            }
        });

        $("#media_doc_search").keyup(function(e) {
            if (e.key == "Enter") {
                ajPost(
                    "q=" + $(this).val(),
                    "/media-doc-view",
                    "media-doc-view"
                );
            }
        });

        $("#media_pres_search").keyup(function(e) {
            if (e.key == "Enter") {
                ajPost(
                    "q=" + $(this).val(),
                    "/media-pres-view",
                    "media-pres-view"
                );
            }
        });

        if ($("#dt_media").length) {
            $("#dt_media").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-media",
                columns: [
                    { data: "display_name" },
                    { data: "file_name" },
                    { data: "category" },
                    { data: "external_url" },
                    { data: "is_downloadable" },
                    { data: "is_active" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                            <a class="btn btn-danger btn-sm m-btn--air" href="` +
                                burl +
                                `/media-edit/` +
                                full.id +
                                `">Edit</a>
                            `
                            );
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 5,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }

        if ($("#media_vid").length) {
            ajPost("", "/media-vid-view", "media-vid-view");
        }

        if ($("#media_img").length) {
            ajPost("", "/media-img-view", "media-img-view");
        }

        if ($("#media_doc").length) {
            ajPost("", "/media-doc-view", "media-doc-view");
        }

        if ($("#media_pres").length) {
            ajPost("", "/media-pres-view", "media-pres-view");
        }
    };

    var h_mail_templates = function() {
        $("#btnSaveMailTemplate").click(function() {
            ajPost($("#frmMailTemplate").serialize(), "/save-mail-template");
        });

        if ($("#dt_mail_templates").length) {
            $("#dt_mail_templates").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-mail-templates",
                columns: [
                    { data: "type" },
                    { data: "subject" },
                    { data: "is_active" },
                    { data: "remarks" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                burl +
                                `/edit-mail-template/` +
                                full.id +
                                `"><i class="la la-edit"></i> Edit Details</a>
                            </div>
                        </span>`
                            );
                        }
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }
    };

    var h_dashboard = function() {
        if ($(".amCharts").length) {
            total_order_sum_chart();
            $("#amchartFilterBtn").click(function() {
                var d =
                    "year=" +
                    $("#chartYear").val() +
                    "&month=" +
                    $("#chartMonth").val();
                if ($("#chartType").val() == "sales") {
                    $("#ctSales").show();
                    total_order_sum_chart(d);
                } else if ($("#chartType").val() == "enrollments") {
                    $("#ctEnrollments").show();
                    enrollments_by_day_chart(d);
                } else if ($("#chartType").val() == "boomerangs") {
                    $("#ctBoomerangs").show();
                    boomerangs_by_day_chart(d);
                }
            });
        }

        function total_order_sum_chart(d = "") {
            ajPost(d, "/chart-total-order-sum", "get-total-order-sum-chart");
        }

        function enrollments_by_day_chart(d = "") {
            ajPost(
                d,
                "/chart-enrollments-by-day",
                "get-enrollments-by-day-chart"
            );
        }

        function boomerangs_by_day_chart(d = "") {
            ajPost(
                d,
                "/chart-boomerangs-by-day",
                "get-boomerangs-by-day-chart"
            );
        }

        $(document).on("click", ".imgUpg", function() {
            $(this)
                .closest(".parentDiv")
                .find("input[name=my_package]")
                .attr("checked", true);
        });

        $("#btnIgo").click(function() {
            ajPost("", "/igo", "igo-agreement");
        });

        $("#btnIdecide").click(function() {
            ajPost("", "/idecide", "idecide-agreement");
        });

        $("#btnIbuumFoundation").click(function() {
            ajPost("", "/ibuum-foundation", "ibuum-foundation");
        });

        $("#login-to-events-manager-btn").click(function() {
            var eventLogin = document.querySelector("#login-to-events-manager");
            var eventLoginUsername = eventLogin.querySelector(
                'input[name="username"]'
            );
            var eventLoginPassword = eventLogin.querySelector(
                'input[name="password"]'
            );
            eventLoginUsername.setAttribute("value", "ibuumsupport");
            eventLoginPassword.setAttribute("value", "ibuum123!!!");
            //
            $("#login-to-events-manager").submit();
        });

        $("#login-to-events-browse-btn").click(function() {
            var remember_token = $("#current-user-events-token").text();
            var eventLogin = document.querySelector("#login-to-events-browse");
            var eventLoginToken = eventLogin.querySelector(
                'input[name="token"]'
            );
            eventLoginToken.setAttribute("value", remember_token);
            //
            $("#login-to-events-browse").submit();
        });

        $("#login-to-events-purchases-btn").click(function() {
            var remember_token = $("#current-user-events-token").text();
            var eventLogin = document.querySelector("#login-to-events-browse");
            var eventLoginToken = eventLogin.querySelector(
                'input[name="token"]'
            );
            eventLoginToken.setAttribute("value", remember_token);
            //
            $("#login-to-events-browse").submit();
        });

        $("#btnVibeAgree").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmVibeImportUser")
                    .find(":input")
                    .serialize(),
                "/vibe/agree",
                "vibe-agree"
            );
        });

        $(document).on("click", "#saveOnCreateAccAgree", function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost("", "/create-save-on-account", "create-save-on-account");
        });

        $(document).on("click", "#iDecideCreateAccAgree", function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost("", "/create-idecide-account", "create-idecide-account");
        });

        $("#cmbRankType").change(function() {
            var v = $(this).val();
            ajPost("rank=" + v, "/get-rank-values", "get-rank-values");
        });

        $("#btnBS_thisMonth").click(function() {
            ajPost("", "/get-bs-this-month", "get-bs-this-month");
        });

        $("#btnBS_lastMonth").click(function() {
            ajPost("", "/get-bs-last-month", "get-bs-this-month");
        });

        if ($("#upgradeCountdown").length) {
            ajPost("", "/get-upgrade-countdown", "get-upgrade-countdown");
        }
    };

    var h_order = function() {
        if ($("#dt_orders").length) {
            var from = $("#d_from").val();
            var to = $("#d_to").val();
            $("#dt_orders").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: {
                    url: burl + "/dt-orders",
                    data: function(d) {
                        d.from = from;
                        d.to = to;
                    }
                },
                columns: [
                    { data: "order_id" },
                    { data: "distid" },
                    { data: "status_desc", name: "statuscode.status_desc" },
                    { data: "trasnactionid" },
                    { data: "ordersubtotal" },
                    { data: "ordertotal" },
                    { data: "pay_method_name" },
                    { data: "created_dt" },
                    { data: "Action" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                burl +
                                `/edit-order/` +
                                full.order_id +
                                `"><i class="la la-edit"></i> Edit Details</a>
                                <a href="#" class="dropdown-item showDlgHistory" tag2="update-history" tag="` +
                                burl +
                                `/dlg-update-history/ORDER/` +
                                full.order_id +
                                `"><i class="la la-book"></i> Update History</a>
                            </div>
                        </span>`
                            );
                            return (
                                `<a class="btn btn-danger btn-sm m-btn--air" href="` +
                                burl +
                                `/edit-order/` +
                                full.order_id +
                                `">Edit</a>`
                            );
                        }
                    }
                ]
            });

            $("#ordersByDateFilterBtn").click(function(e) {
                e.preventDefault();
                var from = $("#d_from").val();
                var to = $("#d_to").val();
                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }
                window.location.replace(burl + "/orders/" + from + "/" + to);
            });

            $("#exp_orders_by_date").on("click", function(e) {
                e.preventDefault();
                var f = $("#dt_orders")
                    .dataTable()
                    .fnSettings();
                var q = $("#dt_orders_filter input").val();
                var d = $("#frmOrdersByDateFilter")
                    .find(":input")
                    .serialize();
                var i = f.aaSorting[0][0];
                var o = f.aaSorting[0][1];
                var u =
                    burl +
                    "/exp-orders/" +
                    f.aoColumns[i].data +
                    "/" +
                    o +
                    "/" +
                    q +
                    "?" +
                    d;
                window.location.replace(u);
            });
        }

        $("#btnUpdateOrder").click(function() {
            ajPost(
                $("#frmUpdateOrder")
                    .find(":input")
                    .serialize(),
                "/upgrade-order"
            );
        });

        $("#btnAddOrder").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmAddOrder")
                    .find(":input")
                    .serialize(),
                "/create-order",
                "add-new-order"
            );
        });

        $(".refund-order").click(function() {
            var btn = $(this);
            var order_id = $(btn).attr("order-id");
            swal({
                title: "Do you want to refund this order ?",
                text: "Order ID #" + order_id,
                html: getRefundPopUpHTML(),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, refund it!"
            }).then(function(result) {
                if (result.value) {
                    $(btn).prop("disabled", true);
                    $(btn).text("Please wait...");
                    var data =
                        "order_id=" +
                        order_id +
                        "&" +
                        $("#frmRefundOptions").serialize();

                    ajPost(data, "/refund-order", "refund-order");
                }
            });
        });

        $(".button-refund-order-item").click(function() {
            var btn = $(this);
            var order_item_id = $(btn).attr("order-item-id");
            swal({
                title: "Do you want to refund this order item ?",
                text: "Order Item ID #" + order_item_id,
                html: getRefundPopUpHTML(),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, refund it!"
            }).then(function(result) {
                if (result.value) {
                    $(btn).prop("disabled", true);
                    $(btn).text("Please wait...");
                    var data =
                        "order_item_id=" +
                        order_item_id +
                        "&" +
                        $("#frmRefundOptions").serialize();

                    ajPost(data, "/refund-order-item", "refund-order-item");
                }
            });
        });

        $("#btnAddCountry").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmAddCountry")
                    .find(":input")
                    .serialize(),
                "/create-country",
                "add-new-country"
            );
        });
    };

    var h_orderItem = function() {};

    var nextCheckOutModel;
    var h_model = function() {
        if ($("#showQuestionList").length) {
            showDlg($("#dd_q_list"), "startup");
        }

        if ($("#dd_reset_pass").length) {
            $("#dd_reset_pass").modal("show");
        }

        $("#btnUpgradeNow").click(function() {
            var x = $("input[name=my_package]:checked").val();
            if (typeof x !== "undefined") {
                showDlg($("#dd_upgrade"), "upgrade-now");
            } else {
                errMsg("Please select a package");
            }
        });

        $("#dd_ibuum_foundation").on(
            "click",
            "#btnCheckoutFoundation",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmIbuumFoundation")
                        .find(":input")
                        .serialize(),
                    "/checkout-foundation",
                    "checkout-foundation"
                );
            }
        );
        $("#dd_ibuum_foundation").on(
            "click",
            "#btnCheckoutFoundationBack",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost("", "/ibuum-foundation", "ibuum-foundation");
            }
        );

        $("#dd_ibuum_foundation").on(
            "click",
            "#btnNewCardCheckoutFoundation",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmIbuumFoundationNewCard")
                        .find(":input")
                        .serialize(),
                    "/ibuum-foundation-checkout-card",
                    "ibuum-foundation-checkout-card"
                );
            }
        );

        $("#btnCheckOut").click(function() {
            nextCheckOutModel = showDlg(
                $("#dd_check_out"),
                "purchaseIbuumerangPack"
            );
        });

        // ===================== Eccexerate shop buttons and banners

        $("#btnCheckOutPhotobook2020").click(function() {
            nextCheckOutModel = showDlg(
                $("#dd_check_out"),
                "purchaseXcceleratePhotobook"
            );
        });

        $("#btnCheckOutSalesToolsEng").click(function() {
            nextCheckOutModel = showDlg(
                $("#dd_check_out"),
                "purchaseXccelerateToolsEng"
            );
        });

        $("#btnCheckOutSalesToolsSpan").click(function() {
            nextCheckOutModel = showDlg(
                $("#dd_check_out"),
                "purchaseXccelerateToolsSpan"
            );
        });

        $("#btnCheckOutVideoSeries").click(function() {
            nextCheckOutModel = showDlg(
                $("#dd_check_out"),
                "purchaseVideoSeries"
            );
        });

        // ===================== End Eccexerate

        $("#buy-voucher").click(function() {
            showDlg($("#buy_voucher"), "add-new-voucher-code");
        });

        $("#buy_voucher").on("click", "#redirectDashboard", function() {
            window.location.replace(burl);
        });

        $("#dd_upgrade").on("click", "#redirectDashboard", function() {
            window.location.replace(burl);
        });

        $("#dd_check_out").on("click", "#redirectDashboard", function() {
            window.location.replace(burl);
        });

        $("#dd_subscription_reactivate_suspended_user").on(
            "click",
            "#redirectDashboard",
            function() {
                window.location.replace(burl);
            }
        );

        $("#dd_check_out").on("change", "#countryCode", function() {
            var country_code = $(this).val();
            if (country_code) {
                ajPost(
                    "country_code=" + country_code,
                    "/get-states",
                    "get-states"
                );
            }
        });

        $("#dd_upgrade").on("change", "#countryCode", function() {
            var country_code = $(this).val();
            if (country_code) {
                ajPost(
                    "country_code=" + country_code,
                    "/get-states",
                    "get-states-upgrade"
                );
            }
        });

        $("#dd_ticket_checkout").on("change", "#countryCode", function() {
            var country_code = $(this).val();
            if (country_code) {
                ajPost(
                    "country_code=" + country_code,
                    "/get-states",
                    "get-states-upgrade"
                );
            }
        });

        $("#dd_events_ticket_checkout").on(
            "change",
            "#countryCode",
            function() {
                var country_code = $(this).val();
                if (country_code) {
                    ajPost(
                        "country_code=" + country_code,
                        "/get-states",
                        "get-states-upgrade"
                    );
                }
            }
        );

        $("#dd_events_ticket_checkout").on(
            "click",
            "#redirectDashboardFromEventsTicket",
            function() {
                console.log("Redirects to dashboard");
                window.location.replace(burl);
            }
        );

        //
        $("#media_vid").on("click", ".showDlg_s", function() {
            showDlg($("#dd_s"), $(this));
        });

        $(".showDlg_s").click(function() {
            showDlg($("#dd_s"), $(this));
        });

        $(".showDlg_l").click(function() {
            showDlg($("#dd_l"), $(this));
        });

        $("body").on("click", ".showDlgHistory", function(e) {
            e.preventDefault();
            showDlg($("#dd_l"), $(this));
        });

        $("#dt_intern_leads").on("click", ".showDlg_s", function() {
            showDlg($("#dd_s"), $(this));
        });

        $("#dd_s").on("click", "#btnAddLead", function() {
            ajPost($("#frmLead").serialize(), "/add-lead", "add-lead");
        });

        $("#dd_s").on("click", "#btnUpdateLead", function() {
            ajPost($("#frmLead").serialize(), "/update-lead", "update-lead");
        });

        $("#dd_s").on("click", "#btnForgotPass", function() {
            ajPost(
                "distid=" + $("#distid").val(),
                "/forgot-password",
                "forgot-password"
            );
        });

        //
        $("#dd_upgrade").on(
            "click",
            "#btnApplyCheckOutUpgradeCoupon",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmCheckOutUpgradePaymentCoupon")
                        .find(":input")
                        .serialize(),
                    "/check-coupon-code-upgrade",
                    "check-coupon-code-upgrade"
                );
            }
        );

        //ticket
        $("#dd_ticket_checkout").on(
            "click",
            "#btnApplyCheckOutTicketCoupon",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmCheckOutPaymentCoupon")
                        .find(":input")
                        .serialize(),
                    "/check-coupon-code-ticket",
                    "check-coupon-code-ticket"
                );
            }
        );

        $("#dd_events_ticket_checkout").on(
            "click",
            "#btnApplyCheckOutTicketCoupon",
            function() {
                $(this).text("Please wait...");
                ajPost(
                    $("#frmCheckOutPaymentEventsTicket")
                        .find(":input")
                        .serialize(),
                    "/check-coupon-code-events-ticket",
                    "check-coupon-code-events-ticket"
                );
            }
        );

        $("#dd_ticket_checkout").on(
            "click",
            "#btnAddNewCardOnCheckOutTicket",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmUpgrade")
                        .find(":input")
                        .serialize(),
                    "/ticket-packs-check-out-new-card",
                    "ticket-packs-check-out-new-card"
                );
            }
        );

        $("#dd_events_ticket_checkout").on(
            "click",
            "#btnAddNewCardOnCheckOutTicket",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmUpgrade")
                        .find(":input")
                        .serialize(),
                    "/events-ticket-packs-check-out-new-card",
                    "events-ticket-packs-check-out-new-card"
                );
            }
        );

        $("#buy_voucher").on("click", "#btnAddNewDiscount", function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmAddNewVoucher")
                    .find(":input")
                    .serialize(),
                "/add-new-coupon",
                "add-new-coupon"
            );
        });

        // ---------------------------------------------------------------
        // ----------------------------------------------------------------
        // Used on reloading buumerangs and generic modal for products
        $("#dd_check_out").on("click", "#btnConfirmCheckOut", function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmCheckOut")
                    .find(":input")
                    .serialize(),
                "/ibuumerang-add-to-cart",
                "ibuumerang-add-to-cart"
            );
        });
        $("#dd_check_out").on(
            "click",
            "#btnAddNewCardOnCheckOutIbuumerangs",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmUpgrade")
                        .find(":input")
                        .serialize(),
                    "/ibuumerang-packs-check-out-new-card",
                    "ibuumerang-packs-check-out-new-card"
                );
            }
        );
        $("#dd_check_out").on(
            "click",
            "#btnApplyCheckOutBoomerangCoupon",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmCheckOutPaymentCoupon")
                        .find(":input")
                        .serialize(),
                    "/check-coupon-code-ibuumerang",
                    "check-coupon-code-ibuumerang"
                );
            }
        );
        $("#dd_check_out").on(
            "click",
            "#btnConfirmCheckOutPaymentIbuumerangPacks",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                $("#btnApplyCheckOutBoomerangCoupon").prop("disabled", true);
                ajPost(
                    $("#frmCheckOutPayment")
                        .find(":input")
                        .serialize(),
                    "/ibuumerang-packs-check-out",
                    "ibuumerang-packs-check-out"
                );
            }
        );

        $("#dd_check_out").on(
            "click",
            "#btnConfirmCheckOutPaymentGeneric",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                $("#btnApplyCheckOutBoomerangCoupon").prop("disabled", true);
                ajPost(
                    $("#frmCheckOutPayment")
                        .find(":input")
                        .serialize(),
                    "/generic-check-out",
                    "generic-check-out"
                );
            }
        );
        $("#dd_check_out").on(
            "click",
            "#btnAddNewCardOnCheckOutGeneric",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmUpgrade")
                        .find(":input")
                        .serialize(),
                    "/generic-check-out-new-card",
                    "generic-check-out-new-card"
                );
            }
        );
        $("#dd_check_out").on("click", "#btnBackCheckOutGeneric", function() {
            $("#dd_check_out").modal("toggle");
        });
        // ---------------------------------------------------------------
        // ----------------------------------------------------------------

        $("#dd_upgrade").on(
            "click",
            "#btnAddNewCardOnCheckOutUpgradeProducts",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmUpgrade")
                        .find(":input")
                        .serialize(),
                    "/upgrade-package-check-out-new-card",
                    "upgrade-package-check-out-new-card"
                );
            }
        );

        $("#dd_upgrade").on(
            "click",
            "#btnConfirmCheckOutPaymentUpgradePackage",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                $("#btnApplyCheckOutUpgradeCoupon").prop("disabled", true);
                ajPost(
                    $("#frmCheckOutPayment")
                        .find(":input")
                        .serialize(),
                    "/upgrade-product-check-out",
                    "upgrade-product-check-out"
                );
            }
        );

        $("#dd_check_out").on("click", "#btn-payment-methods", function(e) {
            handlePaymentMethodClick(e);
        });

        //ticket
        $("#dd_ticket_checkout").on(
            "click",
            "#btnConfirmCheckOutPaymentTicketPacks",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                $("#btnApplyCheckOutTicketCoupon").prop("disabled", true);
                ajPost(
                    $("#frmCheckOutPayment")
                        .find(":input")
                        .serialize(),
                    "/ticket-check-out",
                    "ticket-check-out"
                );
            }
        );

        $("#dd_events_ticket_checkout").on(
            "click",
            "#btnConfirmCheckOutPaymentTicketPacks",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");

                ajPost(
                    $("#frmCheckOutPaymentEventsTicket")
                        .find(":input")
                        .serialize(),
                    "/events-ticket-check-out",
                    "events-ticket-check-out"
                );
                $(this).prop("value", "BUY NOW");
            }
        );

        $("#dd_ticket_checkout").on("click", "#btnTicketPurchase", function() {
            $.get(burl + "/purchase-ticket-pack/", function(data) {
                $("#dd_ticket_checkout").html(data);
            });
        });
        $("#dd_events_ticket_checkout").on(
            "click",
            "#btnTicketPurchase",
            function() {
                $.get(burl + "/purchase-events-ticket-pack/", function(data) {
                    $("#dd_events_ticket_checkout").html(data);
                });
            }
        );
        $("#dd_ticket_checkout").on(
            "click",
            "#btnSkipTicketPurchase",
            function() {
                ajPost("", "/skip-ticket-confirm", "skip-ticket-confirm");
                // $("#dd_ticket_checkout").modal('hide');
            }
        );
        $("#dd_events_ticket_checkout").on(
            "click",
            "#btnSkipTicketPurchase",
            function() {
                ajPost(
                    "",
                    "/events-skip-ticket-confirm",
                    "events-skip-ticket-confirm"
                );
                // $("#dd_ticket_checkout").modal('hide');
            }
        );

        $("#dd_ticket_checkout").on(
            "click",
            "#btnTicketPurchaseSkip",
            function() {
                ajPost("", "/skip-ticket-purchased", "skip-ticket-purchased");
                $("#dd_ticket_checkout").modal("hide");
            }
        );

        $("#dd_events_ticket_checkout").on(
            "click",
            "#btnTicketPurchaseSkip",
            function() {
                ajPost(
                    "",
                    "/skip-events-ticket-purchased",
                    "skip-events-ticket-purchased"
                );
                $("#dd_events_ticket_checkout").modal("hide");
            }
        );

        $("#dd_ticket_checkout").on(
            "click",
            "#btnCheckoutTicketPurchase",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    "",
                    "/checkout-ticket-purchased",
                    "checkout-ticket-purchased"
                );
            }
        );

        $("#add-new-payment-card").click(function() {
            showDlg($("#dd_subscription_add_card"), "dlgAddNewCard");
        });

        $("#dd_events_ticket_checkout").on(
            "click",
            "#btnCheckoutTicketPurchase",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");

                var products = $(".qty-box");
                var btnCheckoutTicketPurchase = $("#btnCheckoutTicketPurchase");
                var emptyProduct = 0;

                for (var i = 0; i < products.length; i++) {
                    if (products[i].value == 0) {
                        emptyProduct++;
                        continue;
                    }
                }

                console.log("111");

                if (emptyProduct == products.length) {
                    $("#not-entered-quantities").css("visibility", "visible");

                    btnCheckoutTicketPurchase.prop("disabled", false);
                    btnCheckoutTicketPurchase.text("BUY NOW");

                    $("#frmEventsTicketCheckOut").submit(function(e) {
                        e.preventDefault(); // prevent the form from 'submitting'
                    });
                } else {
                    console.log("There are valid quantities");

                    $(this).prop("disabled", true);
                    $(this).prop("value", "Please wait...");

                    $.ajax({
                        url: "/checkout-events-ticket-purchased",
                        type: "POST",
                        data:
                            $("#frmEventsTicketCheckOut")
                                .find(":input")
                                .serialize() +
                            "&_token=" +
                            _tok,
                        dataType: "JSON",
                        success: function(data) {
                            if (data["view"]) {
                                $("#dd_events_ticket_checkout").html(
                                    data["view"]
                                );
                                $("#dd_events_ticket_checkout").modal("show");
                            }
                        }
                    });

                    $(this).prop("value", "BUY NOW");
                }
            }
        );

        //
        $("#dd_upgrade").on(
            "click",
            "#btnBackCheckOutUpgradePayment",
            function() {
                var back_page = $(this).attr("data-id");
                var product_id = $(this).attr("product-id");
                if (back_page == 1) {
                    $.get(burl + "/upgrade-now/" + product_id, function(data) {
                        $("#dd_upgrade").html(data);
                    });
                }
            }
        );

        $("#dd_upgrade").on("click", "#btn-payment-methods", function(e) {
            handlePaymentMethodClick(e);
        });

        $("#dd_check_out").on(
            "click",
            "#btnBackCheckOutPaymentIbuumerangPacks",
            function() {
                var back_page = $(this).attr("data-id");
                if (back_page == 1) {
                    $.get(burl + "/purchase-ibuumerang-pack/", function(data) {
                        $("#dd_check_out").html(data);
                    });
                } else if (back_page == 2) {
                    $.get(burl + "/ibuumerang-add-to-cart/", function(data) {
                        $("#dd_check_out").html(data.v);
                    });
                }
            }
        );

        $("#dd_ticket_checkout").on(
            "click",
            "#btnBackCheckOutPaymentTicketPacks",
            function() {
                var back_page = $(this).attr("data-id");
                if (back_page == 1) {
                    $.get(burl + "/purchase-ticket-pack/", function(data) {
                        $("#dd_ticket_checkout").html(data);
                    });
                } else if (back_page == 2) {
                    ajPost(
                        "",
                        "/checkout-ticket-purchased",
                        "checkout-ticket-purchased"
                    );
                }
            }
        );

        $("#dd_events_ticket_checkout").on(
            "click",
            "#btnBackCheckOutPaymentTicketPacks",
            function() {
                var back_page = $(this).attr("data-id");
                if (back_page == 1) {
                    $("#frmCheckOutPaymentEventsTicket").submit(function(e) {
                        e.preventDefault(); // prevent the form from 'submitting'

                        var url = burl + "/purchase-events-ticket-pack"; // get the target
                        var formData = $(this).serialize(); // get form data
                        $.get(url, formData, function(data) {
                            // send; response.data will be what is returned
                            if (data["v"]) {
                                $("#dd_events_ticket_checkout").html(data["v"]);
                                $("#dd_events_ticket_checkout").modal("show");
                            }
                        });
                    });
                } else if (back_page == 2) {
                    $.ajax({
                        url: "/checkout-events-ticket-purchased",
                        type: "POST",
                        data:
                            $("#frmEventsTicketCheckOut")
                                .find(":input")
                                .serialize() +
                            "&_token=" +
                            _tok,
                        dataType: "JSON",
                        success: function(data) {
                            if (data["view"]) {
                                $("#dd_events_ticket_checkout").html(
                                    data["view"]
                                );
                                $("#dd_events_ticket_checkout").modal("show");
                            }
                        }
                    });
                }
            }
        );

        $("#dd_reset_pass").on("click", "#btnSetNewPass", function() {
            ajPost(
                $("#frmResetPass").serialize(),
                "/reset-password",
                "reset-password"
            );
        });

        $("#dd_q_list").on("click", ".btnNextStep", function() {
            var cs = $(this).attr("currentStep");
            var t = $(this).attr("tag");
            ajPost(
                $("#dd_q_list")
                    .find(".content")
                    .find(":input")
                    .serialize() +
                    "&tag=" +
                    t,
                "/question-list-content/" + cs,
                "question-list"
            );
        });

        $("#dd_q_list").on("click", ".btnStartAgain", function() {
            ajPost("", "/question-list-content/0", "question-list");
        });

        $("#dd_q_list").on("click", "#btnCompleteQuestions", function() {
            ajPost("", "/question-list-complete");
            $("#dd_q_list").modal("hide");
        });

        $("#dd_q_list").on("keyup", "#iq_user_id", function() {
            var step1_skipBtn = $("#btnStep1");
            var v = $(this).val();
            if (v != "") {
                step1_skipBtn.text("Enter");
            } else {
                step1_skipBtn.text("Skip");
            }
        });

        $("#dd_q_list").on("click", ".cbxMyPackage", function() {
            $("#btnStep4").text("Next");
        });

        $("#dd_l").on("click", ".btnDrillDown", function() {
            var distid = $(this).attr("tag");
            ajPost("", "/report/org-drill-down/" + distid, "org-drill-down");
        });

        $("#dd_s").on("click", "#btnUpdateOrderItem", function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmUpdateOrderItem")
                    .find(":input")
                    .serialize(),
                "/upgrade-order-item",
                "upgrade-order-item"
            );
        });

        $("#dd_payout_control").on("click", "#btnUpdatePayoutMethod", function(
            e
        ) {
            e.preventDefault();
            if ($("select[name=payout_method]").val() == "") {
                errMsg("Payout method cannot be empty");
                return false;
            }
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmUpdatePayoutMethod")
                    .find(":input")
                    .serialize(),
                "/update-payout-method",
                "update-payout-method"
            );
        });

        $("#dd_s").on("click", "#btnAddOrderItem", function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmNewOrderItem")
                    .find(":input")
                    .serialize(),
                "/add-new-order-item",
                "add-new-order-item"
            );
        });

        $("#transferHistoryDetail").click(function() {
            showDlg($("#dd_l"), $(this));
        });

        $("#dt_distributor_by_rank").on(
            "click",
            ".btnDistByRankDetail",
            function() {
                $(this).prop("disabled", true);
                showDlg($("#dd_l"), $(this));
            }
        );

        $("#subscription_payment_method_type_id").change(function() {
            if ($(this).val() == 0) {
                showDlg($("#dd_subscription_add_card"), "dlgAddNewCard");
            }
        });

        $("#reactivate-subscription").click(function() {
            showDlg(
                $("#dd_subscription_reactivate"),
                "dlgSubscriptionReactivate"
            );
        });
        $("#dd_subscription_reactivate_suspended_user").on(
            "click",
            "#btnBackBtnOnSubscriptionReactivateAddNewCard",
            function() {
                showDlg(
                    $("#dd_subscription_reactivate_suspended_user"),
                    "dlgSubscriptionReactivateSuspendedUser"
                );
            }
        );
        $("#dd_suspended_account_reactivate").on(
            "click",
            "#reactivate-subscription-suspended-user",
            function() {
                showDlg(
                    $("#dd_subscription_reactivate_suspended_user"),
                    "dlgSubscriptionReactivateSuspendedUser"
                );
                $("#dd_suspended_account_reactivate").modal("hide");
            }
        );

        $(document).on(
            "click",
            "#reactivate-subscription-suspended-user",
            function() {
                showDlg(
                    $("#dd_subscription_reactivate_suspended_user"),
                    "dlgSubscriptionReactivateSuspendedUser"
                );
                $("#dd_suspended_account_reactivate").modal("hide");
            }
        );

        $("#dd_subscription_reactivate").on(
            "click",
            "#btnBackBtnOnSubscriptionReactivateAddNewCard",
            function() {
                showDlg(
                    $("#dd_subscription_reactivate"),
                    "dlgSubscriptionReactivate"
                );
            }
        );

        $(".unilevel-details").on("click", function(e) {
            e.preventDefault();

            var $modal = $("#orderDetails");

            $.ajax({
                type: "POST",
                url: burl + "/order-details",
                data: {
                    id: $(this).data("id"),
                    date: $(this).data("date"),
                    commission: "unilevel",
                    _token: _tok
                },
                cache: false,
                success: function(data) {
                    $modal.find(".modal-body").html(data);
                    $modal.modal();
                }
            });
        });

        $(".leadership-details").on("click", function(e) {
            e.preventDefault();

            var $modal = $("#orderDetails");

            $.ajax({
                type: "POST",
                url: burl + "/order-details",
                data: {
                    id: $(this).data("id"),
                    date: $(this).data("date"),
                    commission: "leadership",
                    _token: _tok
                },
                cache: false,
                success: function(data) {
                    $modal.find(".modal-body").html(data);
                    $modal.modal();
                }
            });
        });

        function showDlg($m, o) {
            var url;
            if (o == "startup") {
                url = burl + "/question-list";
            } else if (o == "upgrade-now") {
                var x = $("input[name=my_package]:checked").val();
                url = burl + "/upgrade-now/" + x;
            } else if (o == "purchaseIbuumerangPack") {
                url = burl + "/purchase-ibuumerang-pack/";
            } else if (o == "add-new-voucher-code") {
                url = burl + "/add-new-voucher-code";
            } else if (o == "checkOutPayment") {
                url = burl + "/check-payment/";
            } else if (o == "dlgAddNewCard") {
                url = burl + "/dlg-add-new-card";
            } else if (o == "dlgSubscriptionReactivate") {
                url = burl + "/dlg-subscription-reactivate";
            } else if (o == "dlgSubscriptionReactivateSuspendedUser") {
                url = burl + "/dlg-subscription-reactivate-suspended-user";
            } else if (o == "purchaseXcceleratePhotobook") {
                url = burl + "/purchase-xccelerate-photobook/";
            } else if (o == "purchaseXccelerateToolsEng") {
                url = burl + "/purchase-xccelerate-tools-eng/";
            } else if (o == "purchaseXccelerateToolsSpan") {
                url = burl + "/purchase-xccelerate-tools-span/";
            } else if (o == "purchaseVideoSeries") {
                url = burl + "/purchase-video-series/";
            } else {
                url = o.attr("tag");
            }

            $m.on("show.bs.modal", function() {
                $m.off("show.bs.modal");
                dlgShow($(this), o);
            });
            $m.on("shown.bs.modal", function() {
                $m.off("shown.bs.modal");
                dlgShown($(this), o);
            });
            $m.on("hidden.bs.modal", function() {
                // $m.off('shown.bs.modal');
                dlgHidden($(this), o);
            });

            setTimeout(function() {
                $.ajax({
                    type: "GET",
                    url: url,
                    cache: false,
                    success: function(data) {
                        $m.html(data);
                        $m.modal();
                    }
                });
            });

            function dlgShown(dlg, o) {
                $(".date_picker").datepicker({
                    format: "yyyy/mm/dd"
                });

                if (o == "startup") {
                    ajPost("", "/question-list-content/0", "question-list");
                }

                if (
                    typeof o !== "string" &&
                    o.attr("tag2") == "org-drill-down"
                ) {
                    if ($("#dt_distributors_by_level_detail").length) {
                        var level = $("#distlevel").val();
                        $("#dt_distributors_by_level_detail").DataTable({
                            serverSide: true,
                            processing: true,
                            responsive: true,
                            searchDelay: 500,
                            ajax:
                                burl +
                                "/report/dt-distributors-by-level-detail/" +
                                level,
                            columns: [
                                { data: "distid" },
                                { data: "firstname" },
                                { data: "lastname" },
                                { data: "email" },
                                { data: "username" },
                                { data: "current_product_id" },
                                { data: "sponsorid" },
                                // {data: 'current_month_rank'},
                                // {data: 'lifetime_achieved_rank'},
                                { data: "created_dt" },
                                { data: "direction" },
                                { data: "Action" }
                            ],
                            columnDefs: [
                                {
                                    targets: -1,
                                    title: "Actions",
                                    searchable: false,
                                    orderable: false,
                                    render: function(data, type, full, meta) {
                                        return (
                                            `
                        <button tag="` +
                                            full.distid +
                                            `" tag2="org-drill-down" class="btn btn-info btn-sm org-drill-down btnDrillDown">Detail</button>`
                                        );
                                    }
                                },
                                {
                                    targets: 5,
                                    render: function(data, type, full, meta) {
                                        var en_pack = {
                                            "2": {	
                                                title: "Basic Pack",	
                                                icon: "EOR_pack_icon_basic.png"	
                                            },	
                                            "3": {	
                                                title: "Visionary Pack",	
                                                icon: "EOR_pack_icon_visionary.png"	
                                            }
                                        };
                                        if (typeof en_pack[data] === "undefined") {
                                            return data;
                                        }
                                        return (
                                            '<span class="m-badge ' +
                                            en_pack[data].class +
                                            ' m-badge--wide">' +
                                            en_pack[data].title +
                                            "</span>"
                                        );
                                    }
                                }
                            ]

                        });
                    }
                } else if (
                    typeof o !== "string" &&
                    o.attr("tag2") == "dist-by-pack"
                ) {
                    if ($("#dt_distributors_by_pack").length) {
                        var packId = $("#packId").val();
                        $("#dt_distributors_by_pack").DataTable({
                            serverSide: true,
                            processing: true,
                            responsive: true,
                            searchDelay: 500,
                            ajax:
                                burl +
                                "/report/dt-distributors-by-pack/" +
                                packId,
                            columns: [
                                { data: "distid" },
                                { data: "firstname" },
                                { data: "lastname" },
                                { data: "email" },
                                { data: "username" }
                            ]
                        });
                    }
                } else if (
                    typeof o !== "string" &&
                    o.attr("tag2") == "update-history"
                ) {
                    if ($("#dt_dlg_update_history").length) {
                        var type = $("#h_type").val();
                        var id = $("#h_id").val();
                        $("#dt_dlg_update_history").DataTable({
                            serverSide: true,
                            processing: true,
                            responsive: true,
                            searchDelay: 500,
                            order: [[4, "desc"]],
                            ajax: {
                                url: burl + "/dt-dlg-update-history",
                                data: function(d) {
                                    d.id = $("#h_id").val();
                                    d.type = $("#h_type").val();
                                }
                            },
                            columns: [
                                { data: "type_id" },
                                { data: "mode" },
                                { data: "before_update" },
                                { data: "after_update" },
                                { data: "created_at" },
                                { data: "name" }
                            ],
                            columnDefs: [
                                {
                                    targets: 2,
                                    render: function(data, type, full, meta) {
                                        var before_update = JSON.parse(
                                            decodeEntities(full.before_update)
                                        );
                                        var htmlString = "<ul>";
                                        $.each(before_update, function(
                                            key,
                                            value
                                        ) {
                                            htmlString +=
                                                "<li>" +
                                                key +
                                                ": " +
                                                value +
                                                "</li>";
                                        });
                                        htmlString += "<ul>";
                                        return htmlString;
                                    }
                                },
                                {
                                    targets: 3,
                                    render: function(data, type, full, meta) {
                                        var after_update = JSON.parse(
                                            decodeEntities(full.after_update)
                                        );
                                        var htmlString = "<ul>";
                                        $.each(after_update, function(
                                            key,
                                            value
                                        ) {
                                            htmlString +=
                                                "<li>" +
                                                key +
                                                ": " +
                                                value +
                                                "</li>";
                                        });
                                        htmlString += "<ul>";
                                        return htmlString;
                                    }
                                },
                                {
                                    targets: 1,
                                    render: function(data, type, full, meta) {
                                        var status = {
                                            ADD: {
                                                title: "ADD",
                                                class: "m-badge--success"
                                            },
                                            UPDATE: {
                                                title: "UPDATE",
                                                class: " m-badge--info"
                                            }
                                        };
                                        if (
                                            typeof status[data] === "undefined"
                                        ) {
                                            return data;
                                        }
                                        return (
                                            '<span class="m-badge ' +
                                            status[data].class +
                                            ' m-badge--wide">' +
                                            status[data].title +
                                            "</span>"
                                        );
                                    }
                                }
                            ]
                        });
                    }

                    function decodeEntities(encodedString) {
                        var textArea = document.createElement("textarea");
                        textArea.innerHTML = encodedString;
                        return textArea.value;
                    }
                } else if (
                    typeof o !== "string" &&
                    o.attr("tag2") == "get-transfer-history"
                ) {
                    if ($("#dt_dlg_transfer_history").length) {
                        $("#dt_dlg_transfer_history").DataTable({
                            serverSide: true,
                            processing: true,
                            responsive: true,
                            searchDelay: 500,
                            ordering: false,
                            ajax: {
                                url: burl + "/dt-dlg-transfer-history"
                            },
                            columns: [
                                { data: "date" },
                                { data: "type" },
                                { data: "opening_balance" },
                                { data: "closing_balance" },
                                { data: "amount" },
                                { data: "type" },
                                { data: "remarks" }
                            ],
                            columnDefs: [
                                {
                                    targets: 1,
                                    render: function(data, type, full, meta) {
                                        var status = {
                                            DEPOSIT: {
                                                title: "In",
                                                class: "m-badge--success"
                                            },
                                            COUP_CODE_REFUND: {
                                                title: "In",
                                                class: "m-badge--success"
                                            },
                                            REFUND: {
                                                title: "In",
                                                class: "m-badge--success"
                                            },
                                            ADJUSTMENT_ADD: {
                                                title: "In",
                                                class: "m-badge--success"
                                            },
                                            TYPE_OUT: {
                                                title: "Out",
                                                class: " m-badge--danger"
                                            }
                                        };
                                        if (
                                            typeof status[data] === "undefined"
                                        ) {
                                            return (
                                                '<span class="m-badge ' +
                                                status["TYPE_OUT"].class +
                                                ' m-badge--wide">' +
                                                status["TYPE_OUT"].title +
                                                "</span>"
                                            );
                                        }
                                        return (
                                            '<span class="m-badge ' +
                                            status[data].class +
                                            ' m-badge--wide">' +
                                            status[data].title +
                                            "</span>"
                                        );
                                    }
                                },
                                {
                                    targets: 2,
                                    render: function(data, type, full, meta) {
                                        return (
                                            "$" +
                                            parseFloat(
                                                Math.round(data * 100) / 100
                                            ).toFixed(2)
                                        );
                                    }
                                },
                                {
                                    targets: 3,
                                    render: function(data, type, full, meta) {
                                        return (
                                            "$" +
                                            parseFloat(
                                                Math.round(data * 100) / 100
                                            ).toFixed(2)
                                        );
                                    }
                                },
                                {
                                    targets: 4,
                                    render: function(data, type, full, meta) {
                                        return (
                                            "$" +
                                            parseFloat(
                                                Math.round(data * 100) / 100
                                            ).toFixed(2)
                                        );
                                    }
                                }
                            ]
                        });
                    }
                } else if (
                    typeof o !== "string" &&
                    o.attr("tag2") == "dist-by-rank"
                ) {
                    var rank = $("#d_rank").val();
                    if ($("#dt_distributors_by_rank_detail").length) {
                        $("#dt_distributors_by_rank_detail").DataTable({
                            serverSide: true,
                            processing: true,
                            responsive: true,
                            searchDelay: 500,
                            ajax: {
                                url:
                                    burl +
                                    "/report/dt-distributor-by-rank-detail/" +
                                    encodeURI(rank)
                            },
                            columns: [
                                { data: "distid" },
                                { data: "firstname" },
                                { data: "lastname" },
                                { data: "created_dt" }
                            ],
                            columnDefs: []
                        });
                    }
                    $(".btnDistByRankDetail").removeAttr("disabled");
                    $("#exp_distributors_by_rank_detail").on("click", function(
                        e
                    ) {
                        e.preventDefault();
                        var d = $("#d_rank").val();
                        var f = $("#dt_distributor_by_rank")
                            .dataTable()
                            .fnSettings();
                        var q = $("#dt_distributor_by_rank_filter input").val();
                        var i = f.aaSorting[0][0];
                        var o = f.aaSorting[0][1];
                        var u =
                            burl +
                            "/report/exp-distributor-by-rank-detail/" +
                            f.aoColumns[i].data +
                            "/" +
                            o +
                            "/" +
                            q +
                            "?rank=" +
                            d;
                        window.location.replace(u);
                    });
                }
            }

            function dlgShow(dlg, o) {}

            function dlgHidden(dlg, o) {
                if (o == "dlgAddNewCard") {
                    location.reload();
                }
            }
        }
    };

    var h_boomerangs = function() {
        $(function() {
            var individualButton = $("#btnBoom_inv");
            var groupButton = $("#btnBoom_group");
            var buumerangTypeSelection = $("#buumerangTypeSelection");

            $("#img-vibe-driver").hide();
            $("#img-vibe-rider").hide();
            $("#img-vibe-overdrive").hide();
            $("#img-igo").hide();
            $("#img-default").show();
            $("#buumerangProduct").change(function() {
                $(".boomimage").hide();
                $("#img-" + $(this).val()).show();
                $(".buumerang-product").val($("#buumerangProduct").val());

                var userType = $(this).val();
                var isHidden = buumerangTypeSelection.is(":hidden");

                if (userType === "igo" && isHidden) {
                    buumerangTypeSelection.show();
                } else if (isHidden === false) {
                    if (groupButton.hasClass("active")) {
                        // Behold the magic of javascript! $('#btnBoom_inv').click() does not work!
                        individualButton
                            .parent()
                            .children()[0]
                            .click();
                    }

                    buumerangTypeSelection.hide();
                }
            });
        });

        $("#btnBoom_inv").click(function() {
            $("#divBoomGroup").hide();
            $("#btnCopyGroup").hide();
            $("#divBoomInd").show();
            $("#btnCopyInd").show();
            $("#btnSendSMS").prop("disabled", false);
            $("#txtSendSMS").prop("disabled", false);
            $("#btnSendEmail").prop("disabled", false);
            $("#txtSendEmail").prop("disabled", false);
        });
        $("#btnBoom_group").click(function() {
            $("#divBoomInd").hide();
            $("#btnCopyInd").hide();
            $("#divBoomGroup").show();
            $("#btnCopyGroup").show();
            $("#btnSendSMS").prop("disabled", true);
            $("#txtSendSMS").prop("disabled", true);
            $("#btnSendEmail").prop("disabled", true);
            $("#txtSendEmail").prop("disabled", true);
        });

        $("#btnGenBoom_Ind").click(function() {
            ajPost(
                $("#divBoomInd")
                    .find(":input")
                    .serialize(),
                "/gen-boom-ind",
                "gen-boom-ind"
            );
        });

        $("#btnGenBoom_Group").click(function() {
            ajPost(
                $("#divBoomGroup")
                    .find(":input")
                    .serialize(),
                "/gen-boom-group",
                "gen-boom-group"
            );
        });

        $("#btnSendSMS").click(function() {
            var intlNumber = $("#txtSendSMS").intlTelInput("getNumber");
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#divBoomInd")
                    .find(":input")
                    .serialize() +
                    "&m=" +
                    intlNumber +
                    "&c=" +
                    $("#boomCode_Ind").val(),
                "/boom-send-sms",
                "boom-send-sms"
            );
            /* clear the inputs */
            $("#selectProduct #buumerangProduct")
                .val("")
                .trigger("change");
            $("#divBoomInd #txtSendSMS").val("");
            $("#divBoomInd #txtSendEmail").val("");
            $("#divBoomInd #buumIndProd").val("");
            $("#divBoomInd #indFname").val("");
            $("#divBoomInd #indLname").val("");
            $("#divBoomInd #indExpDate")
                .val("")
                .trigger("change");
        });

        $("#btnSendEmail").click(function() {
            var clearEmail = "";
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#divBoomInd")
                    .find(":input")
                    .serialize() +
                    "&e=" +
                    $("#txtSendEmail").val() +
                    "&c=" +
                    $("#boomCode_Ind").val(),
                "/boom-send-mail",
                "boom-send-mail"
            );
            /* clear the inputs */
            $("#selectProduct #buumerangProduct")
                .val("")
                .trigger("change");
            $("#divBoomInd #txtSendSMS").val("");
            $("#divBoomInd #txtSendEmail").val("");
            $("#divBoomInd #buumIndProd").val("");
            $("#divBoomInd #indFname").val("");
            $("#divBoomInd #indLname").val("");
            $("#divBoomInd #indExpDate")
                .val("")
                .trigger("change");
        });

        if ($("#btnCopyInd").length) {
            var clipboard = new ClipboardJS("#btnCopyInd");
            clipboard.on("success", function(e) {
                okMsg("Copied");
            });
        }

        if ($("#btnCopyGroup").length) {
            var clipboard = new ClipboardJS("#btnCopyGroup");
            clipboard.on("success", function(e) {
                okMsg("Copied");
            });
        }

        if ($("#dt_payout_control").length) {
            $payout_control_table = $("#dt_payout_control").DataTable({
                ajax: burl + "/dt-payout-control",
                columns: [
                    { data: "type", name: "Payout Type" },
                    { data: "country", name: "Country" },
                    {
                        data: "action",
                        name: "Action",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }

        if ($("#dt_boomerangs_ind").length) {
            $("#dt_boomerangs_ind").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-boomerangs-ind",
                columns: [
                    { data: "lead_firstname" },
                    { data: "lead_lastname" },
                    { data: "lead_email" },
                    { data: "lead_mobile" },
                    { data: "boomerang_code" },
                    { data: "date_created" },
                    { data: "exp_dt" },
                    { data: "is_used" }
                ],
                columnDefs: [
                    {
                        targets: 7,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--danger" },
                                0: { title: "No", class: " m-badge--success" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }

        if ($("#dt_boomerangs_group").length) {
            $("#dt_boomerangs_group").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-boomerangs-group",
                columns: [
                    { data: "group_campaign" },
                    { data: "group_no_of_uses" },
                    { data: "group_available" },
                    { data: "boomerang_code" },
                    { data: "date_created" },
                    { data: "exp_dt" }
                ]
            });
        }
    };

    var h_product = function() {
        if ("#dt_products".length) {
            $("#dt_products").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-products",
                columns: [
                    { data: "id" },
                    { data: "productname" },
                    { data: "typedesc" },
                    { data: "is_enabled" },
                    { data: "productdesc" },
                    { data: "price" },
                    { data: "sku" },
                    { data: "itemcode" },
                    { data: "bv" },
                    { data: "cv" },
                    { data: "qv" },
                    { data: "qc" },
                    { data: "ac" },
                    { data: "Action" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                        <span class="dropdown">
                            <a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="` +
                                burl +
                                `/product/detail/` +
                                full.id +
                                `"><i class="la la-edit"></i> Edit Details</a>
                                <a href="#" class="dropdown-item showDlgHistory" tag2="update-history" tag="` +
                                burl +
                                `/dlg-update-history/PRODUCT/` +
                                full.id +
                                `"><i class="la la-book"></i> Update History</a>
                            </div>
                        </span>`
                            );
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }

        $("#formUpdateProduct").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/update-product",
                type: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(d) {
                    if (d["error"] == 1) {
                        rd = d;
                        if ("msg" in d) {
                            errMsg(d["msg"]);
                        }
                    } else if (d["error"] == 0) {
                        rd = d;
                        if ("msg" in d) {
                            okMsg(d["msg"]);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
                complete: function() {
                }
            });
        });

        $("#formNewProduct").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/add-new-product",
                type: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(d) {
                    if (d["error"] == 1) {
                        rd = d;
                        if ("msg" in d) {
                            errMsg(d["msg"]);
                        }
                    } else if (d["error"] == 0) {
                        rd = d;
                        if ("msg" in d) {
                            okMsg(d["msg"]);
                        }
                        if("url" in d){
                            setTimeout(function () {
                                window.location.href = d['url'];
                            }, 1500);
                        }

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
                complete: function() {
                }
            });
        });
    };

    var h_customer = function() {
        if ($("#dt_dist_customers").length) {
            $("#dt_dist_customers").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-dist-customers",
                columns: [
                    { data: "custid" },
                    { data: "name" },
                    { data: "email" },
                    { data: "mobile" },
                    { data: "boomerang_code" },
                    { data: "created_date" }
                ]
            });
        }
    };

    var h_discount = function() {
        $("#btnNewCoupon").click(function() {
            ajPost(
                $("#frmNewCoupon")
                    .find(":input")
                    .serialize(),
                "/add-new-coupon",
                "add-new-coupon"
            );
        });

        if ($("#dt_discounts").length) {
            $("#dt_discounts").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-discounts",
                columns: [
                    { data: "code" },
                    { data: "discount_amount" },
                    { data: "is_used" },
                    { data: "is_active" },
                    { data: "distid" },
                    { data: "generated_for" },
                    { data: "created_at" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            var toggleActive =
                                `<a class="btn btn-danger btn-sm m-btn--air" href="` +
                                burl +
                                `/toggle-discount-active/` +
                                full.id +
                                `">Toggle Active</a>`;

                            if (
                                full.generated_for != null &&
                                full.is_active == 1 &&
                                full.is_used == 0
                            ) {
                                toggleActive +=
                                    ` <a class="btn btn-danger btn-sm m-btn--air" href="` +
                                    burl +
                                    `/delete-discount-code/` +
                                    full.id +
                                    `">Cancel</a>`;
                            }

                            return toggleActive;
                        }
                    },
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--danger" },
                                0: { title: "No", class: " m-badge--success" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            var status = {
                                1: { title: "Yes", class: "m-badge--success" },
                                0: { title: "No", class: " m-badge--danger" }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ]
            });
        }
    };

    var h_bulkEmail = function() {
        $("#btnNewBulkMail").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmNewBulkMail")
                    .find(":input")
                    .serialize(),
                "/send-bulk-mail",
                "send-bulk-mail"
            );
        });
        $("#btnUpdateBulkMail").click(function() {
            ajPost(
                $("#frmEditBulkMail")
                    .find(":input")
                    .serialize(),
                "/update-bulk-mail"
            );
        });

        if ($("#dt_bulk_email").length) {
            $("#dt_bulk_email").DataTable({
                order: [[0, "desc"]]
            });
        }
    };

    var h_ewallet = function() {
        if ($("#dt_ewallet_pending").length) {
            $("#dt_ewallet_pending").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-ewallet-transactions",
                columns: [
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "username" },
                    { data: "amount" },
                    { data: "created_at" }
                ]
            });
        }
        $("#btnTransferNow").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost("", "/transfer-now", "transfer-now");
        });
        $("#btnTranferToPayap").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            var a = $("#transferAmt").val();
            ajPost("amount=" + a, "/transfer-to-payap", "transfer-to-payap");
        });
        $("#ipayoutAccountSetup").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost("", "/ipayout-account-setup", "ipayout-account-setup");
        });

        $("#btnTranferToIPayout").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            var a = $("#transferAmt").val();
            ajPost(
                "amount=" + a,
                "/transfer-to-ipayout",
                "transfer-to-btnTranferToIPayout"
            );
        });
    };

    var h_ewallet_csv = function() {
        if ($("#dt_ewallet_csv").length) {
            $("#dt_ewallet_csv").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: burl + "/dt-ewallet-csv",
                columns: [
                    { data: "id" },
                    { data: "memo" },
                    { data: "no_of_entries" },
                    { data: "generated_by_name" },
                    { data: "generated_on" },
                    { data: "Actions" }
                ],
                columnDefs: [
                    {
                        targets: -1,
                        title: "Actions",
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (
                                `
                            <a class="btn btn-danger btn-sm m-btn--air" href="` +
                                burl +
                                `/download-csv/` +
                                full.id +
                                `">Download CSV</a>
                            `
                            );
                        }
                    }
                ]
            });
        }
    };

    var h_commission = function() {
        if ($("#exp_commission_summary").length) {
            var from = $("#from").val();
            var to = $("#to").val();
            var total = $("#total").val();
            $("#exp_commission_summary").on("click", function(e) {
                var f = $("#dt_commission_summary")
                    .dataTable()
                    .fnSettings();
                var q = $("#dt_commission_summary_filter input").val();
                var i = f.aaSorting[0][0];
                var o = f.aaSorting[0][1];
                var u =
                    burl +
                    "/exp-commission-summary/" +
                    f.aoColumns[i].data +
                    "/" +
                    o +
                    "/" +
                    q +
                    "/?total=" +
                    total;
                window.location.replace(u);
            });
        }
        if ($("#exp_commission_detail").length) {
            var from = $("#from").val();
            var to = $("#to").val();
            var total = $("#total").val();
            $("#exp_commission_detail").on("click", function(e) {
                var f = $("#dt_commission_detail")
                    .dataTable()
                    .fnSettings();
                var q = $("#dt_commission_detail_filter input").val();
                var i = f.aaSorting[0][0];
                var o = f.aaSorting[0][1];
                var u =
                    burl +
                    "/exp-commission-detail/" +
                    f.aoColumns[i].data +
                    "/" +
                    o +
                    "/" +
                    q +
                    "/?total=" +
                    total;
                window.location.replace(u);
            });
        }

        if ($("#exp_tsb_commission_detail").length) {
            var from = $("#from").val();
            var to = $("#to").val();
            var total = $("#total").val();
            $("#exp_tsb_commission_detail").on("click", function(e) {
                var f = $("#dt_tsb_commission_detail")
                    .dataTable()
                    .fnSettings();
                var q = $("#dt_tsb_commission_detail_filter input").val();
                var i = f.aaSorting[0][0];
                var o = f.aaSorting[0][1];
                var u =
                    burl +
                    "/exp-tsb-commission-detail/" +
                    f.aoColumns[i].data +
                    "/" +
                    o +
                    "/" +
                    q +
                    "/?total=" +
                    total;
                window.location.replace(u);
            });
        }

        $("#btnRunCommission").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmRunCommission")
                    .find(":input")
                    .serialize(),
                "/run-commission",
                "run-commission"
            );
        });
        // $('#btnRunCommission').click(function () {
        //     $(this).prop('disabled', true);
        //     $(this).text('Please wait...');
        //     ajPost($('#frmRunCommission').find(':input').serialize(), '/run-commission', 'run-commission');
        // });
        $("#btnUniRunCommission").click(function() {
            ajPost(
                $("#uniRunCommission")
                    .find(":input")
                    .serialize(),
                "/unilevel-commission",
                "unilevel-commission"
            );
        });

        $("#btnTSBCommission").click(function() {
            ajPost(
                $("#tsb_commission")
                    .find(":input")
                    .serialize(),
                "/tsb-commission",
                "tsb-commission"
            );
        });
        $("#btnLeadershipCommission").click(function() {
            ajPost(
                $("#leadershipCommission")
                    .find(":input")
                    .serialize(),
                "/leadership-commission",
                "leadership-commission"
            );
        });

        $("#btnApproveCommission").click(function() {
            var f = $("#from").val();
            var t = $("#to").val();
            var b = $(this);
            swal({
                title: "Approve Commissions ?",
                text: "From " + f + " To " + t,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, approve them!"
            }).then(function(result) {
                if (result.value) {
                    b.hide();
                    ajPost("", "/approve-commission");
                }
            });
        });

        $("#btnPostCommission").click(function() {
            var f = $("#from").val();
            var t = $("#to").val();
            var b = $(this);
            swal({
                title: "Post Commissions ?",
                text: "From " + f + " To " + t,
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, post them!"
            }).then(function(result) {
                if (result.value) {
                    ajPost("", "/post-commission");
                }
            });
        });

        if ($("#dt_commission_summary").length) {
            $("#dt_commission_summary").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: burl + "/dt-commission-summary",
                columns: [
                    { data: "distid" },
                    { data: "username" },
                    { data: "amount" }
                ]
            });
        }
        if ($("#dt_withdrawals").length) {
            $("#dt_withdrawals").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: {
                    url: burl + "/commission/dt-withdrawals",
                    data: function(d) {
                        d.from = $("#d_from").val();
                        d.to = $("#d_to").val();
                    }
                },
                columns: [
                    { data: "id" },
                    { data: "distid" },
                    { data: "firstname" },
                    { data: "lastname" },
                    { data: "amount" },
                    { data: "payap_mobile" },
                    { data: "withdraw_method" },
                    { data: "created_at" },
                    { data: "remarks" }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        visible: false,
                        searchable: false
                    }
                ]
            });
        }
        if ($("#dt_commission_detail").length) {
            $("#dt_commission_detail").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: burl + "/dt-commission-detail",
                columns: [
                    { data: "transaction_date" },
                    { data: "distid" },
                    { data: "username" },
                    { data: "amount" },
                    { data: "level" },
                    { data: "memo" }
                ]
            });
        }

        if ($("#dt_tsb_commission_detail").length) {
            $("#dt_tsb_commission_detail").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: burl + "/dt-tsb-commission-detail",
                columns: [
                    { data: "replace" },
                    { data: "distid" },
                    { data: "username" },
                    { data: "amount" },
                    { data: "memo" }
                ]
            });
        }

        if ($("#dt_commission_detail_post").length) {
            $("#dt_commission_detail_post").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: burl + "/dt-commission-detail-post",
                columns: [
                    { data: "transaction_date" },
                    { data: "distid" },
                    { data: "username" },
                    { data: "amount" },
                    { data: "level" },
                    { data: "memo" }
                ]
            });
        }

        if ($("#dt_approved_commission_summary").length) {
            $("#dt_approved_commission_summary").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: burl + "/dt-approved-commission-summary",
                columns: [
                    { data: "distid" },
                    { data: "username" },
                    { data: "amount" }
                ]
            });
        }
        if ($("#dt_approved_commission_detail").length) {
            $("#dt_approved_commission_detail").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[0, "desc"]],
                ajax: burl + "/dt-approved-commission-detail",
                columns: [
                    { data: "processed_date" },
                    { data: "transaction_date" },
                    { data: "distid" },
                    { data: "username" },
                    { data: "amount" },
                    { data: "level" },
                    { data: "memo" }
                ]
            });
        }

        if ($("#pearData").length) {
            var parts = window.location.href.split("/");
            var id = parts.pop() || null;

            if (isNaN(id)) {
                id = null;
            }

            $("#pearData").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searching: false,
                order: [[0, "desc"]],
                ajax: burl + "/report/pear-data/" + (id ? id : ""),
                columns: [
                    { data: "name", orderable: false, searchable: false },
                    { data: "current_month_qv" },
                    { data: "qv_contribution" },
                    { data: "pqv" },
                    { data: "rankdesc" }
                ]
            });
        }

        $("#btnAdjustments").click(function() {
            ajPost(
                $("#frmAdjustments")
                    .find(":input")
                    .serialize(),
                "/adjustments",
                "adjustments"
            );
        });
        if ($("#withdrawalFilterBtn").length) {
            $("#withdrawalFilterBtn").click(function() {
                var from = $("#d_from").val();
                var to = $("#d_to").val();
                if ((from == "" && to != "") || (from != "" && to == "")) {
                    errMsg("Enter From date and To date");
                    return false;
                }
                window.location.replace(
                    burl + "/commission/withdrawals/" + from + "/" + to
                );
            });
        }
    };

    var h_update_history = function() {
        if ($("#dt_update_history").length) {
            var t = $("#his_type").val();
            $("#dt_update_history").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                order: [[4, "desc"]],
                ajax: burl + "/dt-update-history/" + t,
                columns: [
                    { data: "type_id" },
                    { data: "mode" },
                    { data: "before_update" },
                    { data: "after_update" },
                    { data: "created_at" },
                    { data: "name" }
                ],
                columnDefs: [
                    {
                        targets: 2,
                        render: function(data, type, full, meta) {
                            var before_update = JSON.parse(
                                decodeEntities(full.before_update)
                            );
                            var htmlString = "<ul>";
                            $.each(before_update, function(key, value) {
                                htmlString +=
                                    "<li>" + key + ": " + value + "</li>";
                            });
                            htmlString += "<ul>";
                            return htmlString;
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, full, meta) {
                            var after_update = JSON.parse(
                                decodeEntities(full.after_update)
                            );
                            var htmlString = "<ul>";
                            $.each(after_update, function(key, value) {
                                htmlString +=
                                    "<li>" + key + ": " + value + "</li>";
                            });
                            htmlString += "<ul>";
                            return htmlString;
                        }
                    },
                    {
                        targets: 1,
                        render: function(data, type, full, meta) {
                            var status = {
                                ADD: {
                                    title: "ADD",
                                    class: "m-badge--success"
                                },
                                UPDATE: {
                                    title: "UPDATE",
                                    class: " m-badge--info"
                                },
                                REFUND: {
                                    title: "REFUND",
                                    class: " m-badge--danger"
                                }
                            };
                            if (typeof status[data] === "undefined") {
                                return data;
                            }
                            return (
                                '<span class="m-badge ' +
                                status[data].class +
                                ' m-badge--wide">' +
                                status[data].title +
                                "</span>"
                            );
                        }
                    }
                ],
                rowCallback: function(row, data, index) {}
            });
        }
        function decodeEntities(encodedString) {
            var textArea = document.createElement("textarea");
            textArea.innerHTML = encodedString;
            return textArea.value;
        }
    };

    var h_subscription = function() {
        $("#btnSaveSubscription").click(function() {
            $.getJSON(
                burl + "/get-grace-period",
                $("#frmSubscription")
                    .find(":input")
                    .serialize(),
                function(data) {
                    if (data.alert == 1) {
                        $("#gflag").val(1);
                        swal({
                            title: data.title,
                            text: data.text,
                            type: data.type,
                            showCancelButton: true,
                            confirmButtonText: "Yes, set it!"
                        }).then(function(result) {
                            if (result.value) {
                                ajPost(
                                    $("#frmSubscription")
                                        .find(":input")
                                        .serialize(),
                                    "/subscription"
                                );
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        $("#gflag").val(0);
                        ajPost(
                            $("#frmSubscription")
                                .find(":input")
                                .serialize(),
                            "/subscription"
                        );
                    }
                }
            );
        });

        $("#dd_subscription_add_card").on("change", "#countryCode", function() {
            var country_code = $(this).val();
            if (country_code) {
                ajPost(
                    "country_code=" + country_code,
                    "/get-states",
                    "get-states"
                );
            }
        });

        $("#dd_subscription_reactivate").on(
            "change",
            "#countryCode",
            function() {
                var country_code = $(this).val();
                if (country_code) {
                    ajPost(
                        "country_code=" + country_code,
                        "/get-states",
                        "get-states"
                    );
                }
            }
        );

        $("#dd_subscription_reactivate_suspended_user").on(
            "change",
            "#countryCode",
            function() {
                var country_code = $(this).val();
                if (country_code) {
                    ajPost(
                        "country_code=" + country_code,
                        "/get-states",
                        "get-states"
                    );
                }
            }
        );

        $("#dd_subscription_add_card").on(
            "click",
            "#btnAddNewCardOnSubscription",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmSubscriptionAddCard")
                        .find(":input")
                        .serialize(),
                    "/add-new-card-subscription",
                    "add-new-card-subscription"
                );
            }
        );

        $("#dd_subscription_reactivate").on(
            "click",
            "#btnSubscriptionReactivateSubmitButton",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmReactivateSubscription")
                        .find(":input")
                        .serialize(),
                    "/reactivate-subscription",
                    "reactivate-subscription"
                );
            }
        );

        $("#dd_subscription_reactivate_suspended_user").on(
            "click",
            "#btnSubscriptionReactivateSubmitButton",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmReactivateSuspendedSubscription")
                        .find(":input")
                        .serialize(),
                    "/reactivate-suspended-subscription",
                    "reactivate-suspended-subscription"
                );
            }
        );

        $("#dd_subscription_reactivate").on(
            "click",
            "#btnReactivateSubscriptionAddCouponCode",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Wait...");
                ajPost(
                    $("#reactivateSubscriptionAddCouponCode")
                        .find(":input")
                        .serialize(),
                    "/reactivate-subscription-add-coupon-code",
                    "reactivate-subscription-add-coupon-code"
                );
            }
        );

        $("#dd_subscription_reactivate_suspended_user").on(
            "click",
            "#btnReactivateSubscriptionAddCouponCode",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Wait...");
                ajPost(
                    $("#reactivateSubscriptionAddCouponCode")
                        .find(":input")
                        .serialize(),
                    "/reactivate-suspended-subscription-add-coupon-code",
                    "reactivate-subscription-suspended-user-add-coupon-code"
                );
            }
        );

        $("#dd_subscription_reactivate_suspended_user").on(
            "click",
            "#btnReactivateSubscriptionSuspendedUserAddCouponCode",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Wait...");
                ajPost(
                    $("#reactivateSubscriptionAddCouponCode")
                        .find(":input")
                        .serialize(),
                    "/reactivate-subscription-add-suspended-user-coupon-code",
                    "reactivate-subscription-add-suspended-user-coupon-code"
                );
            }
        );

        $("#dd_subscription_reactivate").on(
            "click",
            "#btnAddNewCardOnSubscriptionReactivate",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmSubscriptionReactivateAddCard")
                        .find(":input")
                        .serialize(),
                    "/add-new-card-subscription-reactivate",
                    "add-new-card-subscription-reactivate"
                );
            }
        );

        $("#dd_subscription_reactivate_suspended_user").on(
            "click",
            "#btnAddNewCardOnSubscriptionReactivateSuspendedUser",
            function() {
                $(this).prop("disabled", true);
                $(this).text("Please wait...");
                ajPost(
                    $("#frmSubscriptionReactivateAddCardSuspendedUser")
                        .find(":input")
                        .serialize(),
                    "/add-new-card-subscription-reactivate-suspended-user",
                    "add-new-card-subscription-reactivate-suspended-user"
                );
            }
        );
    };

    var h_api_token = function() {
        if ($("#dt_api_token").length) {
            $("#dt_api_token").DataTable();
        }

        if ($("#dt_api_requests").length) {
            $("#dt_api_requests").DataTable({
                serverSide: true,
                processing: true,
                responsive: true,
                searchDelay: 500,
                ajax: burl + "/dt-api-requests",
                columns: [
                    { data: "request_on" },
                    { data: "request" },
                    { data: "status" },
                    { data: "token" }
                ]
            });
        }
    };

    var h_binary_permission = function() {
        $("#btnSaveBinaryPermission").click(function() {
            ajPost(
                $("#frmBinaryPermission")
                    .find(":input")
                    .serialize(),
                "/save-binary-permission"
            );
        });
    };

    var h_binary_editor = function() {
        if ($(".select2_tree_from").length) {
            $(".select2_tree_from").select2({
                placeholder: "Distributor at tree",
                allowClear: true,
                ajax: {
                    url: burl + "/binary-tree-editor/replace",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(d, params) {
                        params.page = params.page || 1;

                        return {
                            results: d.data,
                            pagination: {
                                more: params.page * 10 < d.total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        }
        if ($("#select2_tree_to").length) {
            $("#select2_tree_to").select2({
                placeholder: "Replace with",
                allowClear: true,
                ajax: {
                    url: burl + "/binary-tree-editor/replace-with",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(d, params) {
                        params.page = params.page || 1;

                        return {
                            results: d.data,
                            pagination: {
                                more: params.page * 10 < d.total
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        }
        $("#binaryTreeReplace").click(function() {
            ajPost(
                $("#frmBinaryTreeReplace")
                    .find(":input")
                    .serialize(),
                "/binary-tree-editor/replace"
            );
        });
        $("#binaryTreeSearch").click(function() {
            ajPost(
                $("#frmBinaryTreeSearch")
                    .find(":input")
                    .serialize(),
                "/binary-tree-editor/search"
            );
        });
    };

    var h_upgrade_control = function() {
        $("#btnIndUpgrade").click(function() {
            ajPost(
                $("#frmIndUpgradeDate")
                    .find(":input")
                    .serialize(),
                "/save-dist-expiry-date"
            );
        });
        $("#btnDistsUpgrade").click(function() {
            ajPost(
                $("#frmDistsExpUpgradeDate")
                    .find(":input")
                    .serialize(),
                "/save-dists-expiry-date"
            );
        });
    };

    var h_payout_control = function() {
        $("#payoutSetDefault").click(function() {
            ajPost(
                $("#frmPayoutSetDefault")
                    .find(":input")
                    .serialize(),
                "/payout-control-set-default"
            );
            $payout_control_table.ajax.reload();
        });
        $(document).on("click", ".edit-payout", function() {
            $.get(burl + "/edit-payout/" + $(this).attr("data-id"), function(
                data
            ) {
                $("#dd_payout_control").html(data["v"]);
                $("#dd_payout_control").modal("show");
            });
        });
    };

    var h_subscription_reactivate = function() {
        $("#btnAdminSubscriptionReactivateSubmitButton").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmSubscriptionReactivate")
                    .find(":input")
                    .serialize(),
                "/subscription-reactivate",
                "admin-subscription-reactivate"
            );
        });
    };

    var h_ambassador_reactivate = function() {
        $("#btnAdminAmbassadorReactivateSubmitButton").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmAmbassadorReactivate")
                    .find(":input")
                    .serialize(),
                "/ambassador-reactivate",
                "admin-ambassador-reactivate"
            );
        });
    };

    var h_user_transfer = function() {
        var resendCode = function() {
            var resendButton = $("#btnResend2FA");
            resendButton.prop("disabled", true);
            resendButton.text("Resending code...");

            $.ajax({
                url: "/authy/request",
                method: "POST",
                data: {
                    _token: _tok
                },
                success: function() {
                    alert("Code has been resent successfully.");
                    resendButton.prop("disabled", false);
                    resendButton.text("Resend Code");
                }
            });
        };

        $("#btnResend2FA").click(resendCode);

        // btnResend2FA
        $("#pdfButton").click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/vitals",
                type: "POST",
                data: {
                    _token: _tok
                },
                dataType: "JSON",
                success: function(data) {
                    if (data.success === false) {
                        errMsg("There was an error with the code to send.");
                    } else {
                        var $modal = $("#2FactorDialog");
                        $modal.modal();
                    }
                }
            });
        });

        $("#btnSubmit2FAEWallet").click(function() {
            $("#div2FAError").hide();
            $("#div2FAResendMsg").hide();
            $(this)
                .html('<i class="fa fa-spinner fa-spin"></i> Wait')
                .prop("disabled", true);

            var verificationCode = $("#2FactorDialog #verificationCode").val();

            if (!verificationCode.length) {
                errMsg("You must enter the verification code.");
                return;
            }

            $.ajax({
                url: "/sub-tfa",
                type: "POST",
                data: {
                    verification_code: verificationCode,
                    _token: _tok
                },
                dataType: "JSON",
                success: function(data) {
                    if (data.success === false) {
                        errMsg(data.msg);

                        if (data.failed_count >= 3) {
                            $("#frm2FA").hide();
                        }
                        $("#btnSubmit2FAEWallet")
                            .html("Submit")
                            .prop("disabled", false);
                    } else {
                        $("#btnSubmit2FAEWallet")
                            .html("Submit")
                            .prop("disabled", false);
                        $("#2FactorDialog").modal("hide");
                        $("#verificationCode").val("");

                        window.open(data.url);
                    }
                }
            });
        });

        $("#btnResend2FAEWallet").on("click", function() {
            $("#div2FAError").hide();
            $("#div2FAResendMsg").hide();
            $(this)
                .html('<i class="fa fa-spinner fa-spin"></i> Wait')
                .prop("disabled", true);
            ajPost("", "/resend-tfa", "resend-tfa");
        });

        $("#btnSubmit2FA").click(function() {
            var verificationCode = $("#2FactorDialog #verificationCode").val();

            if (verificationCode.length === 0) {
                alert("You must enter the verification code.");
                return;
            }

            $.ajax({
                url: "/authy/verify",
                type: "POST",
                data: {
                    verification_code: verificationCode,
                    _token: _tok
                },
                dataType: "JSON",
                success: function(data) {
                    if (data.success === false) {
                        var reSend = confirm(
                            "The code was incorrect. Do you want to resend the code?"
                        );
                        if (reSend === true) {
                            resendCode();
                        }
                    } else {
                        $("#2FactorDialog").modal("hide");
                        $("#2FactorDialog #verificationCode").val("");
                        $("#transferConfirmationDialog").modal();
                    }
                }
            });
        });

        $("#transferConfirmationDialog #submitBtn").click(function() {
            var outsideSubmitButton = $("#btnTransferUserOwnership");
            $("#transferConfirmationDialog").modal("hide");

            $("#is_confirmed").val(1);
            outsideSubmitButton.prop("disabled", true);
            outsideSubmitButton.text("Please wait...");
            ajPost(
                $("#frmTransferUser")
                    .find(":input")
                    .serialize(),
                "/user/transfer",
                "admin-user-transfer-confirmed"
            );
        });

        $("#btnTransferUserOwnership").click(function() {
            $(this).prop("disabled", true);
            $(this).text("Please wait...");
            ajPost(
                $("#frmTransferUser")
                    .find(":input")
                    .serialize(),
                "/user/transfer",
                "admin-user-transfer"
            );
        });
    };

    var confirmUserTransfer = function() {
        var distId = $("#select4_sponsor").val();
        var form = $("#frmTransferUser");
        var firstName = form.find(":input[name='firstname']").val();
        var lastName = form.find(":input[name='lastname']").val();
        var fullName = firstName + " " + lastName;

        $("#tsaNumber").text("TSA#: " + distId);
        $("#name").text("Recipient: " + fullName);

        $.ajax({
            url: "/authy/request",
            method: "POST",
            data: {
                _token: _tok
            },
            success: function(data) {
                if (data.success === false) {
                    alert(
                        "An unknown error occurred when attempting to use Authy. Please contact us."
                    );
                } else {
                    $("#2FactorDialog").modal();
                }
            }
        });
    };

    var h_rank_settings = function() {
        $(".checkbox-toggle").bootstrapToggle();

        $("#btnSaveRankSettings").click(function() {
            var result = confirm(
                "Are you sure you want to do this? This will affect the next running ranks cron."
            );

            if (result !== true) {
                return;
            }

            var dataString = "";

            $("#rankTimingForm")
                .find("input:checked")
                .each(function() {
                    dataString += "hour[]=" + $(this).attr("data-hour") + "&";
                });

            dataString = dataString.substring(0, dataString.length - 1);

            ajPost(dataString, "/settings/ranks");
        });
    };

    var h_new_enrollment = function() {
        $("#btnEnroll").click(function() {
            $(this).attr("disabled", "disabled");
            $(this).text("Please wait....");
            ajPost(
                $("#frmEnroll")
                    .find(":input")
                    .serialize(),
                "/users/enroll",
                "new-enrollment"
            );
        });

        var setSubscriptionStartDate = function() {
            var package = $("#enrollmentPackageSelect").val();
            var startDate = moment()
                .add(1, "month")
                .format("YYYY-MM-DD");

            if (package == 13) {
                startDate = moment()
                    .add(3, "month")
                    .format("YYYY-MM-DD");
            }

            $("#subscriptionStartDateInput").val(startDate);
        };

        setSubscriptionStartDate();

        const METRO_PAYMENT_PROCESSOR = 12;
        const T1_PAYMENT_PROCESSOR = 9;

        var selectSubscription = function() {
            var enrollmentPackage = $("#enrollmentPackageSelect").val();

            var subscriptionProduct = 11;

            if (enrollmentPackage == 1) {
                subscriptionProduct = 33;
            } else if (enrollmentPackage == 2) {
                var isTier3 =
                    $("#countryCodeSelect")
                        .children("option:selected")
                        .data("tier3") === 1;

                if (isTier3) {
                    subscriptionProduct = 26;
                }
            }

            $("#subscriptionProductSelect").val(subscriptionProduct);
        };

        var calculateTotal = function() {
            if ($("#paymentMethodSelect").val() == "comp") {
                $("#totalInput").val("$0.00");
                $("#totalInput").data("price", 0);
                return;
            }

            var enrollmentPackageSelect = $("#enrollmentPackageSelect");
            var packageId = parseInt(enrollmentPackageSelect.val());
            var standByPrice = parseFloat(
                enrollmentPackageSelect.children("option:first").data("price")
            );
            var price = parseFloat(
                enrollmentPackageSelect
                    .children("option:selected")
                    .data("price")
            );
            var total = standByPrice;

            // standby (packageId 1) can't buy any extra product
            // 2020 graduate (packageId 13) can only buy video training
            // everyone else (packageId > 1) can buy event tickets
            if (packageId > 1) {
                total += price;

                if (packageId == 13) {
                    var addVideoTrainingCheckbox = $(
                        "#addVideoTrainingCheckbox"
                    );
                    if (addVideoTrainingCheckbox.prop("checked")) {
                        total += parseFloat(
                            addVideoTrainingCheckbox.data("price")
                        );
                    }
                } else {
                    var addEventTicketCheckbox = $("#addEventTicketCheckbox");
                    if (addEventTicketCheckbox.prop("checked")) {
                        total += parseFloat(
                            addEventTicketCheckbox.data("price")
                        );
                    }
                }
            }

            $("#totalInput").data("price", total.toFixed(2));
            $("#totalInput").val("$" + total.toFixed(2));
        };

        $("#enrollmentPackageSelect").change(function() {
            var package = parseInt($(this).val());
            var addVideoTrainingDiv = $("#addVideoTrainingDiv");
            var addEventTicketDiv = $("#addEventTicketDiv");

            if (package == 1) {
                disableAndHide(addVideoTrainingDiv);
                disableAndHide(addEventTicketDiv);
            } else if (package == 13) {
                enableAndShow(addVideoTrainingDiv);
                disableAndHide(addEventTicketDiv);
            } else {
                disableAndHide(addVideoTrainingDiv);
                enableAndShow(addEventTicketDiv);
            }

            selectSubscription();
            setSubscriptionStartDate();
            calculateTotal();
        });

        var countryCodeChange = function() {
            var target = $("#billingSameCheckbox").prop("checked")
                ? "#countryCodeSelect"
                : "#billingCountryCodeSelect";

            var paymentMethodSelect = $("#paymentMethodSelect");
            var usOptionGroup = paymentMethodSelect
                .children("optgroup")
                .first();
            var paymentProcessor = paymentMethodSelect.val();
            var country = $(target).val();

            selectSubscription();
            calculateTotal();

            if (paymentProcessor == "comp" || paymentProcessor == "voucher") {
                return;
            }

            if (country != "US") {
                usOptionGroup.attr("disabled", "disabled");

                if (paymentProcessor != T1_PAYMENT_PROCESSOR) {
                    paymentMethodSelect.val(T1_PAYMENT_PROCESSOR);
                }
            } else if (country == "US") {
                if (usOptionGroup.attr("disabled") == "disabled") {
                    usOptionGroup.removeAttr("disabled");
                }

                if (paymentProcessor == T1_PAYMENT_PROCESSOR) {
                    paymentMethodSelect.val(METRO_PAYMENT_PROCESSOR);
                }
            }
        };

        $("#billingCountryCodeSelect").change(countryCodeChange);
        $("#countryCodeSelect").change(countryCodeChange);

        var disableAndHide = function(element) {
            element.hide();
            element.find("input").attr("disabled", "disabled");
        };

        var enableAndShow = function(element) {
            element.show();
            element.find("input").removeAttr("disabled");
        };

        $("#billingSameCheckbox").change(function() {
            const billingAddress = $("#billingAddress");
            if ($(this)[0].checked) {
                disableAndHide(billingAddress);
            } else {
                enableAndShow(billingAddress);
            }

            countryCodeChange();
        });

        $("#paymentMethodSelect").change(function() {
            var processor = $(this).val();
            var creditCardSection = $("#creditCardSection");
            var compSection = $("#compSection");
            var voucherSection = $("#voucherSection");

            if (processor == "voucher") {
                disableAndHide(creditCardSection);
                disableAndHide(compSection);
                enableAndShow(voucherSection);
            } else if (processor == "comp") {
                disableAndHide(creditCardSection);
                disableAndHide(voucherSection);
                enableAndShow(compSection);
            } else {
                // only credit card payment methods remain
                disableAndHide(voucherSection);
                disableAndHide(compSection);
                enableAndShow(creditCardSection);
            }

            calculateTotal();
        });

        $("#btnNextStep").click(function() {
            var steps = $(".step");
            var numSteps = steps.length;
            var maxStepIndex = numSteps - 1;

            var currentStepIndex = -1;

            for (var index = 0; index < numSteps; index++) {
                var step = $(steps[index]);

                if (step.hasClass("active-step")) {
                    currentStepIndex = index;
                    break;
                }
            }

            var nextStepIndex = currentStepIndex + 1;
            var currentStep = $(steps[currentStepIndex]);

            if (currentStep[0].checkValidity() === false) {
                currentStep[0].reportValidity();
                return;
            }

            if (currentStepIndex != maxStepIndex) {
                currentStep.removeClass("active-step");
                $(steps[nextStepIndex]).addClass("active-step");
            }

            var nextStepButton = $(this);
            var prevStepButton = $("#btnPrevStep");

            if (prevStepButton.attr("disabled") == "disabled") {
                prevStepButton.removeAttr("disabled");
            }

            if (nextStepButton.attr("disabled") == "disabled") {
                nextStepButton.removeAttr("disabled");
            }

            if (nextStepIndex == maxStepIndex) {
                nextStepButton.attr("disabled", "disabled");
            }
        });

        $("#btnPrevStep").click(function() {
            var steps = $(".step");
            var numSteps = steps.length;
            var maxStepIndex = numSteps - 1;

            var currentStepIndex = -1;

            for (var index = 0; index < numSteps; index++) {
                var step = $(steps[index]);

                if (step.hasClass("active-step")) {
                    currentStepIndex = index;
                    break;
                }
            }
            var prevStepIndex = currentStepIndex - 1;

            if (currentStepIndex != 0) {
                $(steps[currentStepIndex]).removeClass("active-step");
                $(steps[prevStepIndex]).addClass("active-step");
            }

            var nextStepButton = $("#btnNextStep");
            var prevStepButton = $("#btnPrevStep");

            if (prevStepButton.attr("disabled") == "disabled") {
                prevStepButton.removeAttr("disabled");
            }

            if (nextStepButton.attr("disabled") == "disabled") {
                nextStepButton.removeAttr("disabled");
            }

            if (prevStepIndex == 0) {
                prevStepButton.attr("disabled", "disabled");
            }
        });

        var fieldMappings = {
            usernameInput: "usernameCopy",
            emailInput: "emailCopy",
            defaultPasswordInput: "defaultPasswordCopy"
        };

        $.each(fieldMappings, function(originalId, copyId) {
            $("#" + originalId).change(function() {
                $("#" + copyId).val($(this).val());
            });
        });

        $("#btnVerifyVoucher").click(function() {
            $(this).attr("disabled", "disabled");
            $(this).text("Please wait...");

            let data = $("#voucherSection")
                .find("input")
                .serialize();
            data += "&total=" + parseFloat($("#totalInput").data("price"));

            ajPost(data, "/users/enroll/verify-voucher", "verify-voucher");
        });

        $("#addEventTicketCheckbox").change(calculateTotal);
        $("#addVideoTrainingCheckbox").change(calculateTotal);
        $("#addEventTicketCheckbox").click(calculateTotal);
        $("#addVideoTrainingCheckbox").click(calculateTotal);

        $("#btnComplete").click(function() {
            setTimeout(() => {
                window.location.reload();
            }, 4000);
        });
    };

    var h_rank_settings = function() {
        $(".checkbox-toggle").bootstrapToggle();

        $("#btnSaveRankSettings").click(function() {
            var result = confirm(
                "Are you sure you want to do this? This will affect the next running ranks cron."
            );

            if (result !== true) {
                return;
            }

            var dataString = "";

            $("#rankTimingForm")
                .find("input:checked")
                .each(function() {
                    dataString += "hour[]=" + $(this).attr("data-hour") + "&";
                });

            dataString = dataString.substring(0, dataString.length - 1);

            ajPost(dataString, "/settings/ranks");
        });
    };

    function ajPost(da, u, ptt) {
        var rd;
        $.ajax({
            type: "POST",
            url: burl + u,
            data: da + "&_token=" + _tok,
            dataType: "JSON",
            cache: false,
            success: function(d) {
                if (d["error"] == 1) {
                    rd = d;
                    if ("msg" in d) {
                        errMsg(d["msg"]);
                    }
                } else if (d["error"] == 0) {
                    rd = d;
                    afterSub(ptt, d);
                    if ("msg" in d) {
                        okMsg(d["msg"]);
                    }
                }
            },
            complete: function() {
                afterCom(ptt, rd);
            }
        });

        function afterSub(ptt, data) {}

        function afterCom(ptt, rd) {
            if (rd != undefined && rd["error"] == 0) {
                if ("url" in rd) {
                    if (rd["url"] == "reload")
                        setTimeout(() => {
                            window.location.reload();
                        }, 4000);
                    else {
                        if ("target_blank" in rd) {
                            window.open(rd["url"]);
                        } else {
                            window.location.replace(rd["url"]);
                        }
                    }
                } else if (ptt == "login") {
                    // b.prop('disabled', false);
                    // b.text('APPLY');
                    $("#dd_suspended_account_reactivate").html(rd["v"]);
                    $("#dd_suspended_account_reactivate").modal("show");
                } else if (ptt == "sign-up") {
                    $("#divFrmSignup").hide();
                    $("#divAccCreated").show();
                } else if (ptt == "replicating-preferences-reset") {
                    $("#replicatedPrefs").html(rd.template);
                } else if (ptt == "validate-promo") {
                    $("#frmPromo").submit();
                } else if (ptt == "validate-media") {
                    $("#frmMedia").submit();
                } else if (ptt == "media-vid-view") {
                    $("#media_vid").html(rd["v"]);
                } else if (ptt == "media-img-view") {
                    $("#media_img").html(rd["v"]);
                    //
                    var $image = $("#img_viewer");

                    $image.viewer({
                        inline: true,
                        viewed: function() {
                            $image.viewer("zoomTo", 1);
                        }
                    });
                    var viewer = $image.data("viewer");
                    $("#med_imgs").viewer();
                } else if (ptt == "media-doc-view") {
                    $("#media_doc").html(rd["v"]);
                } else if (ptt == "media-pres-view") {
                    $("#media_pres").html(rd["v"]);
                } else if (ptt == "forgot-password") {
                    $("#dd_s").modal("hide");
                } else if (ptt == "reset-password") {
                    $("#dd_reset_pass").modal("hide");
                } else if (ptt == "question-list") {
                    $("#dd_q_list")
                        .find(".content")
                        .html(rd["v"]);
                } else if (ptt == "gen-boom-ind") {
                    $("#boomCode_Ind").val(rd["code"]);
                    $("#boomCount_avail").val(rd["available"]);
                    $("#boomCount_pending").val(rd["pending"]);
                    console.log("boomCount_avail: " + rd["pending"]);
                } else if (ptt == "gen-boom-group") {
                    $("#boomCode_group").val(rd["code"]);
                    $("#boomCount_avail").val(rd["available"]);
                    $("#boomCount_pending").val(rd["pending"]);
                } else if (ptt == "get-total-order-sum-chart") {
                    AmCharts.makeChart("dbChart", {
                        rtl: mUtil.isRTL(),
                        type: "serial",
                        theme: "light",
                        dataProvider: rd["data"],
                        valueAxes: [
                            {
                                gridColor: "#FFFFFF",
                                gridAlpha: 0.2,
                                dashLength: 0
                            }
                        ],
                        gridAboveGraphs: true,
                        startDuration: 1,
                        graphs: [
                            {
                                balloonText: "[[category]]: <b>[[value]]</b>",
                                fillAlphas: 0.8,
                                lineAlpha: 0.2,
                                type: "column",
                                valueField: "total_order_amount_sum"
                            }
                        ],
                        chartCursor: {
                            categoryBalloonEnabled: false,
                            cursorAlpha: 0,
                            zoomable: false
                        },
                        categoryField: "created_dt",
                        categoryAxis: {
                            gridPosition: "start",
                            labelRotation: 90
                        },
                        export: {
                            enabled: true
                        }
                    });
                    $(".chartTitle").text("Daily Sales");
                } else if (ptt == "get-enrollments-by-day-chart") {
                    AmCharts.makeChart("dbChart", {
                        rtl: mUtil.isRTL(),
                        type: "serial",
                        theme: "light",
                        dataProvider: rd["data"],
                        valueAxes: [
                            {
                                gridColor: "#FFFFFF",
                                gridAlpha: 0.2,
                                dashLength: 0
                            }
                        ],
                        gridAboveGraphs: true,
                        startDuration: 1,
                        graphs: [
                            {
                                balloonText: "[[category]]: <b>[[value]]</b>",
                                fillAlphas: 0.8,
                                lineAlpha: 0.2,
                                type: "column",
                                valueField: "en_count"
                            }
                        ],
                        chartCursor: {
                            categoryBalloonEnabled: false,
                            cursorAlpha: 0,
                            zoomable: false
                        },
                        categoryField: "created_dt",
                        categoryAxis: {
                            gridPosition: "start",
                            labelRotation: 90
                        },
                        export: {
                            enabled: true
                        }
                    });
                    $(".chartTitle").text("Enrollments");
                } else if (ptt == "get-boomerangs-by-day-chart") {
                    AmCharts.makeChart("dbChart", {
                        rtl: mUtil.isRTL(),
                        type: "serial",
                        theme: "light",
                        dataProvider: rd["data"],
                        valueAxes: [
                            {
                                gridColor: "#FFFFFF",
                                gridAlpha: 0.2,
                                dashLength: 0
                            }
                        ],
                        gridAboveGraphs: true,
                        startDuration: 1,
                        graphs: [
                            {
                                balloonText:
                                    "Induvidual Boomerang: <b>[[value]]</b>",
                                fillAlphas: 0.8,
                                lineAlpha: 0.2,
                                type: "column",
                                valueField: "ind_count"
                            },
                            {
                                balloonText:
                                    "Group Boomerang: <b>[[value]]</b>",
                                fillAlphas: 0.8,
                                lineAlpha: 0.2,
                                type: "column",
                                valueField: "grp_count"
                            }
                        ],
                        chartCursor: {
                            categoryBalloonEnabled: false,
                            cursorAlpha: 0,
                            zoomable: false
                        },
                        categoryField: "date_created",
                        categoryAxis: {
                            gridPosition: "start",
                            labelRotation: 90
                        },
                        export: {
                            enabled: true
                        }
                    });
                    $(".chartTitle").text("Boomerangs");
                } else if (ptt == "add-new-coupon") {
                    $("#frmNewCoupon")
                        .find("#code")
                        .val(rd["code"]);
                    $("#frmNewCoupon")
                        .find("#amount")
                        .val("");
                    $("#select2_sponsor")
                        .empty()
                        .trigger("change");
                    $("#buy_voucher").html(rd["v"]);
                } else if (ptt == "org-drill-down") {
                    $("#dd_l").html(rd["v"]);
                    if ($("#dt_distributors_by_level_detail").length) {
                        var distid = $("#distid").val();
                        $("#dt_distributors_by_level_detail").DataTable({
                            serverSide: true,
                            processing: true,
                            responsive: true,
                            searchDelay: 500,
                            ajax: burl + "/report/dt-org-drill-down/" + distid,
                            columns: [
                                { data: "distid" },
                                { data: "firstname" },
                                { data: "lastname" },
                                { data: "email" },
                                { data: "username" },
                                { data: "current_product_id" },
                                { data: "sponsorid" },
                                { data: "created_dt" },
                                { data: "direction" },
                                // {data: 'current_month_rank'},
                                // {data: 'lifetime_achieved_rank'},
                                { data: "Action" }
                            ],
                            columnDefs: [
                                {
                                    targets: -1,
                                    title: "Actions",
                                    searchable: false,
                                    orderable: false,
                                    render: function(data, type, full, meta) {
                                        return (
                                            `
                        <button tag="` +
                                            full.distid +
                                            `" tag2="org-drill-down" class="btn btn-info btn-sm org-drill-down btnDrillDown">Detail</button>`
                                        );
                                    }
                                },
                                {
                                    targets: 5,
                                    render: function(data, type, full, meta) {
                                        var en_pack = {
                                            "2": {	
                                                title: "Basic Pack",	
                                                icon: "EOR_pack_icon_basic.png"	
                                            },	
                                            "3": {	
                                                title: "Visionary Pack",	
                                                icon: "EOR_pack_icon_visionary.png"	
                                            }
                                        };
                                        if (typeof en_pack[data] === "undefined") {
                                            return data;
                                        }

                                        return (
                                            '<span class="m-badge ' +
                                            en_pack[data].class +
                                            ' m-badge--wide">' +
                                            en_pack[data].title +
                                            "</span>"
                                        );
                                    }
                                }
                            ]
                        });
                    }
                } else if (ptt == "ibuumerang-add-to-cart") {
                    $("#dd_check_out").html(rd["v"]);
                } else if (ptt == "ibuumerang-packs-check-out") {
                    $("#dd_check_out").html(rd["v"]);
                } else if (ptt == "generic-check-out") {
                    $("#dd_check_out").html(rd["v"]);
                } else if (ptt == "generic-check-out-new-card") {
                    $("#dd_check_out").html(rd["v"]);
                } else if (ptt == "ticket-check-out") {
                    $("#dd_ticket_checkout").html(rd["v"]);
                } else if (ptt == "events-ticket-check-out") {
                    $("#dd_events_ticket_checkout").html(rd["v"]);
                } else if (ptt == "skip-ticket-confirm") {
                    $("#dd_ticket_checkout").html(rd["v"]);
                } else if (ptt == "skip-events-ticket-confirm") {
                    $("#dd_events_ticket_checkout").html(rd["v"]);
                } else if (ptt == "check-coupon-code-ibuumerang") {
                    var b = $("#dd_check_out").find(
                        "#btnApplyCheckOutBoomerangCoupon"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#ibuumerang_product_table").html(rd["v"]);
                    if (rd.total == 0) {
                        $("#dd_check_out")
                            .find(".quick-checkout")
                            .hide();
                        $("#btnConfirmCheckOutPaymentIbuumerangPacks").text(
                            "CONTINUE"
                        );
                    } else {
                        $("#dd_check_out")
                            .find(".quick-checkout")
                            .show();
                        $("#btnConfirmCheckOutPaymentIbuumerangPacks").text(
                            "SUBMIT"
                        );
                    }
                } else if (ptt == "check-coupon-code-ticket") {
                    var b = $("#dd_ticket_checkout").find(
                        "#btnApplyCheckOutTicketCoupon"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#ticket_product_table").html(rd["v"]);
                    if (rd.total == 0) {
                        $("#dd_ticket_checkout")
                            .find(".quick-checkout")
                            .hide();
                        $("#btnConfirmCheckOutPaymentTicketPacks").text(
                            "CONTINUE"
                        );
                    } else {
                        $("#dd_ticket_checkout")
                            .find(".quick-checkout")
                            .show();
                        $("#btnConfirmCheckOutPaymentTicketPacks").text(
                            "SUBMIT"
                        );
                    }
                } else if (ptt == "check-coupon-code-events-ticket") {
                    var b = $("#dd_events_ticket_checkout").find(
                        "#btnApplyCheckOutTicketCoupon"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#ticket_product_table").html(rd["v"]);
                    if (rd.total == 0) {
                        $("#dd_events_ticket_checkout")
                            .find(".quick-checkout")
                            .hide();
                        $("#btnConfirmCheckOutPaymentTicketPacks").text(
                            "CONTINUE"
                        );
                    } else {
                        $("#dd_events_ticket_checkout")
                            .find(".quick-checkout")
                            .show();
                        $("#btnConfirmCheckOutPaymentTicketPacks").text(
                            "SUBMIT"
                        );
                    }
                } else if (ptt == "check-coupon-code-events-ticket") {
                    var b = $("#dd_events_ticket_checkout").find(
                        "#btnApplyCheckOutTicketCoupon"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#ticket_product_table").html(rd["v"]);
                    if (rd.total == 0) {
                        $("#dd_events_ticket_checkout")
                            .find(".quick-checkout")
                            .hide();
                        $("#btnConfirmCheckOutPaymentTicketPacks").text(
                            "CONTINUE"
                        );
                    } else {
                        $("#dd_events_ticket_checkout")
                            .find(".quick-checkout")
                            .show();
                        $("#btnConfirmCheckOutPaymentTicketPacks").text(
                            "SUBMIT"
                        );
                    }
                } else if (ptt == "ibuumerang-packs-check-out-new-card") {
                    $("#dd_check_out").html(rd["v"]);
                } else if (ptt == "ticket-packs-check-out-new-card") {
                    $("#dd_ticket_checkout").html(rd["v"]);
                } else if (ptt == "events-ticket-packs-check-out-new-card") {
                    $("#dd_events_ticket_checkout").html(rd["v"]);
                } else if (ptt == "get-states") {
                    $("#stateprov").html(rd["v"]);
                } else if (ptt == "get-states-upgrade") {
                    $("#stateprov").html(rd["v"]);
                } else if (ptt == "check-coupon-code-upgrade") {
                    var b = $("#dd_upgrade").find(
                        "#btnApplyCheckOutUpgradeCoupon"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#upgrade_product_table").html(rd["v"]);
                    if (rd.total > 0) {
                        $("#dd_upgrade")
                            .find(".quick-checkout")
                            .show();
                        $("#btnConfirmCheckOutPaymentUpgradePackage").text(
                            "SUBMIT"
                        );
                    } else {
                        $("#dd_upgrade")
                            .find(".quick-checkout")
                            .hide();
                        $("#btnConfirmCheckOutPaymentUpgradePackage").text(
                            "CONTINUE"
                        );
                    }
                } else if (ptt == "upgrade-product-check-out") {
                    var b = $("#dd_upgrade").find(
                        "#btnConfirmCheckOutPaymentUpgradePackage"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#dd_upgrade").html(rd["v"]);
                } else if (ptt == "upgrade-package-check-out-new-card") {
                    var b = $("#dd_upgrade").find(
                        "#btnAddNewCardOnCheckOutUpgradeProducts"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#dd_upgrade").html(rd["v"]);
                } else if (ptt == "get-rank-values") {
                    var p = $("#divRanking");
                    p.find("#divQV").text(rd["qv"]);
                    p.find("#divTSA").text(rd["tsa"]);
                    p.find("#divQualification").text(rd["qualification"]);
                    p.find("#divCurrentMonthlyQV").text(
                        rd["current_monthly_qv"]
                    );
                    p.find("#divActiveTSANeeded").text(rd["active_tsa_needed"]);
                    p.find("#divPercentage").text(rd["percentage"]);
                    p.find("#divTop3").html(rd["v_contributors"]);
                    p.find("#divTopQC").html(rd["qc_contributors"]);
                    p.find("#divQC").html(rd["qualifying_qc"]);
                    p.find("#qcDivPercentage").html(rd["qc_percent"]);
                    p.find("#divCurrentMonthlyQC").html(rd["qc_volume"]);
                    p.find(".binary-limit").html(rd["binary_count"]);
                    if (rd["binary_count"] > 0) {
                        p.find(".personally-enrolled").addClass("active");
                    } else {
                        p.find(".personally-enrolled").removeClass("active");
                    }
                } else if (ptt == "get-bs-this-month") {
                    var p = $("#businss_widget");
                    p.find("#divAcheivedRank").text(rd["rankdesc"]);
                    p.find("#divMonthlyQV").text(rd["monthly_qv"]);
                    p.find("#divQulifiedVol").text(rd["qualified_qv"]);
                    p.find("#divComm").text(rd["comm"]);
                } else if (ptt == "get-upgrade-countdown") {
                    var upgBtn = $("#btnUpgradeNow");
                    if (rd["date"]) {
                        var offset = new Date().getTimezoneOffset();
                        var localOffset = offset * 60000;
                        var countDownDate =
                            new Date(rd["date"]).getTime() + localOffset;

                        var x = setInterval(function() {
                            var now = new Date().getTime();
                            var distance = countDownDate - now;
                            var days = Math.floor(
                                distance / (1000 * 60 * 60 * 24)
                            );
                            var hours = Math.floor(
                                (distance % (1000 * 60 * 60 * 24)) /
                                    (1000 * 60 * 60)
                            );
                            var minutes = Math.floor(
                                (distance % (1000 * 60 * 60)) / (1000 * 60)
                            );
                            var seconds = Math.floor(
                                (distance % (1000 * 60)) / 1000
                            );

                            $("#upgradeCountdown").text(
                                ("0" + days).slice(-2) +
                                    " : " +
                                    ("0" + hours).slice(-2) +
                                    " : " +
                                    ("0" + minutes).slice(-2) +
                                    " : " +
                                    ("0" + seconds).slice(-2)
                            );
                            if (distance < 0) {
                                clearInterval(x);
                                $("#upgradeCountdown").text(
                                    "00 : 00 : 00 : 00"
                                );
                                // upgBtn.hide();
                                upgBtn.show();
                            } else {
                                upgBtn.show();
                            }
                        }, 1000);
                    } else {
                        $("#upgradeCountdown").text("00 : 00 : 00 : 00");
                        upgBtn.hide();
                    }
                } else if (ptt == "adjustments") {
                    $("#frmAdjustments")
                        .find(":input")
                        .val("");
                    $("#select2_sponsor")
                        .empty()
                        .trigger("change");
                } else if (ptt == "save-payap") {
                    $("#dd_payap_config").modal("hide");
                } else if (ptt == "save-payap-ssn") {
                    $("#dd_payap_config_ssn").modal("hide");
                } else if (ptt == "ibuumerang-add-to-cart") {
                    $("#dd_check_out").html(rd["v"]);
                } else if (ptt == "add-new-discount") {
                    $("#dd_check_out").html(rd["v"]);
                } else if (ptt == "add-new-card-subscription") {
                    var b = $("#dd_subscription_add_card").find(
                        "#btnAddNewCardOnSubscription"
                    );
                    b.prop("disabled", false);
                    b.text("SUBMIT");

                    $("#dd_subscription_add_card").modal("hide");
                    $(
                        "#subscription_payment_method_type_id option:last"
                    ).replaceWith(
                        '<option value="' +
                            rd.payment_method_id +
                            '" selected>' +
                            rd.card_name +
                            "</option>"
                    );
                } else if (ptt == "reactivate-suspended-subscription") {
                    $("#dd_subscription_reactivate_suspended_user").html(
                        rd["v"]
                    );
                } else if (ptt == "reactivate-subscription") {
                    if (rd["act"] == "add_new_card") {
                        $("#dd_subscription_reactivate").html(rd["v"]);
                    } else if (rd["act"] == "ewallet" || rd["act"] == "card") {
                        var b = $("#dd_subscription_reactivate").find(
                            "#btnSubscriptionReactivateSubmitButton"
                        );
                        $("#dd_subscription_reactivate").modal("hide");
                        $("#subscription-status").removeClass("m--font-danger");
                        $("#subscription-status").addClass("m--font-success");
                        $("#subscription-status").html("Active");
                        $("#reactivate-subscription").hide();
                        $("#next-subscription-date").val(rd["nd"]);
                        b.prop("disabled", false);
                        b.text("Submit");
                    }
                } else if (ptt == "reactivate-subscription-add-coupon-code") {
                    $("#dd_subscription_reactivate").html(rd["v"]);
                    var b = $("#dd_subscription_reactivate").find(
                        "#btnReactivateSubscriptionAddCouponCode"
                    );
                    b.prop("disabled", false);
                    b.text("Apply");
                    if (rd["total"] == 0) {
                        $(
                            "#subscription_payment_method_type_id option[value='add_new_card']"
                        ).remove();
                    }
                } else if (
                    ptt ==
                    "reactivate-subscription-suspended-user-add-coupon-code"
                ) {
                    $("#dd_subscription_reactivate_suspended_user").html(
                        rd["v"]
                    );
                    var b = $(
                        "#dd_subscription_reactivate_suspended_user"
                    ).find("#btnReactivateSubscriptionAddCouponCode");
                    b.prop("disabled", false);
                    b.text("Apply");
                    if (rd["total"] == 0) {
                        $("#addNewCard").remove();
                    }
                } else if (ptt == "add-new-card-subscription-reactivate") {
                    var b = $("#dd_subscription_reactivate").find(
                        "#btnAddNewCardOnSubscriptionReactivate"
                    );
                    $("#frmSubscriptionReactivateAddCard")
                        .find(":input")
                        .val("");
                    $("#dd_subscription_reactivate").modal("hide");
                    $("#subscription-status").removeClass("m--font-danger");
                    $("#subscription-status").addClass("m--font-success");
                    $("#subscription-status").html("Active");
                    $("#reactivate-subscription").hide();
                    $("#next-subscription-date").val(rd["nd"]);
                    b.prop("disabled", false);
                    b.text("Submit");
                } else if (ptt == "refund-order") {
                    var b = $(document).find(".refund-order");
                    b.prop("disabled", false);
                    b.text("Refund order");
                } else if (ptt == "refund-order-item") {
                    var b = $(document).find(".button-refund-order-item");
                    b.text("Refund");
                } else if (
                    ptt == "add-new-card-subscription-reactivate-suspended-user"
                ) {
                    var b = $("#dd_subscription_reactivate").find(
                        "#btnAddNewCardOnSubscriptionReactivate"
                    );
                    $("#frmSubscriptionReactivateAddCard")
                        .find(":input")
                        .val("");
                    $("#dd_subscription_reactivate").modal("hide");
                    $("#subscription-status").removeClass("m--font-danger");
                    $("#subscription-status").addClass("m--font-success");
                    $("#subscription-status").html("Active");
                    $("#reactivate-subscription").hide();
                    $("#next-subscription-date").val(rd["nd"]);
                    b.prop("disabled", false);
                    b.text("Submit");
                } else if (ptt == "refund-order") {
                    var b = $(document).find(".refund-order");
                    b.prop("disabled", false);
                    b.text("Refund order");
                } else if (ptt == "update-payout-method") {
                    var b = $("#dd_payout_control").find(
                        "#btnUpdatePayoutMethod"
                    );
                    b.prop("disabled", false);
                    b.text("Save Changes");
                    $("#dd_payout_control").modal("hide");
                    $payout_control_table.ajax.reload();
                } else if (ptt == "idecide-agreement") {
                } else if (ptt == "igo-agreement") {
                } else if (ptt == "ibuum-foundation") {
                    $("#dd_ibuum_foundation").html(rd["v"]);
                    $("#dd_ibuum_foundation").modal("show");
                } else if (ptt == "checkout-foundation") {
                    var b = $("#dd_ibuum_foundation").find(
                        "#btnCheckoutFoundation"
                    );
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                    $("#dd_ibuum_foundation").html(rd["v"]);
                    $("#dd_ibuum_foundation").modal("show");
                } else if (ptt == "checkout-ticket-purchased") {
                    $("#dd_ticket_checkout").html(rd["v"]);
                    $("#dd_ticket_checkout").modal("show");
                } else if (ptt == "ibuum-foundation-checkout-card") {
                    var b = $("#dd_ibuum_foundation").find(
                        "#btnNewCardCheckoutFoundation"
                    );
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                    $("#dd_ibuum_foundation").modal("hide");
                } else if (ptt == "refund-voucher") {
                    $("#voucherCode").html(rd["voucher"]);
                    $("#tsaNumber").html(rd["tsaNumber"]);
                    $("#fullName").html(rd["fullName"]);
                    $("#amount").html(rd["amount"]);
                    $("#orderId").val(rd["orderId"]);
                    $("#voucherRefundSection").removeClass("m--hide");
                }
            } else {
                if (ptt == "run-commission") {
                    var b = $("#btnRunCommission");
                    b.prop("disabled", false);
                    b.text("Run");
                } else if (ptt == "unilevel-commission") {
                    var b = $("#btnUniRunCommission");
                    b.prop("disabled", false);
                    b.text("Run");
                } else if (ptt == "leadership-commission") {
                    var b = $("#btnLeadershipCommission");
                    b.prop("disabled", false);
                    b.text("Run");
                } else if (ptt == "upgrade-order-item") {
                    var b = $("#dd_s").find("#btnUpdateOrderItem");
                    b.prop("disabled", false);
                    b.text("Save Changes");
                } else if (ptt == "update-payout-method") {
                    var b = $("#dd_payout_control").find(
                        "#btnUpdatePayoutMethod"
                    );
                    b.prop("disabled", false);
                    b.text("Save Changes");
                } else if (ptt == "add-new-order-item") {
                    var b = $("#dd_s").find("#btnAddOrderItem");
                    b.prop("disabled", false);
                    b.text("Add New");
                } else if (ptt == "add-new-order") {
                    var b = $("#btnAddOrder");
                    b.prop("disabled", false);
                    b.text("Save Order");
                } else if (ptt == "add-new-country") {
                    var b = $("#btnAddCountry");
                    b.prop("disabled", false);
                    b.text("Save Country");
                } else if (ptt == "ibuumerang-add-to-cart") {
                    var b = $("#btnConfirmCheckOut");
                    b.prop("disabled", false);
                    b.text("ADD TO CART");
                } else if (ptt == "ibuumerang-packs-check-out") {
                    var b = $("#btnConfirmCheckOutPaymentIbuumerangPacks");
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                    $("#btnApplyCheckOutBoomerangCoupon").prop(
                        "disabled",
                        false
                    );
                } else if (ptt == "ticket-check-out") {
                    var b = $("#btnConfirmCheckOutPaymentTicketPacks");
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                    $("#btnApplyCheckOutTicketCoupon").prop("disabled", false);
                } else if (ptt == "events-ticket-check-out") {
                    var b = $("#btnConfirmCheckOutPaymentTicketPacks");
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                    $("#btnApplyCheckOutTicketCoupon").prop("disabled", false);
                } else if (ptt == "checkout-ticket-purchased") {
                    var b = $("#btnCheckoutTicketPurchase");
                    b.prop("disabled", false);
                    b.text("BUY NOW");
                } else if (ptt == "checkout-events-ticket-purchased") {
                    var b = $("#btnCheckoutTicketPurchase");
                    b.prop("disabled", false);
                    b.text("BUY NOW");
                } else if (ptt == "check-coupon-code-ibuumerang") {
                    $("input[name=coupon]").val("");
                    var b = $("#btnApplyCheckOutBoomerangCoupon");
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#ibuumerang_product_table").html(rd["v"]);
                    if (rd.total > 0) {
                        $("#dd_check_out")
                            .find(".quick-checkout")
                            .show();
                        $("#btnConfirmCheckOutPaymentIbuumerangPacks").text(
                            "SUBMIT"
                        );
                    } else {
                        $("#dd_check_out")
                            .find(".quick-checkout")
                            .hide();
                        $("#btnConfirmCheckOutPaymentIbuumerangPacks").text(
                            "CONTINUE"
                        );
                    }
                } else if (ptt == "check-coupon-code-ticket") {
                    $("input[name=coupon]").val("");
                    var b = $("#btnApplyCheckOutTicketCoupon");
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#ticket_product_table").html(rd["v"]);
                    if (rd.total > 0) {
                        $("#dd_ticket_checkout")
                            .find(".quick-checkout")
                            .show();
                        $("#btnConfirmCheckOutPaymentTicketPacks").text(
                            "SUBMIT"
                        );
                    } else {
                        $("#dd_ticket_checkout")
                            .find(".quick-checkout")
                            .hide();
                        $("#btnConfirmCheckOutPaymentTicketPacks").text(
                            "CONTINUE"
                        );
                    }
                } else if (ptt == "ibuumerang-packs-check-out-new-card") {
                    var b = $("#btnAddNewCardOnCheckOutIbuumerangs");
                    b.prop("disabled", false);
                    b.text("APPLY");
                } else if (ptt == "generic-check-out-new-card") {
                    var b = $("#btnAddNewCardOnCheckOutGeneric");
                    b.prop("disabled", false);
                    b.text("APPLY");
                } else if (ptt == "ticket-packs-check-out-new-card") {
                    var b = $("#btnAddNewCardOnCheckOutTicket");
                    b.prop("disabled", false);
                    b.text("APPLY");
                } else if (ptt == "check-coupon-code-upgrade") {
                    $("input[name=coupon]").val("");
                    var b = $("#dd_upgrade").find(
                        "#btnApplyCheckOutUpgradeCoupon"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#upgrade_product_table").html(rd["v"]);
                    if (rd.total > 0) {
                        $("#dd_upgrade")
                            .find(".quick-checkout")
                            .show();
                        $("#btnConfirmCheckOutPaymentUpgradePackage").text(
                            "SUBMIT"
                        );
                    } else {
                        $("#dd_upgrade")
                            .find(".quick-checkout")
                            .hide();
                        $("#btnConfirmCheckOutPaymentUpgradePackage").text(
                            "CONTINUE"
                        );
                    }
                } else if (ptt == "upgrade-package-check-out-new-card") {
                    var b = $("#dd_upgrade").find(
                        "#btnAddNewCardOnCheckOutUpgradeProducts"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#dd_upgrade").html(rd["v"]);
                } else if (ptt == "upgrade-product-check-out") {
                    var b = $("#dd_upgrade").find(
                        "#btnConfirmCheckOutPaymentUpgradePackage"
                    );
                    b.prop("disabled", false);
                    b.text("APPLY");
                    $("#btnApplyCheckOutUpgradeCoupon").prop("disabled", false);
                    $("#dd_upgrade").html(rd["v"]);
                } else if (ptt == "add-new-card-subscription") {
                    var b = $("#dd_subscription_add_card").find(
                        "#btnAddNewCardOnSubscription"
                    );
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                } else if (ptt == "add-new-coupon") {
                    var b = $("#buy_voucher").find("#btnAddNewDiscount");
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                    $("#buy_voucher").html(rd["v"]);
                } else if (ptt == "reactivate-subscription-add-coupon-code") {
                    $("#dd_subscription_reactivate").html(rd["v"]);
                    var b = $("#dd_subscription_reactivate").find(
                        "#btnReactivateSubscriptionAddCouponCode"
                    );
                    b.prop("disabled", false);
                    b.text("Apply");
                } else if (
                    ptt ==
                    "reactivate-subscription-suspended-user-add-coupon-code"
                ) {
                    $("#dd_subscription_reactivate_suspended_user").html(
                        rd["v"]
                    );
                    var b = $(
                        "#dd_subscription_reactivate_suspended_user"
                    ).find("#btnReactivateSubscriptionAddCouponCode");
                    b.prop("disabled", false);
                    b.text("Apply");
                } else if (ptt == "add-new-card-subscription-reactivate") {
                    var b = $("#dd_subscription_reactivate").find(
                        "#btnAddNewCardOnSubscriptionReactivate"
                    );
                    b.prop("disabled", false);
                    b.text("Apply");
                } else if (
                    ptt == "add-new-card-subscription-reactivate-suspended-user"
                ) {
                    var b = $(
                        "#dd_subscription_reactivate_suspended_user"
                    ).find(
                        "#btnAddNewCardOnSubscriptionReactivateSuspendedUser"
                    );
                    b.prop("disabled", false);
                    b.text("Apply");
                } else if (ptt == "refund-order") {
                    var b = $(document).find(".refund-order");
                    b.prop("disabled", false);
                    b.text("Refund order");
                } else if (ptt == "reactivate-subscription") {
                    var b = $("#dd_subscription_reactivate").find(
                        "#btnSubscriptionReactivateSubmitButton"
                    );
                    b.prop("disabled", false);
                    b.text("Submit");
                } else if (ptt == "reactivate-suspended-subscription") {
                    var b = $(
                        "#dd_subscription_reactivate_suspended_user"
                    ).find("#btnSubscriptionReactivateSubmitButton");
                    b.prop("disabled", false);
                    b.text("Submit");
                } else if (ptt == "idecide-agreement") {
                    if (rd["v"]) {
                        $("#dd_idecide_agreement").html(rd["v"]);
                        $("#dd_idecide_agreement").modal("show");
                    }
                } else if (ptt == "ibuum-foundation") {
                    $("#dd_ibuum_foundation").html(rd["v"]);
                    $("#dd_ibuum_foundation").modal("show");
                } else if (ptt == "igo-agreement") {
                    if (rd["v"]) {
                        $("#dd_igo_agreement").html(rd["v"]);
                        $("#dd_igo_agreement").modal("show");
                    }
                } else if (ptt == "create-idecide-account") {
                    $("#iDecideCreateAccAgree").prop("disabled", false);
                    $("#iDecideCreateAccAgree").text("Agree & Continue");
                    $("#dd_idecide_agreement").modal("hide");
                } else if (ptt == "create-save-on-account") {
                    $("#saveOnCreateAccAgree").prop("disabled", false);
                    $("#saveOnCreateAccAgree").text("Agree & Continue");
                    $("#dd_igo_agreement").modal("hide");
                } else if (ptt == "checkout-foundation") {
                    var b = $("#dd_ibuum_foundation").find(
                        "#btnCheckoutFoundation"
                    );
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                } else if (ptt == "ibuum-foundation-checkout-card") {
                    var b = $("#dd_ibuum_foundation").find(
                        "#btnNewCardCheckoutFoundation"
                    );
                    b.prop("disabled", false);
                    b.text("SUBMIT");
                    $("#dd_ibuum_foundation").html(rd["v"]);
                }
            }
            if (ptt == "transfer-to-payap") {
                if (rd["show_payap_config_dlg"] == 1) {
                    $("#dd_payap_config").modal("show");
                } else if (rd["show_payap_config_dlg"] == 2) {
                    $("#dd_payap_config_ssn").modal("show");
                }
                var b = $("#btnTranferToPayap");
                b.prop("disabled", false);
                b.text("Transfer");
            }
            if (ptt == "ipayout-account-setup") {
                var b = $("#ipayoutAccountSetup");
                b.prop("disabled", false);
                b.text("Setup Account");
            } else if (ptt == "transfer-to-btnTranferToIPayout") {
                var b = $("#btnTranferToIPayout");
                b.prop("disabled", false);
                b.text("Transfer");
            } else if (ptt == "save-on-transfer") {
                $("#btnSorTransfer").prop("disabled", false);
            } else if (ptt == "create-idecide") {
                $("#btnCreateIDecide").prop("disabled", false);
            } else if (ptt == "create-idecide-account") {
                $("#iDecideCreateAccAgree").prop("disabled", false);
                $("#iDecideCreateAccAgree").text("Agree & Continue");
                $("#dd_idecide_agreement").modal("hide");
            } else if (ptt == "create-save-on-account") {
                $("#saveOnCreateAccAgree").prop("disabled", false);
                $("#saveOnCreateAccAgree").text("Agree & Continue");
                $("#dd_igo_agreement").modal("hide");
            } else if (ptt == "create-tv-idecide") {
                var b = $("#btnCreateTVIDecide");
                b.prop("disabled", false);
                b.text("Get iDecide Now !");
            } else if (ptt == "transfer-now") {
                var b = $("#btnTransferNow");
                b.prop("disabled", false);
                b.text("Transfer Now !");
            } else if (ptt == "save-profile") {
                var b = $("#btnSaveProfile");
                b.prop("disabled", false);
                b.text("Save basic information");
            } else if (ptt == "save-placements") {
                var b = $("#btnSavePlacements");
                b.prop("disabled", false);
                b.text("Save Placements");
            } else if (ptt == "save-primary-card") {
                var b = $("#btnSavePrimaryCard");
                b.prop("disabled", false);
                b.text("Save primary card detail");
            } else if (ptt == "save-primary-address") {
                var b = $("#btnSaveAddress");
                b.prop("disabled", false);
                b.text("Save primary address");
            } else if (ptt == "save-billing-address") {
                var b = $("#btnSaveBillingAddress");
                b.prop("disabled", false);
                b.text("Save billing address");
            } else if (ptt == "reset-idecide-password") {
                var b = $("#btnSaveIdecidePassword");
                b.prop("disabled", false);
                b.text("Save new password");
            } else if (ptt == "reset-idecide-mail") {
                var b = $("#btnSaveIdecideEmail");
                b.prop("disabled", false);
                b.text("Save new email");
            } else if (ptt == "save-payap") {
                var b = $("#btnSavePayap");
                b.prop("disabled", false);
                b.text("Save");
            } else if (ptt == "save-payap-ssn") {
                var b = $("#btnSavePayapSSN");
                b.prop("disabled", false);
                b.text("Save");
            } else if (ptt == "boom-send-sms") {
                var b = $("#btnSendSMS");
                b.prop("disabled", false);
                b.text("Send as SMS");
            } else if (ptt == "boom-send-mail") {
                var b = $("#btnSendEmail");
                b.prop("disabled", false);
                b.text("Send as Email");
            } else if (ptt == "send-bulk-mail") {
                var b = $("#btnNewBulkMail");
                b.prop("disabled", false);
                b.text("Send");
            } else if (ptt == "add-new-ambassador") {
                var b = $("#btnNewIntern");
                b.prop("disabled", false);
                b.text("Save");
            } else if (ptt == "update-ambassador") {
                var b = $("#btnUpdateIntern");
                b.prop("disabled", false);
                b.text("Save");
            } else if (ptt == "sor-toggle-status") {
                var b = $("#btnToggleSORStatus");
                b.prop("disabled", false);
                b.text("Toggle");
            } else if (ptt == "idecide-toggle-status") {
                var b = $("#btnToggleIDecideStatus");
                b.prop("disabled", false);
                b.text("Toggle");
            } else if (ptt == "billing-add-new-card") {
                var b = $("#btnAddCard");
                b.prop("disabled", false);

                var value = $("#billingAddressSelect :selected").val();

                if (value == -1) {
                    b.text("Add card & address");
                } else {
                    b.text("Add card");
                }
            } else if (ptt == "vibe-agree") {
                var button = $("#btnVibeAgree");
                button.prop("disabled", false);
                button.text("Accept & Continue");
            } else if (ptt == "admin-subscription-reactivate") {
                var button = $("#btnAdminSubscriptionReactivateSubmitButton");
                button.prop("disabled", false);
                button.text("Submit");
            } else if (ptt == "admin-user-transfer") {
                if (rd["error"] == 0) {
                    confirmUserTransfer();
                } else {
                    var button = $("#btnTransferUserOwnership");
                    button.prop("disabled", false);
                    button.text("Submit");
                }
            } else if (ptt == "admin-user-transfer-confirmed") {
                var button = $("#btnTransferUserOwnership");
                button.prop("disabled", false);
                button.text("Submit");

                $("#frmTransferUser")
                    .find(":input")
                    .val("");
                $("#select4_sponsor")
                    .empty()
                    .trigger("change");
            } else if (ptt == "new-enrollment") {
                var btn = $("#btnEnroll");
                btn.text("Enroll User");
                btn.removeAttr("disabled");
                if (rd["error"] == 0) {
                    $("#btnPrevStep").hide();
                    $("#btnNextStep").hide();
                    $("#btnEnroll").hide();
                    $("#btnComplete").show();
                }
            } else if (ptt == "verify-voucher") {
                var btn = $("#btnVerifyVoucher");
                btn.text("Verify Voucher Code");
                btn.removeAttr("disabled");

                if (rd["error"] == 1) {
                    $("#voucherCodeInput")[0].setCustomValidity(rd["msg"]);
                } else {
                    $("#voucherCodeInput")[0].setCustomValidity("");
                }
            }
        }
    }

    function ajaxGet(url) {
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function(response) {
                if (response["error"] == 1) {
                    if ("msg" in response) {
                        errMsg(response["msg"]);
                    }
                } else if (response["error"] == 0) {
                    if ("msg" in response) {
                        okMsg(response["msg"]);
                    }
                }
            }
        });
    }

    function infoMsg(msg) {
        tostmsg("info", msg);
    }

    function warnMsg(msg) {
        tostmsg("warning", msg);
    }

    function errMsg(msg) {
        tostmsg("danger", msg);
    }

    function okMsg(msg) {
        tostmsg("success", msg);
    }

    function tostmsg(type, msg) {
        $.notify(msg, {
            type: type,
            allow_dismiss: true,
            delay: 8000,
            newest_on_top: true,
            z_index: 999999,
            placement: {
                from: "top",
                align: "center"
            },
            animate: {
                enter: "animated bounce",
                exit: "animated bounce"
            }
        });
    }

    function handlePaymentMethodClick(event) {
        event.preventDefault();
        var paymentId = $(event.target)
            .closest("#payment-method-options-inactive")
            .find(".form-check-input")
            .val();
        $.ajax({
            url: "/api/update/" + paymentId,
            type: "POST",
            data: { is_deleted: 0, primary: 0 },
            dataType: "JSON",
            success: function(data) {
                $(event.target)
                    .closest("#btn-payment-methods")
                    .hide();
                $(event.target)
                    .closest("#payment-method-options-inactive")
                    .removeClass("payment-method-options-inactive")
                    .addClass("payment-method-options-active");
                $(event.target)
                    .closest("#payment-method-options-inactive")
                    .find("#status-inactive")
                    .text("Status: Active");
                $(event.target)
                    .closest("#payment-method-options-inactive")
                    .find(".form-check-input")
                    .removeAttr("disabled");
            }
        });
    }

    function getRefundPopUpHTML() {
        return `
<form class="pt-3" style="background-color:#fbf7ff;" id="frmRefundOptions">
  <small>Please select your refund settings: </small>
  <div class="form-group row pt-3">
    <label for="inputEmail3" class="col-sm-6 col-form-label pt-0">Refund QV</label>
    <div class="form-row col-sm-6">
        <div class="col-sm-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="refund_qv" id="refundQV1" value="1">
              <label class="form-check-label" for="refundQV1">
                YES
              </label>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="refund_qv" id="refundQV2" value="0" checked>
              <label class="form-check-label" for="refundQV2">
                NO
              </label>
            </div>
        </div>
      </div>
  </div>
  <div class="form-group row">
    <label class="col-sm-6 col-form-label pt-0">Terminate User</label>
    <div class="form-row col-sm-6">
        <div class="col-sm-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="terminate_user" id="terminateUser1" value="1">
              <label class="form-check-label" for="terminateUser1">
                YES
              </label>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="terminate_user" id="terminateUser2" value="0" checked>
              <label class="form-check-label" for="terminateUser2">
                NO
              </label>
            </div>
        </div>
      </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-6 col-form-label pt-0">Suspend User</label>
    <div class="form-row col-sm-6">
        <div class="col-sm-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="suspend_user" id="suspendUser1" value="1">
              <label class="form-check-label" for="suspendUser1">
                YES
              </label>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="suspend_user" id="suspendUser2" value="0" checked>
              <label class="form-check-label" for="suspendUser2">
                NO
              </label>
            </div>
        </div>
      </div>
  </div>
</form>
`;
    }

    $("#volume-tab").on("click", function() {
        if ($("#volume-tab").hasClass("active")) {
            return;
        }

        $("#tsaCredits-tab").removeClass("active");
        $("#volume-tab").addClass("active");
        $(
            ".top-producing-block, .monthly-needed, .ri-summary-1 .summary-row, .subscriptions-types-wrap"
        ).toggleClass("active");
    });

    $("#tsaCredits-tab").on("click", function() {
        if ($("#tsaCredits-tab").hasClass("active")) {
            return;
        }

        $("#volume-tab").removeClass("active");
        $("#tsaCredits-tab").addClass("active");
        $(
            ".top-producing-block, .monthly-needed, .ri-summary-1 .summary-row, .subscriptions-types-wrap"
        ).toggleClass("active");
    });

    $("#replicatedPrefs").on("click", "#btnSavePreferences", function() {
        ajPost(
            $("#replicatedPrefs")
                .find(":input")
                .serialize(),
            "/replicating-preferences",
            "replicating-preferences"
        );
    });

    $("#btnSaveCountryPaymentMethod").click(function() {
        ajPost(
            $("#frmEditCountry")
                .find(":input")
                .serialize(),
            "/update-payment-method-country",
            "update-payment-method-country"
        );
    });

    $("#btnSaveSettingsCountry").click(function() {
        ajPost(
            $("#frmEditCountry")
                .find(":input")
                .serialize(),
            "/update-settings-method-country",
            "update-settings-country"
        );
    });

    $("#btnSaveMerchantPaymentTypeAndLimits").click(function() {
        ajPost(
            $("#frmEditMerchant")
                .find(":input")
                .serialize(),
            "/update-merchant",
            "update-merchant"
        );
    });

    $("#replicatedPrefs").on("click", "#btnCancelPreferences", function() {
        ajPost(
            $("#replicatedPrefs")
                .find(":input")
                .serialize(),
            "/replicating-preferences-reset",
            "replicating-preferences-reset"
        );
    });

    $("#btnConfirmTaxInformation").on("click", function() {
        ajPost(
            $("#frmTaxInfo")
                .find(":input")
                .serialize(),
            "/update-user-tax-info",
            "update-user-tax-info"
        );
    });

    $("#btnConfirmTaxInformationIntl").on("click", function() {
        ajPost(
            $("#frmTaxInfo")
                .find(":input")
                .serialize(),
            "/update-user-tax-info-international",
            "update-user-tax-info"
        );
    });

    $("#btnSignFormW8BEN").on("click", function() {
        window.location.href =
            "https://" + window.location.host + "/tax/get-fw8ben";
    });

    $("#btnSignFormW8BEN").on("click", function() {
        window.location.href =
            "https://" + window.location.host + "/tax/get-fw8ben";
    });

    return {
        init: function() {
            h_default();
            h_login();
            h_model();
            h_register();
            h_user();
            h_training();
            h_lead();
            h_report();
            h_promo();
            h_media();
            h_mail_templates();
            h_dashboard();
            h_boomerangs();
            h_order();
            h_orderItem();
            h_product();
            h_customer();
            h_discount();
            h_bulkEmail();
            h_ewallet();
            h_ewallet_csv();
            h_commission();
            h_update_history();
            h_subscription();
            h_api_token();
            h_binary_permission();
            h_binary_editor();
            h_upgrade_control();
            h_payout_control();
            h_subscription_reactivate();
            h_ambassador_reactivate();
            h_user_transfer();
            h_rank_settings();
            h_new_enrollment();
        }
    };
})();
