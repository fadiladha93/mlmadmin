<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Media
 *
 * @property int $id
 * @property string|null $display_name
 * @property string|null $file_name
 * @property string|null $extension
 * @property string|null $category
 * @property string|null $external_url
 * @property int|null $order_no
 * @property int|null $is_active
 * @property int|null $is_external
 * @property int|null $is_downloadable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereExternalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereIsDownloadable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereIsExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Media whereOrderNo($value)
 */
	class Media extends \Eloquent {}
}

namespace App{
/**
 * App\PreEnrollmentSelection
 *
 * @property int $id
 * @property int $userId
 * @property int $productId
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $idecide_user
 * @property int|null $saveon_user
 * @property int|null $is_processed
 * @property int|null $is_process_success
 * @property string|null $process_msg
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereIdecideUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereIsProcessSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereIsProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereProcessMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereSaveonUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PreEnrollmentSelection whereUserId($value)
 */
	class PreEnrollmentSelection extends \Eloquent {}
}

namespace App{
/**
 * App\IQCredits
 *
 * @property string|null $legacyid
 * @property float|null $credit_amt
 * @property int|null $bv
 * @property string|null $date_used
 * @property int|null $is_used
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IQCredits newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IQCredits newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IQCredits query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IQCredits whereBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IQCredits whereCreditAmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IQCredits whereDateUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IQCredits whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IQCredits whereLegacyid($value)
 */
	class IQCredits extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property string|null $firstname
 * @property string|null $mi
 * @property string|null $lastname
 * @property string|null $email
 * @property string|null $phonenumber
 * @property string|null $username
 * @property string|null $refname
 * @property string|null $distid
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $usertype
 * @property int|null $statuscode
 * @property string|null $sponsorid
 * @property string|null $legacyid
 * @property int|null $deleted
 * @property string|null $mobilenumber
 * @property int|null $is_business
 * @property string|null $business_name
 * @property string|null $ssn
 * @property string|null $fid
 * @property int|null $founder
 * @property string|null $password
 * @property string|null $account_status
 * @property int|null $email_verified
 * @property int|null $entered_by
 * @property int|null $basic_info_updated
 * @property string|null $remember_token
 * @property int $id
 * @property string|null $default_password
 * @property string|null $created_date
 * @property string|null $created_time
 * @property int|null $current_product_id
 * @property int|null $is_tv_user
 * @property float|null $available_balance
 * @property float|null $estimated_balance
 * @property string|null $payap_mobile
 * @property int|null $admin_role
 * @property int|null $current_month_qv
 * @property int|null $current_month_rank
 * @property string|null $co_applicant_name
 * @property string|null $country_code
 * @property string|null $display_name
 * @property string|null $recognition_name
 * @property string|null $phone_country_code
 * @property string|null $original_subscription_date
 * @property int|null $subscription_payment_method_id
 * @property string|null $next_subscription_date
 * @property int|null $gflag
 * @property string|null $remarks
 * @property int|null $payment_fail_count
 * @property int|null $subscription_attempts
 * @property int|null $sync_with_mailgun
 * @property int|null $is_sites_deactivate
 * @property int|null $is_cron_fail
 * @property int $current_month_pqv
 * @property string|null $created_dt
 * @property float $current_left_carryover
 * @property float $current_right_carryover
 * @property int $current_month_tsa
 * @property string|null $coundown_expire_on
 * @property string|null $binary_placement
 * @property string|null $beneficiary
 * @property int|null $secondary_auth_enabled
 * @property int|null $authy_id
 * @property int|null $is_active
 * @property int|null $current_month_cv
 * @property int|null $binary_q_l
 * @property int|null $binary_q_r
 * @property int $is_activate
 * @property string|null $subscription_remarks
 * @property int $is_bc_active
 * @property int|null $level
 * @property int|null $subscription_product
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserActivityHistory[] $activity
 * @property-read int|null $activity_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BinaryCommission[] $binaryCommissions
 * @property-read int|null $binary_commissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BinaryCommissionCarryoverHistory[] $carryovers
 * @property-read int|null $carryovers_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Product $product
 * @property-read \App\RankDefinition $rankDefinition
 * @property-read \App\Models\ReplicatedPreferences $replicatedPreferences
 * @property-read \App\User $sponsor
 * @property-read \App\Models\UserStatistic $userStatistic
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAccountStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAdminRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAuthyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvailableBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBasicInfoUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBeneficiary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBinaryPlacement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBinaryQL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBinaryQR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCoApplicantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCoundownExpireOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedDt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentLeftCarryover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentMonthCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentMonthPqv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentMonthQv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentMonthRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentMonthTsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCurrentRightCarryover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDefaultPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDistid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEnteredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEstimatedBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFounder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsActivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsBcActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsBusiness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsCronFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsSitesDeactivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIsTvUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLegacyid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMobilenumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNextSubscriptionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOriginalSubscriptionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePayapMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePaymentFailCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhonenumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRecognitionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRefname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSecondaryAuthEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSponsorid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatuscode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSubscriptionAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSubscriptionPaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSubscriptionProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSubscriptionRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSyncWithMailgun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsertype($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\PaymentMethod
 *
 * @property int|null $userID
 * @property int|null $primary
 * @property int|null $deleted
 * @property string|null $token
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $cvv
 * @property string|null $expMonth
 * @property string|null $expYear
 * @property string|null $firstname
 * @property string|null $lastname
 * @property int|null $bill_addr_id
 * @property int|null $pay_method_type
 * @property int|null $is_subscription
 * @property int|null $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereBillAddrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereCvv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereExpMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereExpYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereIsSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod wherePayMethodType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod wherePrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethod whereUserID($value)
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * Model for Site Settings
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int $dist_id
 * @property int $rank_id
 * @property int $level
 * @property float $percent
 * @property float $amount
 * @property int|null $order_id
 * @property string $calculation_date
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property-read \App\OrderItem|null $order
 * @property-read \App\User $sourceUser
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereCalculationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereDistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LeadershipCommission whereUserId($value)
 */
	class LeadershipCommission extends \Eloquent {}
}

namespace App\Models{
/**
 * Model for Site Settings
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string|null $business_name
 * @property string|null $displayed_name
 * @property int $show_name
 * @property string|null $phone
 * @property bool $show_phone
 * @property string|null $email
 * @property bool $show_email
 * @property string|null $co_name
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereCoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereDisplayedName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereShowEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereShowName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereShowPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ReplicatedPreferences whereUserId($value)
 */
	class ReplicatedPreferences extends \Eloquent {}
}

namespace App\Models{
/**
 * Model for Site Settings
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int $dist_id
 * @property int $rank_id
 * @property int $level
 * @property float $percent
 * @property float $amount
 * @property int|null $order_id
 * @property string $calculation_date
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property-read \App\OrderItem|null $order
 * @property-read \App\User $sourceUser
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereCalculationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereDistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UnilevelCommission whereUserId($value)
 */
	class UnilevelCommission extends \Eloquent {}
}

namespace App\Models{
/**
 * Model for Sponsor Tree Nested Set.
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property string $direction
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property string|null $enrolled_at
 * @property int|null $sponsor_id
 * @property int|null $depth
 * @property int|null $temp
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\BinaryPlanNode[] $children
 * @property-read int|null $children_count
 * @property-read \App\Models\BinaryPlanNode|null $parent
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode d()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\BinaryPlanNode newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\BinaryPlanNode newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Models\BinaryPlanNode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereEnrolledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereSponsorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BinaryPlanNode whereUserId($value)
 */
	class BinaryPlanNode extends \Eloquent {}
}

namespace App\Models{
/**
 * Model for Site Settings
 *
 * @package App\Models
 * @property int $id
 * @property string $user_distid
 * @property int $rank_id
 * @property string|null $commission_type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ForceRank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ForceRank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ForceRank query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ForceRank whereCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ForceRank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ForceRank whereRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ForceRank whereUserDistid($value)
 */
	class ForceRank extends \Eloquent {}
}

namespace App\Models{
/**
 * Model for Site Settings
 *
 * @package App\Models
 * @property int $id
 * @property string|null $current_month_qc
 * @property int $user_id
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserStatistic whereCurrentMonthQc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserStatistic whereUserId($value)
 */
	class UserStatistic extends \Eloquent {}
}

namespace App\Models{
/**
 * Model for Site Settings
 *
 * @package App\Models
 * @property int $id
 * @property int|null $user_id
 * @property string $key
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SiteSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SiteSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SiteSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SiteSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SiteSettings whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SiteSettings whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SiteSettings whereValue($value)
 */
	class SiteSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * Model for Site Settings
 *
 * @package App\Models
 * @property int $id
 * @property string $commission_type
 * @property string $end_date
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionStatus whereCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionStatus whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommissionStatus whereStatus($value)
 */
	class CommissionStatus extends \Eloquent {}
}

namespace App{
/**
 * App\BoomerangTracker
 *
 * @property int|null $userid
 * @property string|null $boomerang_code
 * @property int|null $num_boomerang
 * @property int|null $reg_boomerangs
 * @property string|null $exp_dt
 * @property int|null $mode
 * @property int|null $is_used
 * @property string|null $lead_firstname
 * @property string|null $lead_lastname
 * @property string|null $lead_email
 * @property string|null $lead_mobile
 * @property string|null $group_campaign
 * @property int|null $group_no_of_uses
 * @property int|null $group_available
 * @property string|null $date_created
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereBoomerangCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereDateCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereExpDt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereGroupAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereGroupCampaign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereGroupNoOfUses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereLeadEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereLeadFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereLeadLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereLeadMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereNumBoomerang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereRegBoomerangs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangTracker whereUserid($value)
 */
	class BoomerangTracker extends \Eloquent {}
}

namespace App{
/**
 * App\BinaryPermission
 *
 * @property int $id
 * @property string|null $permit_to
 * @property string|null $mode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryPermission whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryPermission wherePermitTo($value)
 */
	class BinaryPermission extends \Eloquent {}
}

namespace App{
/**
 * Class BinaryCommissionHistory
 *
 * @package App
 * @property int $id
 * @property string $start_date
 * @property string $end_date
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionHistory whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionHistory whereStartDate($value)
 */
	class BinaryCommissionHistory extends \Eloquent {}
}

namespace App{
/**
 * App\SaveOn
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $api_log
 * @property int|null $platform_id
 * @property string|null $platform_name
 * @property string|null $platform_tier
 * @property int|null $sor_user_id
 * @property int|null $product_id
 * @property string|null $sor_password
 * @property string|null $token
 * @property int|null $status
 * @property int|null $old_sor_user_id
 * @property string|null $note
 * @property string|null $disable_process
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereApiLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereDisableProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereOldSorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn wherePlatformId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn wherePlatformName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn wherePlatformTier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereSorPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereSorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SaveOn whereUserId($value)
 */
	class SaveOn extends \Eloquent {}
}

namespace App{
/**
 * App\MailTemplate
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $subject
 * @property string|null $content
 * @property string|null $place_holders
 * @property string|null $remarks
 * @property int|null $is_active
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate wherePlaceHolders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailTemplate whereUpdatedAt($value)
 */
	class MailTemplate extends \Eloquent {}
}

namespace App{
/**
 * App\SponsorUpdateHistory
 *
 * @property string|null $created_at
 * @property string|null $f_sponsor
 * @property string|null $status
 * @property string|null $t_sponsor
 * @property int|null $user_id
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory whereFSponsor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory whereTSponsor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SponsorUpdateHistory whereUserId($value)
 */
	class SponsorUpdateHistory extends \Eloquent {}
}

namespace App{
/**
 * App\DiscountCoupon
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $is_used
 * @property string|null $created_at
 * @property int|null $used_by
 * @property float|null $discount_amount
 * @property int|null $is_active
 * @property int|null $generated_for
 * @property int|null $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereGeneratedFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DiscountCoupon whereUsedBy($value)
 */
	class DiscountCoupon extends \Eloquent {}
}

namespace App{
/**
 * App\ApiRequest
 *
 * @property int $id
 * @property string|null $token
 * @property string|null $request
 * @property string|null $status
 * @property string|null $request_on
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiRequest whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiRequest whereRequestOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiRequest whereToken($value)
 */
	class ApiRequest extends \Eloquent {}
}

namespace App{
/**
 * App\ProductTermsAgreement
 *
 * @property int $id
 * @property int $user_id
 * @property int $agree_sor
 * @property int $agree_idecide
 * @property string|null $agreed_sor_at
 * @property string|null $agreed_idecide_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement whereAgreeIdecide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement whereAgreeSor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement whereAgreedIdecideAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement whereAgreedSorAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductTermsAgreement whereUserId($value)
 */
	class ProductTermsAgreement extends \Eloquent {}
}

namespace App{
/**
 * App\ApiLogs
 *
 * @property int $id
 * @property int $user_id
 * @property string $api
 * @property string $endpoint
 * @property string|null $request
 * @property string|null $response
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs whereApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs whereEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLogs whereUserId($value)
 */
	class ApiLogs extends \Eloquent {}
}

namespace App{
/**
 * Class BinaryCommissionCarryoverHistory
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property float $right_carryover
 * @property float $left_carryover
 * @property int $bc_history_id
 * @property-read \App\BinaryCommissionHistory $commissionHistory
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionCarryoverHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionCarryoverHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionCarryoverHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionCarryoverHistory whereBcHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionCarryoverHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionCarryoverHistory whereLeftCarryover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionCarryoverHistory whereRightCarryover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommissionCarryoverHistory whereUserId($value)
 */
	class BinaryCommissionCarryoverHistory extends \Eloquent {}
}

namespace App{
/**
 * App\RankDefinition
 *
 * @property int $id
 * @property int $min_qv
 * @property int $max_qv
 * @property string $rank_title
 * @property float|null $rank_limit
 * @property int|null $rankval
 * @property string|null $rankdesc
 * @property int|null $status_code
 * @property string|null $colour
 * @property string|null $image
 * @property int|null $min_tsa
 * @property int|null $max_tsa
 * @property int $min_binary_count
 * @property int $min_qc
 * @property float $qc_percent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereColour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereMaxQv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereMaxTsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereMinBinaryCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereMinQc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereMinQv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereMinTsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereQcPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereRankLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereRankTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereRankdesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereRankval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RankDefinition whereStatusCode($value)
 */
	class RankDefinition extends \Eloquent {}
}

namespace App{
/**
 * App\NMIGateway
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NMIGateway newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NMIGateway newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NMIGateway query()
 */
	class NMIGateway extends \Eloquent {}
}

namespace App{
/**
 * App\IPayOut
 *
 * @property int $id
 * @property int $user_id
 * @property int $transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IPayOut newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IPayOut newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IPayOut query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IPayOut whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IPayOut whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IPayOut whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IPayOut whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IPayOut whereUserId($value)
 */
	class IPayOut extends \Eloquent {}
}

namespace App{
/**
 * App\Commission
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Commission query()
 */
	class Commission extends \Eloquent {}
}

namespace App{
/**
 * App\Product
 *
 * @property int|null $id
 * @property string|null $productname
 * @property int|null $producttype
 * @property string|null $productdesc
 * @property string|null $productdesc2
 * @property int|null $isautoship
 * @property int|null $statuscode
 * @property string|null $created_at
 * @property string|null $udated_at
 * @property float|null $price
 * @property float|null $price_as
 * @property float|null $price2
 * @property float|null $price3
 * @property string|null $sku
 * @property string|null $itemcode
 * @property int|null $bv
 * @property int|null $cv
 * @property int|null $qv
 * @property int|null $num_boomerangs
 * @property int|null $sponsor_boomerangs
 * @property float|null $qc
 * @property float|null $ac
 * @property int $is_enabled
 * @property-read \App\ProductType $productTypes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereAc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereIsautoship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereItemcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereNumBoomerangs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePriceAs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereProductdesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereProductdesc2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereProductname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereProducttype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereQc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereQv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereSponsorBoomerangs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereStatuscode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App{
/**
 * App\OrderItem
 *
 * @property int|null $orderid
 * @property int|null $productid
 * @property int|null $quantity
 * @property float|null $itemprice
 * @property int|null $bv
 * @property int|null $qv
 * @property int|null $cv
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_date
 * @property string|null $created_time
 * @property int|null $discount_coupon
 * @property int|null $discount_voucher_id
 * @property string|null $created_dt
 * @property int|null $qc
 * @property int|null $ac
 * @property-read \App\Order|null $order
 * @property-read \App\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereAc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereBv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereCreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereCreatedDt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereDiscountCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereDiscountVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereItemprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereProductid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereQc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereQv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App{
/**
 * App\ApiToken
 *
 * @property int $id
 * @property string|null $token
 * @property int|null $is_active
 * @property string|null $generated_on
 * @property int|null $generated_by
 * @property string|null $remarks
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken whereGeneratedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken whereGeneratedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiToken whereToken($value)
 */
	class ApiToken extends \Eloquent {}
}

namespace App{
/**
 * App\Address
 *
 * @property int|null $userid
 * @property string|null $addrtype
 * @property int|null $primary
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $stateprov
 * @property string|null $stateprov_abbrev
 * @property string|null $postalcode
 * @property string|null $countrycode
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $id
 * @property string|null $apt
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereAddrtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereApt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCountrycode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address wherePostalcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address wherePrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereStateprov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereStateprovAbbrev($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereUserid($value)
 */
	class Address extends \Eloquent {}
}

namespace App{
/**
 * App\Transaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction query()
 */
	class Transaction extends \Eloquent {}
}

namespace App{
/**
 * App\Order
 *
 * @property int|null $userid
 * @property int|null $statuscode
 * @property float|null $ordersubtotal
 * @property float|null $ordertax
 * @property float|null $ordertotal
 * @property int|null $orderbv
 * @property int|null $orderqv
 * @property int|null $ordercv
 * @property string|null $trasnactionid
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property int|null $payment_methods_id
 * @property int|null $shipping_address_id
 * @property int $id
 * @property int|null $inv_id
 * @property string|null $created_date
 * @property string|null $created_time
 * @property bool|null $processed
 * @property string|null $coupon_code
 * @property int|null $order_refund_ref
 * @property string|null $created_dt
 * @property int|null $orderqc
 * @property int|null $orderac
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrderItem[] $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCouponCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedDt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereInvId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderRefundRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderac($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderbv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrdercv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderqc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderqv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrdersubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrdertax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrdertotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePaymentMethodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereShippingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatuscode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereTrasnactionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUserid($value)
 */
	class Order extends \Eloquent {}
}

namespace App{
/**
 * App\CommissionTemp
 *
 * @property int $id
 * @property int $transaction_id
 * @property float|null $amount
 * @property int|null $user_id
 * @property int|null $level
 * @property string|null $transaction_date
 * @property string|null $memo
 * @property int|null $status
 * @property int|null $initiated_user_id
 * @property int|null $report_type
 * @property string|null $processed_date
 * @property int|null $order_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereInitiatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereProcessedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereReportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTemp whereUserId($value)
 */
	class CommissionTemp extends \Eloquent {}
}

namespace App{
/**
 * App\TSBCommission
 *
 * @property int $id
 * @property int $user_id
 * @property int $dist_id
 * @property int|null $rank_id
 * @property int|null $level
 * @property float $percent
 * @property float $amount
 * @property int $order_id
 * @property string $calculation_date
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property string|null $memo
 * @property-read \App\OrderItem $order
 * @property-read \App\User $sourceUser
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereCalculationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereDistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TSBCommission whereUserId($value)
 */
	class TSBCommission extends \Eloquent {}
}

namespace App{
/**
 * App\UserRankHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $monthly_rank
 * @property string|null $period
 * @property int|null $monthly_qv
 * @property int|null $qualified_qv
 * @property string|null $monthly_rank_desc
 * @property string|null $last_updated_on
 * @property int $qualified_tsa
 * @property int $monthly_tsa
 * @property int|null $monthly_cv
 * @property int|null $monthly_qc
 * @property int|null $qualified_qc
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereLastUpdatedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereMonthlyCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereMonthlyQc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereMonthlyQv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereMonthlyRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereMonthlyRankDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereMonthlyTsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereQualifiedQc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereQualifiedQv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereQualifiedTsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserRankHistory whereUserId($value)
 */
	class UserRankHistory extends \Eloquent {}
}

namespace App{
/**
 * App\Subscription
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $subscription_product_id
 * @property string|null $attempted_date
 * @property int|null $attempt_count
 * @property int|null $status
 * @property string|null $response
 * @property string|null $next_attempt_date
 * @property int|null $payment_method_id
 * @property int|null $is_reactivate For manual reactivate or not
 * @property string|null $remarks
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereAttemptCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereAttemptedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereIsReactivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereNextAttemptDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereSubscriptionProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereUserId($value)
 */
	class Subscription extends \Eloquent {}
}

namespace App{
/**
 * App\ReservationGuest
 *
 * @property int $reservation_id
 * @property int $adults
 * @property int $childrens
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservationGuest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservationGuest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservationGuest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservationGuest whereAdults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservationGuest whereChildrens($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservationGuest whereReservationId($value)
 */
	class ReservationGuest extends \Eloquent {}
}

namespace App{
/**
 * App\EwalletTransaction
 *
 * @property int $id
 * @property int|null $user_id
 * @property float|null $opening_balance
 * @property float|null $closing_balance
 * @property float|null $amount
 * @property string|null $type
 * @property string|null $remarks
 * @property string|null $created_at
 * @property int|null $csv_generated
 * @property int|null $csv_id
 * @property string|null $payap_mobile
 * @property int|null $purchase_id
 * @property string|null $withdraw_method
 * @property string|null $commission_type
 * @property int|null $add_deduct
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereAddDeduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereClosingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereCommissionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereCsvGenerated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereCsvId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereOpeningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction wherePayapMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletTransaction whereWithdrawMethod($value)
 */
	class EwalletTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\PaymentMethodType
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethodType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethodType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PaymentMethodType query()
 */
	class PaymentMethodType extends \Eloquent {}
}

namespace App{
/**
 * App\BinaryTreeEditor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryTreeEditor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryTreeEditor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryTreeEditor query()
 */
	class BinaryTreeEditor extends \Eloquent {}
}

namespace App{
/**
 * App\Reservation
 *
 * @property int $id
 * @property string|null $arrival_date
 * @property string|null $book_date
 * @property float $club_commission
 * @property string $club_margin
 * @property string|null $confirmation_number
 * @property string|null $contract_number
 * @property string $departure_date
 * @property string|null $email_address
 * @property string $guest_first_name
 * @property string $guest_last_name
 * @property string $location
 * @property int|null $number_of_guest_ref
 * @property int $number_of_rooms
 * @property int|null $other_id
 * @property string|null $reservation_type
 * @property string|null $resort
 * @property float $retail_saving
 * @property string|null $room_type
 * @property int|null $sor_member_id
 * @property int|null $save_on_res_id
 * @property string|null $status
 * @property float $total_charge
 * @property string|null $user_type
 * @property int|null $vacation_club
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereArrivalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereBookDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereClubCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereClubMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereConfirmationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereContractNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereDepartureDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereEmailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereGuestFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereGuestLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereNumberOfGuestRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereNumberOfRooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereOtherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereReservationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereResort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereRetailSaving($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereRoomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereSaveOnResId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereSorMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereTotalCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reservation whereVacationClub($value)
 */
	class Reservation extends \Eloquent {}
}

namespace App{
/**
 * App\Helper
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helper query()
 */
	class Helper extends \Eloquent {}
}

namespace App{
/**
 * App\UpdateHistory
 *
 * @property int $id
 * @property string|null $type
 * @property int|null $type_id
 * @property string|null $before_update
 * @property string|null $after_update
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $mode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory whereAfterUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory whereBeforeUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UpdateHistory whereUpdatedBy($value)
 */
	class UpdateHistory extends \Eloquent {}
}

namespace App{
/**
 * App\AdminPermission
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdminPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdminPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AdminPermission query()
 */
	class AdminPermission extends \Eloquent {}
}

namespace App{
/**
 * App\CommissionDates
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $start_date
 * @property string|null $end_date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionDates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionDates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionDates query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionDates whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionDates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionDates whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionDates whereType($value)
 */
	class CommissionDates extends \Eloquent {}
}

namespace App{
/**
 * Class BinaryCommission
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property float $carryover_left
 * @property float $carryover_right
 * @property float $total_volume_left
 * @property float $total_volume_right
 * @property float $gross_volume
 * @property float $commission_percent
 * @property float $amount_earned
 * @property string $week_ending
 * @property string $status
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereAmountEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereCarryoverLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereCarryoverRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereCommissionPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereGrossVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereTotalVolumeLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereTotalVolumeRight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BinaryCommission whereWeekEnding($value)
 */
	class BinaryCommission extends \Eloquent {}
}

namespace App{
/**
 * App\EwalletCSV
 *
 * @property int $id
 * @property int|null $generated_by
 * @property int|null $processed
 * @property int|null $no_of_entries
 * @property string|null $generated_on
 * @property string|null $memo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV whereGeneratedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV whereGeneratedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV whereNoOfEntries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\EwalletCSV whereProcessed($value)
 */
	class EwalletCSV extends \Eloquent {}
}

namespace App{
/**
 * App\SubscriptionHistory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SubscriptionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SubscriptionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SubscriptionHistory query()
 */
	class SubscriptionHistory extends \Eloquent {}
}

namespace App{
/**
 * App\MailGunMailList
 *
 * @property int $id
 * @property string|null $address
 * @property string|null $name
 * @property string|null $description
 * @property int|null $no_of_members
 * @property string|null $type
 * @property int|null $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList whereNoOfMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MailGunMailList whereType($value)
 */
	class MailGunMailList extends \Eloquent {}
}

namespace App{
/**
 * App\StoreAPI
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StoreAPI newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StoreAPI newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StoreAPI query()
 */
	class StoreAPI extends \Eloquent {}
}

namespace App{
/**
 * App\UserType
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserType query()
 */
	class UserType extends \Eloquent {}
}

namespace App{
/**
 * App\PasswordResetTokens
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $token
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $createdAt
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordResetTokens newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordResetTokens newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordResetTokens query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordResetTokens whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordResetTokens whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordResetTokens whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordResetTokens whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordResetTokens whereUpdatedAt($value)
 */
	class PasswordResetTokens extends \Eloquent {}
}

namespace App{
/**
 * App\ProductType
 *
 * @property int|null $id
 * @property string|null $typedesc
 * @property int|null $statuscode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductType whereStatuscode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductType whereTypedesc($value)
 */
	class ProductType extends \Eloquent {}
}

namespace App{
/**
 * App\Export
 *
 * @property int $user_id
 * @property string|null $export_to
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Export newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Export newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Export query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Export whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Export whereExportTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Export whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Export whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Export whereUserId($value)
 */
	class Export extends \Eloquent {}
}

namespace App{
/**
 * App\IDecide
 *
 * @property int $id
 * @property int $api_log
 * @property int $user_id
 * @property int|null $idecide_user_id
 * @property string|null $password
 * @property string|null $login_url
 * @property int|null $is_updated_business_number
 * @property int|null $generated_integration_id
 * @property int|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide whereApiLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide whereGeneratedIntegrationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide whereIdecideUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide whereIsUpdatedBusinessNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide whereLoginUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IDecide whereUserId($value)
 */
	class IDecide extends \Eloquent {}
}

namespace App{
/**
 * App\Country
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country query()
 */
	class Country extends \Eloquent {}
}

namespace App{
/**
 * App\CommissionTempPost
 *
 * @property int $id
 * @property int $transaction_id
 * @property float|null $amount
 * @property int|null $user_id
 * @property int|null $level
 * @property string|null $transaction_date
 * @property string|null $memo
 * @property int|null $status
 * @property int|null $initiated_user_id
 * @property int|null $report_type
 * @property string|null $processed_date
 * @property int|null $order_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereInitiatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereProcessedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereReportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CommissionTempPost whereUserId($value)
 */
	class CommissionTempPost extends \Eloquent {}
}

namespace App{
/**
 * App\BoomerangInv
 *
 * @property int|null $userid
 * @property int|null $pending_tot
 * @property int|null $available_tot
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangInv newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangInv newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangInv query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangInv whereAvailableTot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangInv whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangInv wherePendingTot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BoomerangInv whereUserid($value)
 */
	class BoomerangInv extends \Eloquent {}
}

namespace App{
/**
 * App\Customer
 *
 * @property int $id
 * @property int|null $userid
 * @property string|null $name
 * @property string|null $email
 * @property string|null $mobile
 * @property string|null $boomerang_code
 * @property string|null $created_date
 * @property string|null $sor_default_password
 * @property int|null $sync_with_mailgun
 * @property string|null $custid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereBoomerangCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereCreatedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereCustid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereSorDefaultPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereSyncWithMailgun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customer whereUserid($value)
 */
	class Customer extends \Eloquent {}
}

namespace App{
/**
 * App\CronJob
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CronJob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CronJob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CronJob query()
 */
	class CronJob extends \Eloquent {}
}

namespace App{
/**
 * App\PromoInfo
 *
 * @property int $id
 * @property string|null $top_banner_img
 * @property string|null $top_banner_url
 * @property int|null $top_banner_is_active
 * @property string|null $side_banner_img
 * @property string|null $side_banner_title
 * @property string|null $side_banner_short_desc
 * @property string|null $side_banner_long_desc
 * @property int|null $side_banner_is_active
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereSideBannerImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereSideBannerIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereSideBannerLongDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereSideBannerShortDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereSideBannerTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereTopBannerImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereTopBannerIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereTopBannerUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoInfo whereUpdatedAt($value)
 */
	class PromoInfo extends \Eloquent {}
}

namespace App{
/**
 * App\Twilio
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Twilio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Twilio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Twilio query()
 */
	class Twilio extends \Eloquent {}
}

namespace App{
/**
 * App\TwilioAuthy
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwilioAuthy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwilioAuthy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TwilioAuthy query()
 */
	class TwilioAuthy extends \Eloquent {}
}

namespace App{
/**
 * Class UserActivityHistory
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property bool $is_active
 * @property bool $is_activate
 * @property bool $is_bc_active
 * @property string $created_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory whereIsActivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory whereIsBcActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivityHistory whereUserId($value)
 */
	class UserActivityHistory extends \Eloquent {}
}

namespace App{
/**
 * App\PayAP
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayAP newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayAP newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayAP query()
 */
	class PayAP extends \Eloquent {}
}

namespace App{
/**
 * App\PayOutControl
 *
 * @property int $id
 * @property int $country_id
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayOutControl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayOutControl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayOutControl query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayOutControl whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayOutControl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayOutControl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayOutControl whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayOutControl whereUpdatedAt($value)
 */
	class PayOutControl extends \Eloquent {}
}

namespace App{
/**
 * App\BulkEmail
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $to
 * @property string|null $sent_on
 * @property string|null $content
 * @property int|null $sent_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail whereSentBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail whereSentOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkEmail whereTo($value)
 */
	class BulkEmail extends \Eloquent {}
}

namespace App{
/**
 * App\TVBrokenSponsors
 *
 * @property int $id
 * @property string|null $agent_id
 * @property string|null $agent_name
 * @property string|null $web_alias
 * @property string|null $email
 * @property string|null $sponsor
 * @property string|null $app_dt
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors whereAgentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors whereAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors whereAppDt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors whereSponsor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TVBrokenSponsors whereWebAlias($value)
 */
	class TVBrokenSponsors extends \Eloquent {}
}

