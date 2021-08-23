<?php
/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
//
//Route::get('/export-dist-spon', 'UserController@exportDistSpon');
//Route::get('/import-dist-spon', 'UserController@importDistSpon');
// all *.domain rules

Route::domain(env('FAQ_SUBDOMAIN'))->group(function ($router) {
    // TODO: need to be refactored
    Route::get('/', 'RedirectController@helpDesk');
});

Route::get('/agreements/terms-and-conditions', function () {
    return view('affiliate.agreement.terms-and-condition');
});

Route::get('/agreements/policies-and-procedures', function () {
    return view('affiliate.agreement.policies-and-procedures');
});

Route::get('/agreements/privacy-policy', function () {
    return view('affiliate.agreement.privacy-policy');
});


// dashboard
Route::get('/', 'DashboardController@index');
//ibuum foundation
Route::post('/ibuum-foundation', 'IbuumFoundation@ibuumFoundation');
Route::post('/checkout-foundation', 'IbuumFoundation@checkoutFoundation');
Route::post('/ibuum-foundation-checkout-card', 'IbuumFoundation@checkoutCardFoundation');
//
Route::post('/igo', 'DashboardController@iGo');
Route::post('/idecide', 'DashboardController@idecide');
Route::post('/chart-total-order-sum', 'DashboardController@getTotalOrderSumChart');
Route::post('/replicating-preferences', 'DashboardController@savePreferences');
Route::post('/replicating-preferences-reset', 'DashboardController@resetPreferences');
// login
Route::get('/login', 'LoginController@frmAffliateLogin')->name('login');
Route::get('/admin', 'LoginController@frmAdminLogin');
Route::post('/login', 'LoginController@affliateLogin');
Route::post('/admin-login', 'LoginController@adminLogin');
Route::get('/logout', 'LoginController@logout');
Route::get('/go-to-admin', 'LoginController@loginToAdminPanel');
Route::get('/forgot-password', 'LoginController@forgotPassword');
Route::post('/forgot-password', 'LoginController@sendPasswordResettingEmail');
Route::get('/reset-password/{token}', 'LoginController@frmResetPassword');
Route::post('/reset-password', 'LoginController@resetPassword');
Route::post('/login-to-igo4less', 'LoginController@login_iGo4Less');
Route::get('/2fa/{base64email}', 'LoginController@twoFAForm');
Route::post('/login-2fa', 'LoginController@twoFALogin');
// user
//Route::get('/question-list/{step}', 'UserController@showQuestionList');
//Route::get('/go-to-step/{next_prev}/{current_step}', 'UserController@goToQuestionList');
Route::get('/question-list', 'UserController@showQuestionList');
Route::post('/question-list-content/{currentStep}', 'UserController@getQuestionListContent');
Route::post('/question-list-complete', 'UserController@questionListCompleted');

Route::post('/vibe/agree', 'DashboardController@vibeAgreeForm');

Route::get('/payment-lookup', 'PaymentsController@getLookupPayment');
Route::post('/payment-lookup', 'PaymentsController@postLookupPayment');
Route::get('/payment-method/{id}/delete', 'PaymentsController@markAsDeleted')->name("user-payment-method-delete");
Route::get('/payment-lookup/{first}/{last}/{id?}/all-transactions', 'PaymentsController@getLookupPaymentAllTransaction')->name('payment-lookup-all-transactions');

/*
 * Upgrade packages
*/

Route::post('/upgrade-now', 'UpgradeController@upgradeNow');
Route::get('/upgrade-now/{package}', 'UpgradeController@dlgUpgradePackage');
Route::post('/check-coupon-code-upgrade', 'UpgradeController@checkCouponCodeUpgrade');
Route::post('/get-states', 'UserController@getStates');
Route::post('/upgrade-product-check-out', 'UpgradeController@upgradeProductCheckOut');
Route::post('/upgrade-package-check-out-new-card', 'UpgradeController@upgradeProductsCheckOutNewCard');
Route::post('/get-upgrade-countdown', 'UpgradeController@getUpgradeCountdown');
//bitpay
Route::post('/bitpay/callback', 'PaymentsController@bitPayCallBack'); //->middleware('cors');
Route::post('/bitpay/refund', 'PaymentsController@bitPayRefundCallBack'); //->middleware('cors');
//skrill
Route::post('/skrill/callback', 'PaymentsController@skrilCallback'); //->middleware('cors');
Route::post('/skrill/cancel', 'PaymentsController@skrilCancel'); //->middleware('cors');

/*
 * Ibuumerang pack purchase
 */
Route::get('/purchase-ibuumerang-pack/', 'IbuumerangController@dlgCheckOutIbuumerang');
Route::post('/ibuumerang-add-to-cart', 'IbuumerangController@ibuumerangPackAddToCart');
Route::get('/ibuumerang-add-to-cart', 'IbuumerangController@ibuumerangPackAddToCartBack');
Route::post('/ibuumerang-packs-check-out', 'IbuumerangController@ibuumerangPacksCheckOut');
Route::post('/ibuumerang-packs-check-out-new-card', 'IbuumerangController@ibuumerangPacksCheckOutNewCard');
Route::post('/check-coupon-code-ibuumerang', 'IbuumerangController@checkCouponCode');
//
//
Route::get('/change-password', 'UserController@frmChangePassword');
Route::post('/change-password', 'UserController@changePassword');
Route::get('/my-profile/{type?}', 'UserController@showMyProfile');
Route::get('/placement-preference', 'UserController@placementPreference');
Route::post('/save-profile', 'UserController@saveProfile');
Route::post('/save-placements', 'UserController@savePlacements');
Route::post('/save-primary-address', 'UserController@savePrimaryAddress');
Route::post('/save-billing-address', 'UserController@saveBillingAddress');
Route::post('/billing-add-new-card', 'UserController@billingAddNewCard');
//Route::get('/thank-you', 'UserController@registraionSuccess');

Route::get('/user/transfer', 'UserTransferController@showTransferUserForm');
Route::post('/user/transfer', 'UserTransferController@transferUser');
Route::get('/users/enroll', 'UserEnrollmentController@enrollForm');
Route::post('/users/enroll', 'UserEnrollmentController@enrollAction');
Route::post('/users/enroll/verify-voucher', 'UserEnrollmentController@verifyVoucher');
Route::get('/users/{type}', 'UserController@userList');

//Route::get('/toggle-approve/{user_id}', 'UserController@toggleApprove');
Route::get('/login-as-user/{distid}', 'UserController@loginAsUser');
//Route::get('/user/{user_id}', 'UserController@detail');
Route::get('/ambassador/{distid}', 'UserController@internDetail');
Route::get('/new-ambassador', 'UserController@frmNewIntern');
Route::post('/add-new-ambassador', 'UserController@addNewIntern');
Route::post('/update-ambassador', 'UserController@updateIntern');
Route::get('/verify-email/{email}/{verification_code}', 'UserController@verifyEmail');
Route::get('/dt-ambassador', 'UserController@getInternDataTable');
Route::get('/dt-terminated-users', 'UserController@getTerminatedUsersDataTable');
Route::get('/exp-ambassador/{sort_col}/{asc_desc}/{fr_by_en?}/{q?}', 'UserController@exportInternData');
Route::get('/dt-admin', 'UserController@getAdminDataTable');
Route::get('/dt-lead', 'UserController@getLeadDataTable');
Route::get('/exp-lead/{sort_col}/{asc_desc}/{q?}', 'UserController@exportLeadData');
Route::get('/all-ambassador', 'UserController@getAllIntern');
Route::get('/dt-enrolled-ambassador', 'UserController@getEnrolledInternDataTable');
Route::get('/enrollements/{distid}', 'UserController@showEnrollements');
Route::get('/auth-token-ambassador/{distid}', 'UserController@authTokenAmbassador');
Route::get('/dt-enrollements/{distid}', 'UserController@getEnrollementDataTable');
Route::get('/admin-user-edit/{userid}', 'UserController@frmEditAdminUser');
Route::post('/update-admin-user', 'UserController@updateAdminUser');
Route::post('/update-admin-user-login', 'UserController@updateAdminUserLogin');
Route::post('/update-cs-user', 'UserController@updateCSUser');
Route::post('/update-cs-user-login', 'UserController@updateCSUserLogin');
Route::get('/change-password-admin', 'UserController@frmChangePasswordAdmin');
Route::post('/change-password-admin', 'UserController@changePassword');
Route::get('/change-password-admin', 'UserController@frmChangePasswordAdmin');
Route::get('/new-admin', 'UserController@frmNewAdmin');
Route::post('/add-new-admin', 'UserController@addNewAdmin');
Route::post('/chart-enrollments-by-day', 'UserController@getEnrollmentsChart');
Route::post('/save-payap', 'UserController@savePayapMobile');
Route::post('/save-payap-ssn', 'UserController@savePayapSSN');
Route::post('/delete-payment-method', 'UserController@deletePaymentMethod');
Route::get('/upgrade-control', 'UserController@showUpgradeControl');
Route::post('/save-dist-expiry-date', 'UserController@saveCountdownExpiryDate');
Route::post('/save-dists-expiry-date', 'UserController@saveCountdownExpiryDateBulk');
Route::get('/active-override', 'UserController@frmactiveOverride');
Route::post('/active-override', 'UserController@activeOverride');
Route::post('/active-override-csv', 'UserController@activeOverrideCsvUpload');
Route::post('/save-subscription-product', 'UserController@saveSubscriptionProduct');
Route::get('/resend-welcome-email/{distid}', 'UserController@resendWelcomeEmail');
Route::get('/remove-from-mailgun/{distid}', 'UserController@removeFromMailgun');

// reports
Route::get('/reports/sales', 'ReportController@salesReport');
Route::get('/commission', 'ReportController@commissionReport');
Route::post('/commission/weekly', 'ReportController@commissionReportWeekly');
Route::post('/commission/weekly/details', 'ReportController@commissionReportWeeklyDetails');
//
//Tools
Route::get('/tools', 'ReportController@tools');
//Route::get('/tools/shop', 'PromoInfoController@showEventPromo');
Route::get('/tools/training', 'TrainingController@trainingVideoShop');
//
// Shop / Store and generic products
Route::get('/shop', 'ShopController@goToShop');
Route::get('/purchase-xccelerate-photobook/', 'ShopController@dlgCheckOutXcceleratePhotobook');
Route::get('/purchase-xccelerate-tools-eng/', 'ShopController@dlgCheckOutXccelerateSalesToolsEng');
Route::get('/purchase-xccelerate-tools-span/', 'ShopController@dlgCheckOutXccelerateSalesToolsSpan');
Route::get('/purchase-video-series/', 'ShopController@dlgCheckOutVideoSeries');
Route::post('/generic-check-out', 'ShopController@genericCheckOut');
Route::post('/generic-check-out-new-card', 'ShopController@genericCheckOutNewCard');
//
// tax and esign genie
Route::post('/update-user-tax-info/', 'UserController@updateUserTaxInfo');
Route::post('/update-user-tax-info-international/', 'UserController@updateUserTaxInfoInternational');
Route::get('/tax/get-fw8ben', 'EsignGenieController@getFw8ben');
Route::post('/esigngenie-callback', 'EsignGenieController@captureJsonFromEsignGenie');
// testing Routes below
Route::get('/esigngenie-callback', 'EsignGenieController@captureJsonFromEsignGenie');
//Route::post('/update-user-tax-flag/{tsa?}', 'UserController@updateUserTaxInfoInternational');



//
Route::get('/commission/volume', 'ReportController@commissionVolume');
Route::get('/report/volume-by-date-range/{from?}/{to?}/{type?}/{tsa?}', 'ReportController@calculateVolumes');
//
Route::get('/invoice', 'ReportController@invoice');
Route::get('/invoice/view/{orderId}', 'ReportController@viewInvoice');
Route::get('/invoice/download/{orderId}', 'ReportController@downloadInvoice');
Route::get('/report/erollments-by-date/{from?}/{to?}', 'ReportController@enrollmentsByDateList');
Route::get('/report/customer/enrollments-by-date', 'ReportController@enrollmentsByDateListForCustomers');
Route::get('/report/dt-enrollments-by-date', 'ReportController@getEnrollmentsByDateDataTable');
Route::get('/report/distributor-by-rank', 'ReportController@distributorByRankList');
Route::get('/report/dt-distributor-by-rank', 'ReportController@getDistributorByRankDataTable');
Route::get('/report/dlg-dist-by-rank/{rank}', 'ReportController@getDlgDistributorByRank');
Route::get('/report/dt-distributor-by-rank-detail/{rank}', 'ReportController@getDistributorByRankDetailDataTable');
Route::get('/report/highest-achieved-rank/{from?}/{to?}', 'ReportController@highestAchievedRankList');
Route::get('/report/dt-highest-achieved-rank', 'ReportController@getHighestAchievedRankDataTable');
Route::get('/report/sales-by-payment-method/{from?}/{to?}', 'ReportController@salesReportList');
Route::get('/report/dt-sales-by-payment-method', 'ReportController@getSalesByPaymentMethodDataTables');
// Route::get('/report/all-sapphires-by-country', 'ReportController@listSapphires');
Route::get('/report/dt-all-sapphires-by-country', 'ReportController@getSapphiresDataTable');
// Route::get('/report/all-diamonds-by-country', 'ReportController@listDiamonds');
Route::get('/report/dt-all-diamonds-by-country', 'ReportController@getDiamondsDataTable');
Route::get('/report/monthly-income-earnings', 'ReportController@listMonthlyEarnings');
Route::get('/report/dt-monthly-income-earnings', 'ReportController@getMonthlyEarningsDataTable');
Route::get('/report/monthly-top-recruiters', 'ReportController@listMonthlyTopRecruiters');
Route::get('/report/dt-monthly-top-recruiters', 'ReportController@getMonthlyTopRecruitersDataTable');
Route::get('/report/monthly-top-customers', 'ReportController@listMonthlyTopCustomers');
Route::get('/report/dt-monthly-top-customers', 'ReportController@getMonthlyTopCustomersDataTable');
Route::get('/report/pear-data/{id?}', 'ReportController@getPearReport');
Route::get('/report/pear/{id?}', 'ReportController@getPearReportByUser')->name('pear-report');
Route::get('/report/historical', 'ReportController@getHistoricalReport');

Route::get('/report/sales-by-country/{from?}/{to?}', 'ReportController@salesByCountryReportList');
Route::get('/report/dt-sales-by-country', 'ReportController@getSalesByCountryDataTable');
Route::get('/report/line-of-sponsorship', 'ReportController@losReport');
Route::post('/report/line-of-sponsorship', 'ReportController@getLosReport');


//Route::get('/report/enrollments', 'ReportController@enrollmentList');
Route::get('/report/enrollments/{distid}', 'ReportController@viewPersonallyEnrolledDistributors');
Route::get('/dt-personally-enrolled-detail/{distid}', 'ReportController@getEnrolledInternDataTable');
Route::get('/report/personally-enrolled', 'ReportController@personallyEnrolledReport');
Route::get('/entire-organization-report', 'ReportController@entireOrganizationReport');
Route::get('/weekly-enrollment-report', 'ReportController@weeklyEnrollmentReport');
Route::get('/weekly-binary-report/{from?}/{to?}', 'ReportController@weeklyBinaryReport');
Route::get('/dt-entire-organization-report', 'ReportController@getEntireOrganizationReportDataTable');
Route::get('/dt-weekly-enrollment-report', 'ReportController@weeklyEnrollmentReportDataTable');
Route::get('/dt-weekly-binary-view', 'ReportController@weeklyBinaryReportDataTable');
Route::get('/report/personally-enrolled/{package}', 'ReportController@personallyEnrolledByPackage');
Route::get('/report/dt-distributors-by-pack/{package}', 'ReportController@getPersonallyEnrolledByPackage');
Route::get('/dt-personal-enrollments', 'ReportController@getPersonallyEnrolledDistributorsDataTable');
Route::get('/exp-personal-enrollments/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportPersonallyEnrolledDistributors');
Route::get('/dt-vip-distributors', 'ReportController@getVipDistributorsDataTable');
Route::get('/exp-vip-distributors/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportVipDistData');
Route::get('/report/distributors_by_level', 'ReportController@distributorsByLevel');
Route::get('/report/distributors_by_level_detail/{level}', 'ReportController@distributorsByLevelDetail');
Route::get('/report/dt-distributors-by-level-detail/{level}/', 'ReportController@getDistributorsByLevelDetailDataTable');
Route::post('/report/org-drill-down/{distid}', 'ReportController@orgDrillDown');
Route::get('/report/dt-org-drill-down/{distid}', 'ReportController@getOrgDrillDownDataTable');
Route::get('/report/dt-dist-by-country', 'ReportController@getDistributorsByCountryDataTable');
Route::get('/report/dt-pre-enrollment-selection', 'ReportController@getPreEnrollmentSelectionDataTable');
Route::get('/report/idecide-and-sor/{from?}/{to?}', 'ReportController@idecideAndSor');
Route::get('/report/subscription-report/{from?}/{to?}', 'ReportController@subcsriptionReport');
Route::get('/report/dt-subscription-report', 'ReportController@getSubcsriptionReportDataTable');
Route::get('/report/subscription-by-payment-method/{from?}/{to?}', 'ReportController@subscriptionByPaymentMethod');
Route::get('/report/dt-subscription-by-payment-method', 'ReportController@getSubscriptionByPaymentMethodDataTable');
Route::get('/report/rank-advancement-report', 'ReportController@rankAdvancementList');
Route::get('/report/dt-rank-advancement-report', 'ReportController@GetRankAdvancementDataTable');
Route::get('/report/dt-fsb-commission-report', 'ReportController@getFsbCommissionDataTable');
Route::get('/report/exp-rank-advancement/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportRankAdvancement');
Route::get('/report/exp-sapphire-by-country/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportSapphiresByCountry');
Route::get('/report/exp-diamond-by-country/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportDiamondByCountry');
Route::get('/report/exp-dist-by-country/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportDistByCountry');
Route::get('/report/exp-sales-by-country/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportSalesByCountry');
Route::get('/report/exp-enrollments-by-date/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportEnrollmentsByDate');
Route::get('/report/exp-distributor-by-rank/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportDistributorByRank');
Route::get('/report/exp-distributor-by-rank-detail/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportDistributorByRankDetail');
Route::get('/report/exp-sales-by-payment-method/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportSalesByPaymentMethod');
Route::get('/report/exp-idecide-and-sor', 'ReportController@exportIDecideOrSor');
Route::get('/report/exp-subscription-report/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportSubscriptionReport');
Route::get('/report/exp-subscription-by-payment-method/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportSubscriptionByPaymentMethod');
Route::get('/report/exp-monthly-income-earnings/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportMonthlyEarning');
Route::get('/report/exp-monthly-top-recruiters/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportMonthlyTopRecruiters');
Route::get('/report/exp-monthly-top-customers/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportMonthlyTopCustomers');
Route::get('/report/subscription-history/', 'ReportController@subscriptionHistoryReport');
Route::get('/report/dt-subscription-history', 'ReportController@subscriptionHistoryDataTable');
Route::get('/report/exp-subscription-history/{sort_col}/{asc_desc}/{q?}', 'ReportController@exportSubscriptionHistory');
Route::get('/report/{type}', 'ReportController@adminReport');

// orders
Route::get('/orders/{from?}/{to?}', 'OrderController@ordersList');
Route::get('/dt-orders', 'OrderController@getOrdersDataTable');
Route::get('/exp-orders/{sort_col}/{asc_desc}/{q?}', 'OrderController@export');
Route::get('/add-order', 'OrderController@frmAdd');
Route::post('/create-order', 'OrderController@createOrder');
Route::get('/edit-order/{order_id}', 'OrderController@frmEdit');


//merchants payment processors
Route::get('/merchants', 'MerchLimitsController@merchantList');
Route::get('/dt_merchants', 'MerchLimitsController@getMerchantDataTable');
Route::get('/edit-merchant/{merchantId}', 'MerchLimitsController@editMerchant');
Route::post('/update-merchant', 'MerchLimitsController@updateMerchant');

Route::get('/chargeback/import', 'ChargebackController@importForm');
Route::post('/chargeback/import', 'ChargebackController@import');

Route::get('/chargeback/dataCsv', 'ChargebackController@dataCsv');
Route::get('/chargeback/manage', 'ChargebackController@manage');

Route::get('/chargeback/merchants', 'ChargebackController@merchants');

//countries
Route::get('/countries', 'CountryController@countryList');
Route::get('/dt-countries', 'CountryController@getCountriesDataTable');
Route::get('/add-country', 'CountryController@frmAdd');
Route::post('/create-country', 'CountryController@createCountry');
Route::get('/delete-country/{countryId}', 'CountryController@deleteCountry');
Route::get('/edit-country/{countryId}', 'CountryController@editCountry');
Route::post('/update-payment-method-country', 'CountryController@updatePaymentMethodForCountry');
Route::get('searchajax', array('as' => 'searchajax', 'uses' => 'CountryController@autoComplete'));
Route::get('/countries/json', function () {
    return response()->json(\App\Country::getAll());
});


//countries
Route::get('/settings/countries', 'CountrySettingController@countryList');
Route::get('/settings/dt-countries', 'CountrySettingController@getCountriesDataTable');
Route::get('/edit-settings-country/{countryId}', 'CountrySettingController@editCountry');
Route::post('/update-settings-method-country', 'CountrySettingController@updateSettingsCountry');

// Json of states
Route::get('/states/json/{country_code}', function ($country_code) {
    return response()->json(
        \DB::table('states')
            ->where('country_code', '=', $country_code)
            ->get()
    );
});

// Payout control
Route::get('/payout-control', 'CountryController@payoutControl');
Route::get('/dt-payout-control', 'CountryController@dtPayoutControl');
Route::get('/edit-payout/{id}', 'CountryController@editPayout');
Route::post('/update-payout-method', 'CountryController@updatePayout');
Route::post('/payout-control-set-default', 'CountryController@updatePayoutDefault');
//Route::post('/transfer-to-ipayout', 'EwalletTransactionController@transferToIPayOut');
Route::post('/ipayout-account-setup', 'EwalletTransactionController@setupIpayOutAccount');
Route::post('/vitals', 'EwalletTransactionController@vitalsSubmit');
Route::post('/sub-tfa', 'EwalletTransactionController@submitTFA');
Route::post('/resend-tfa', 'EwalletTransactionController@resendTFA');
//
Route::get('/batch-order-refund', 'RefundController@refundBatchForm');
Route::post('/batch-order-refund', 'RefundController@refundBatch');
Route::get('/refund-order/{order_id}', 'RefundController@refundOrder');
Route::post('/refund-order/', 'RefundController@refundOrder');
Route::post('/refund-order-item', 'RefundController@refundOrderItem');
//
Route::post('/upgrade-order', 'OrderController@updateOrder');
// order item
Route::get('/update-order-item/{rec_id}', 'OrderItemController@dlgUpdate');
Route::post('/upgrade-order-item', 'OrderItemController@updateRec');
Route::get('/add-new-order-item/{order_id}', 'OrderItemController@dlgNew');
Route::post('/add-new-order-item', 'OrderItemController@addRec');
// promo info
Route::get('/promo-info', 'PromoInfoController@configPage');
Route::post('/validate-promo', 'PromoInfoController@validatePromoRec');
Route::post('/save-promo', 'PromoInfoController@savePromo');
Route::get('/new-promo', 'PromoInfoController@showPromo');
// media
Route::get('/media', 'MediaController@adminIndex');
Route::get('/new-media', 'MediaController@frmNew');
Route::post('/validate-media', 'MediaController@validateMediaRec');
Route::post('/save-media', 'MediaController@saveMedia');
Route::get('/media-edit/{rec_id}', 'MediaController@frmEdit');
Route::post('/update-media', 'MediaController@updateMedia');
Route::get('/dt-media', 'MediaController@getDataTable');
Route::post('/media-vid-view', 'MediaController@getVideoView');
Route::get('/view-video/{rec_id}', 'MediaController@viewVideo');
Route::post('/media-img-view', 'MediaController@getImageView');
Route::post('/media-doc-view', 'MediaController@getDocView');
Route::post('/media-pres-view', 'MediaController@getPresentationView');
Route::get('/download-media/{file_name}', 'MediaController@downloadMedia');
//
Route::get('/email-templates', 'MailTemplateController@index');
Route::get('/install-email-templates', 'MailTemplateController@install');
Route::get('/dt-mail-templates', 'MailTemplateController@getDataTable');
Route::get('/edit-mail-template/{rec_id}', 'MailTemplateController@frmEdit');
Route::post('/save-mail-template', 'MailTemplateController@saveRec');
Route::get('/test-email', 'MailTemplateController@testMail');
//product
Route::get('/product/{type}', 'ProductController@adminProduct');
Route::get('/product/detail/{id}', 'ProductController@productDetail');
Route::get('/dt-products', 'ProductController@getProductsDataTable');
Route::post('/update-product', 'ProductController@updateProduct');
Route::get('/new-product', 'ProductController@frmNewProduct');
Route::post('/add-new-product', 'ProductController@addNewProduct');

// customers
Route::post('/save-customer', 'CustomerController@saveRec');
Route::get('/test-save-on', 'CustomerController@testSaveOn');
Route::get('/exp-customers/{sort_col}/{asc_desc}/{q?}', 'CustomerController@exportCustomersData');
Route::get('/dist-customers', 'CustomerController@customerList');
Route::get('/dt-customers', 'CustomerController@getCustomersDataTable');
Route::get('/customers', 'CustomerController@distCustomersList');
Route::get('/dt-dist-customers', 'CustomerController@getDistCustomersDataTable');
Route::get('/customer/edit-customer/{id}', 'CustomerController@frmEditCustomer');
Route::post('update-customers', 'CustomerController@updateCustomer');
Route::get('/customer/set-customer-id', 'CustomerController@setCustomerId');
// transaction
Route::get('/dt-admin-report-sales', 'TransactionController@getAdminReportData');
Route::get('/exp-admin-report-sales/{sort_col}/{asc_desc}/{q?}', 'TransactionController@exportAdminReportData');
Route::get('/transaction-detail/{rec_id}', 'TransactionController@viewDetail');
Route::get('/dt-sales-report', 'TransactionController@getTransactionByIntern');
// boomerangs inv
Route::post('/set-new-boom-total', 'BoomerangInvController@setNewTotal');
Route::post('/set-max-boom-available', 'BoomerangInvController@setMaxBoomAvailable');
Route::get('/boomerang-instructions', 'BoomerangInvController@dlgInstructions');
// boomerang tracker
Route::post('/gen-boom-ind', 'BoomerangTrackerController@generateInd');
Route::post('/gen-boom-group', 'BoomerangTrackerController@generateGroup');
Route::get('/individual-boomerangs', 'BoomerangTrackerController@indexInd');
Route::get('/group-boomerangs', 'BoomerangTrackerController@indexGroup');
Route::get('/dt-boomerangs-ind', 'BoomerangTrackerController@getInternDataTableInd');
Route::get('/dt-boomerangs-group', 'BoomerangTrackerController@getInternDataTableGroup');
Route::get('/validate-boomerang/{code}', 'BoomerangTrackerController@validateCode');
Route::get('/boomerang/{type}', 'BoomerangTrackerController@boomerangList');
Route::get('/dt-leads-ind', 'BoomerangTrackerController@getLeadIndividualDataTable');
Route::get('/dt-leads-grp', 'BoomerangTrackerController@getLeadGroupDataTable');
Route::get('/exp-admin-leads-grp/{sort_col}/{asc_desc}/{q?}', 'BoomerangTrackerController@exportLeadsGroupData');
Route::get('/exp-admin-leads-ind/{sort_col}/{asc_desc}/{q?}', 'BoomerangTrackerController@exportLeadsIndividualData');
Route::get('/expired-to-inventory', 'BoomerangTrackerController@addExpiredToInventory');
Route::post('/chart-boomerangs-by-day', 'BoomerangTrackerController@getBoomerangsSentChart');
Route::post('/boom-send-sms', 'BoomerangTrackerController@sendSMS');
Route::post('/boom-send-mail', 'BoomerangTrackerController@sendMail');
// discount
Route::get('/discount-coupons', 'DiscountCouponController@index');
Route::get('/new-discount-coupon', 'DiscountCouponController@frmNew');
Route::post('/add-new-coupon', 'DiscountCouponController@addNew');
Route::get('/dt-discounts', 'DiscountCouponController@getRecs');
Route::get('/toggle-discount-active/{recId}', 'DiscountCouponController@toggleActive');
Route::get('/delete-discount-code/{recId}', 'DiscountCouponController@deleteDiscountCode');
Route::get('/refund-voucher', 'DiscountCouponController@refundVoucher');
Route::post('/refund-voucher', 'DiscountCouponController@refundVoucher');

//
Route::get('/add-new-voucher-code', 'DiscountCouponController@addNewDiscountCoupon');

// Save On
Route::post('/save-on-transfer', 'SaveOnController@transfer');
Route::post('/create-save-on-account', 'SaveOnController@createNewAccountByUser');
Route::post('/sor-toggle-status', 'SaveOnController@toggleStatus');
//
// idecide
Route::post('/create-idecide', 'iDecideController@createNewAccount');
Route::post('/create-idecide-account', 'iDecideController@createNewAccountByUser');
Route::post('/create-tv-idecide', 'iDecideController@createForTV');
Route::post('/reset-idecide-password', 'iDecideController@resetPassword');
Route::post('/reset-idecide-mail', 'iDecideController@resetEmail');
Route::get('/sync-idecide', 'iDecideController@syncIdecide');
Route::post('/idecide-toggle-status', 'iDecideController@toggleStatus');
//
// bulk email
Route::get('/bulk-email', 'BulkEmailController@index');
Route::get('/send-new-bulk-email', 'BulkEmailController@frmNew');
Route::post('/send-bulk-mail', 'BulkEmailController@send');
Route::get('/view-bulk-email/{rec_id}', 'BulkEmailController@viewMail');
//Route::get('/edit-bulk-email/{rec_id}', 'BulkEmailController@frmEdit');
//Route::post('/update-bulk-mail', 'BulkEmailController@updateRec');
//Route::get('/toggle-bulk-email/{rec_id}', 'BulkEmailController@toggle');
//Route::get('/run-bulk-email', 'BulkEmailController@run');

// e-wallet transaction
Route::get('/e-wallet', 'EwalletTransactionController@index');
Route::get('/commission/pending', 'EwalletTransactionController@pendingList');
Route::get('/dt-ewallet-transactions', 'EwalletTransactionController@getPendingDataTable');
Route::post('/transfer-now', 'EwalletTransactionController@transfer');
Route::post('/transfer-to-payap', 'EwalletTransactionController@transferToPayap');
Route::get('/dlg-transfer-history', 'EwalletTransactionController@dlgTranferHistory');
Route::get('/dt-dlg-transfer-history', 'EwalletTransactionController@getTransferHistoryDataTable');
Route::get('/commission/withdrawals/{from?}/{to?}', 'EwalletTransactionController@withdrawalsList');
Route::get('/commission/dt-withdrawals', 'EwalletTransactionController@getWithdrawalsDataTables');
Route::get('/1099/{filename}', 'EwalletTransactionController@download1099');
//
//subscription
Route::get('/subscription-reactivate', 'UserController@frmSubscriptionReactivate');
Route::post('/subscription-reactivate', 'UserController@subscriptionReactivate');
Route::get('/subscription', 'SubscriptionController@index');
Route::post('/subscription', 'SubscriptionController@saveSubscription');
Route::get('/get-grace-period', 'SubscriptionController@getGracePeriod');
Route::get('/dlg-add-new-card', 'SubscriptionController@dlgAddNewCard');
Route::post('/add-new-card-subscription', 'SubscriptionController@addNewCard');
Route::get('/dlg-subscription-reactivate', 'SubscriptionController@dlgSubscriptionReactivate');
Route::get('/dlg-subscription-reactivate-suspended-user', 'SubscriptionController@dlgSubscriptionReactivateSuspendedUser');
Route::post('/reactivate-subscription', 'SubscriptionController@reactivateSubscription');
Route::post('/reactivate-suspended-subscription', 'SubscriptionController@reactivateSubscriptionSuspendedUser');
Route::post('/reactivate-suspended-subscription-add-coupon-code', 'SubscriptionController@checkCouponCodeforSuspendedUser');
Route::post('/reactivate-subscription-add-coupon-code', 'SubscriptionController@checkCouponCode');
Route::post('/reactivate-subscription-add-suspended-user-coupon-code', 'SubscriptionController@checkCouponCode');
Route::post('/add-new-card-subscription-reactivate', 'SubscriptionController@addNewCardSubscriptionReactivate');
Route::post('/add-new-card-subscription-reactivate-suspended-user', 'SubscriptionController@addNewCardSubscriptionSuspendedUserReactivate');
Route::get('/subscription-details/users/{subscriptionType}/{sponsorId}', 'SubscriptionController@subscriptionUserDetails')
    ->where(['subscriptionType' => 'standby|tier-coach|traverus-gf|standard', 'sponsorId' => '[0-9]+'])->name('subscription-details');
Route::get('/subscription-details-table', 'SubscriptionController@ajaxSubscriptionUserDetails');

// ambassador
Route::get('/ambassador-reactivate', 'UserController@frmAmbassadorReactivate');
Route::post('/ambassador-reactivate', 'UserController@ambassadorReactivate');
//
// binary viewer
Route::get('/binary-viewer/{id?}', 'BinaryViewerController@index')->name('binaryViewer');
Route::post('/binary-viewer/search', 'BinaryViewerController@getAjaxDistributors');
Route::post('/binary-viewer/init-search', 'BinaryViewerController@getInitSearchDistributors');
//
// binary permission
Route::get('/binary-permission', 'BinaryPermissionController@index');
Route::post('/save-binary-permission', 'BinaryPermissionController@saveRec');
//
// binary tree editor
Route::get('/binary-tree-editor', 'BinaryTreeEditorController@index');
Route::get('/binary-tree-editor/replace', 'BinaryTreeEditorController@getAllExistingRecs');
Route::get('/binary-tree-editor/replace-with', 'BinaryTreeEditorController@getAllActiveDistributors');
Route::post('/binary-tree-editor/replace', 'BinaryTreeEditorController@replace');
Route::post('/binary-tree-editor/search', 'BinaryTreeEditorController@search');
//
// holding tank routes
// Route::get('/placement-lounge', 'HoldingTankController@index');
// Route::get('/placement-lounge/distributors', 'HoldingTankController@getAjaxDistributorsData');
// Route::post('/placement-lounge/distributors/place', 'HoldingTankController@postAjaxDistributors');
//
//ticket purchase
Route::post('/checkout-ticket-purchased', 'Ticket@checkoutPaymentMethod');
Route::get('/check-ticket-purchased', 'Ticket@checkPurchase');
Route::post('/skip-ticket-confirm', 'Ticket@skipPurchaseConfim');
Route::post('/skip-ticket-purchased', 'Ticket@skipPurchase');
Route::post('/ticket-check-out', 'Ticket@ticketPacksCheckOut');
Route::post('/check-coupon-code-ticket', 'Ticket@checkCouponCode');

Route::get('/purchase-ticket-pack/', 'Ticket@dlgCheckOutTicket');
Route::post('/ticket-packs-check-out-new-card', 'Ticket@ticketCheckOutNewCard');
//
//event ticket
Route::post('/checkout-events-ticket-purchased', 'TicketController@checkoutPaymentMethod');
Route::get('/check-events-ticket-purchased', 'TicketController@checkPurchase');
Route::post('/events-ticket-check-out', 'TicketController@ticketPacksCheckOut');
Route::post('/check-coupon-code-events-ticket', 'TicketController@checkCouponCode');
Route::post('/events-ticket-packs-check-out-new-card', 'TicketController@ticketCheckOutNewCard');

Route::get('/purchase-events-ticket-pack/', 'TicketController@dlgCheckOutTicket');
//Route::post('/purchase-events-ticket-pack/', 'TicketController@dlgCheckOutTicket');
//
// ewallet csv
Route::get('/commission/transfered', 'EwalletCSVController@trasferedList');
Route::get('/dt-ewallet-csv', 'EwalletCSVController@getTransferedDataTable');
Route::get('/download-csv/{rec_id}', 'EwalletCSVController@downloadCSV');
//
// commission
Route::get('/commission-engine', 'CommissionController@showEngine');
Route::get('/commission-summary', 'CommissionController@showCommission_summary');
Route::get('/tsb-commission-summary', 'CommissionController@showTsbCommissionSummary');
Route::post('/tsb-commission-post', 'CommissionController@tsbCommissionImportCsv');
Route::get('/commission-detail', 'CommissionController@showCommission_detail');
Route::get('/tsb-commission-detail', 'CommissionController@showTsbCommissionDetail');
Route::get('/commission-detail-post', 'CommissionController@showCommission_post');
Route::post('/run-commission', 'CommissionController@run');
Route::post('/tsb-commission', 'CommissionController@TsbComissionRun');
Route::get('/dt-commission-summary', 'CommissionController@getSummaryDataTable');
Route::get('/dt-tsb-commission-summary', 'CommissionController@getTsbSummaryDataTable');
Route::get('/dt-commission-detail', 'CommissionController@getDetailDataTable');
Route::get('/dt-tsb-commission-detail', 'CommissionController@getTsbDetailDataTable');
Route::get('/dt-commission-detail-post', 'CommissionController@getPostDetailDataTable');
Route::post('/approve-commission', 'CommissionController@approve');
Route::post('/approve-tsb-commission', 'CommissionController@tsbCommissionApprove');
Route::post('/post-commission', 'CommissionController@post');
Route::post('/post-tsb-commission', 'CommissionController@tsbCommissionPost');
Route::get('/exp-commission-summary/{sort_col}/{asc_desc}/{q?}', 'CommissionController@expCommissionSummary');
Route::get('/exp-tsb-commission-summary/{sort_col}/{asc_desc}/{q?}', 'CommissionController@expTsbCommissionSummary');
Route::get('/exp-commission-detail/{sort_col}/{asc_desc}/{q?}', 'CommissionController@expCommissionDetail');
Route::get('/exp-tsb-commission-detail/{sort_col}/{asc_desc}/{q?}', 'CommissionController@expTsbCommissionDetail');
Route::get('/approved-commission', 'CommissionController@approvedCommission');
Route::get('/adjustments', 'CommissionController@adjustmentsView');
Route::post('/adjustments', 'CommissionController@adjustments');
Route::get('/search-approved-commission', 'CommissionController@searchApprovedCommission');
Route::get('/approved-commission-detail', 'CommissionController@approvedCommissionDetail');
Route::get('/dt-approved-commission-detail', 'CommissionController@getApprovedDetailDataTable');
Route::get('/approved-commission-summary', 'CommissionController@approvedCommissionSummary');
Route::get('/dt-approved-commission-summary', 'CommissionController@getApprovedSummaryDataTable');
Route::get('/select2-approved-commission-dates', 'CommissionController@getApprovedCommissionDates');
Route::post('/unilevel-commission', 'CommissionController@calculateUnilevelCommission');
Route::get('/unilevel-commission-details', 'ReportController@unilevelCommissionDetails');
Route::post('/leadership-commission', 'CommissionController@calculateLeadershipCommission');
Route::get('leadership-commission-details', 'ReportController@leadershipCommissionDetails');
Route::get('tsb-commission-details', 'ReportController@tsbCommissionDetails');
Route::post('/order-details', 'ReportController@orderDetails');
Route::get('/commission/importTsb', 'CommissionController@showImportTsbForm');
Route::post('/commission/importTsb', 'CommissionController@importTsbFile');
Route::get('/commission/importVibe', 'CommissionController@showImportVibeForm');
Route::post('/commission/importVibe', 'CommissionController@importVibeFile');
//
// update-history
Route::get('/update-history/{type}', 'UpdateHistoryController@listOrdersHistory');
Route::get('/dlg-update-history/{type}/{id}', 'UpdateHistoryController@dlgUpdateHistory');
Route::get('/dt-update-history/{type}', 'UpdateHistoryController@getOrdersHistoryDateTable');
Route::get('/dt-dlg-update-history', 'UpdateHistoryController@getUpdateHistoryDataTable');
//
// ranks
Route::post('/get-rank-values', 'UserRankHistoryController@getRankValues');
Route::post('/get-bs-this-month', 'UserRankHistoryController@getBSThisMonth');
Route::post('/get-bs-last-month', 'UserRankHistoryController@getBSLastMonth');
Route::get('/cron-calculate-downline-qv', 'UserRankHistoryController@cron_calculateDownlineQV');
//
// cron jobs
Route::get('/cron/{type}', 'CronJobController@run');
//field watch export
Route::get('/export-field-watch', 'ExportController@fieldWatchExport');
//subscription  process;
Route::get('/subscription/cron/{userID?}/{param2?}', "SubscriptionAlertController@RunCronProcess");
Route::get('/manual-subscription/cron/{date?}/{distid?}', "SubscriptionAlertController@RunCronManual");
//
// mailgun mail list
//Route::get( '/mail-gun/add-new-list', "MailGunMailListController@addNewList");
//Route::get( '/mail-gun/add-new-mail', "MailGunMailListController@addNewMail");
Route::get('/mail-gun/add-bulk', "MailGunMailListController@addBulk");
Route::get('/mail-gun/update-bulk', "MailGunMailListController@updateBulk");
// api token
Route::get('/api-token', 'ApiTokenController@index');
Route::get('/new-api-token', 'ApiTokenController@generateNewToken');
Route::get('/api-token-toggle-active/{recId}', 'ApiTokenController@toggleActive');
// api request history
Route::get('/api-request-history', 'ApiRequestController@index');
Route::get('/dt-api-requests', 'ApiRequestController@getDataTable');
// api
Route::get('/api/{type}', 'ApiController@run');

// settings
Route::get('/settings/ranks', 'JenkinsController@ranksIndex');
Route::post('/settings/ranks', 'JenkinsController@ranksPost');
Route::get('/settings/credentials', 'CredentialsController@index');
Route::post('/settings/credentials', 'CredentialsController@store');
Route::get('/settings/{setting}', 'SiteSettingsController@index');
Route::post('/settings/{setting}', 'SiteSettingsController@index');

// binary modification
// Route::get('/binary-modification/{type}', 'BinaryModificationController@index');

// Route::post('/binary-modification/insert/agent', 'BinaryModificationController@getAjaxAgentTsa');
// Route::post('/binary-modification/insert/parent', 'BinaryModificationController@getAjaxParentTsa');
// Route::post('/binary-modification/insert/execute', 'BinaryModificationController@postAjaxInsertLeg');

// Route::post('/binary-modification/move/agent', 'BinaryModificationController@getAjaxNodeInBinaryTree');
// Route::post('/binary-modification/move/execute', 'BinaryModificationController@postAjaxMoveLeg');

// Route::post('/binary-modification/replace/agent', 'BinaryModificationController@getAjaxAgentReplaceTsa');
// Route::post('/binary-modification/replace/parent', 'BinaryModificationController@getAjaxNodeInBinaryTree');
// Route::post('/binary-modification/replace/execute', 'BinaryModificationController@postAjaxReplaceLeg');

// Route::post('/binary-modification/terminate/agent', 'BinaryModificationController@getAjaxNodeInBinaryTree');
// Route::post('/binary-modification/terminate/execute', 'BinaryModificationController@postAjaxTerminateAgent');

Route::get('/checks-user-data', 'UserController@checksUserData');
Route::post('/saves-user-data', 'UserController@savesUserData');
Route::get('/doc-is-signed', function () {
    $statusCode = 500;
    $response = [
        'isSigned' => false,
        'message' => 'You need to sign the document.'
    ];

    $hasDoc = \DB::table('user_assets')->where('user_id', '=', Auth::id())->first();

    if (!is_null($hasDoc)) {
        $statusCode = 200;
        $response['isSigned'] = true;
        $response['message'] = '';
    }

    return response()->json($response, $statusCode);
});

// commission control center
Route::group(
    [
        'prefix' => 'commission-control-center',
        'middleware' => 'check.commission.start_date'
    ],
    function () {
        Route::get('{type}', 'CommissionControlCenter@index')->name('CommissionControlIndex');
        Route::post('commission-period', 'CommissionControlCenter@commissionPeriod')->name('commissionPeriod');
        Route::post('calculate-commission', 'CommissionControlCenter@calculate')->name('calculateCommission');
        Route::post('commission-details', 'CommissionControlCenter@commissionSummary')->name('commissionSummary');
        Route::post('post-commission', 'CommissionControlCenter@post')->name('postCommission');
        Route::post('payout-commission', 'CommissionControlCenter@payout')->name('payoutCommission');
    }
);

// twilio authy
Route::post('/authy-toggle', 'TwilioAuthyController@toggle');

Route::get('/user-info', 'UserController@getUserInfo');

//secure endpoint server for SOR outbound call
Route::post('/sor-reservations', 'SORReservations@sorCommission');
// 404
Route::get('/{param1?}/{param2?}/{param3?}/{param4?}/{param5?}', 'DashboardController@pageNotFound')->name('page404');

// buumerang products
Route::get('/buumerang_products/{type?}/{id}', 'BoomerangProductController@showBuumerangs');
Route::get('/buumerang_products/individual_buumerang/{id}', 'BoomerangProductController@individualBuumerang');
Route::get('/buumerang_products/group_buumerang/{id}', 'BoomerangProductController@groupBuumerang');

Route::post('/authy/request', 'UserTransferController@twoFactorAuthRequest');
Route::post('/authy/verify', 'UserTransferController@twoFactorAuthVerify');

# Ticket System
Route::middleware(['auth'])->group(function () {

    Route::get('tickets/data/{id?}', 'TicketSystemController@data')->name('tickets.data');

    Route::resource('tickets', 'TicketSystemController', [
        'names' => [
            'index'   => 'tickets.index',
            'store'   => 'tickets.store',
            'create'  => 'tickets.create',
            'update'  => 'tickets.update',
            'show'    => 'tickets.show',
            'destroy' => 'tickets.destroy',
            'edit'    => 'tickets.edit',
        ],
        'parameters' => [
            'tickets' => 'ticket',
        ],
    ]);
});
