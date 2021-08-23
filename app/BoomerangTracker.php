<?php

namespace App;

use App\Core\BoomerangManager;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BoomerangTracker extends Model
{

    protected $table = "boomerang_tracker";
    public $timestamps = false;

    const MODE_INDIVIDUAL = '1';
    const MODE_GROUP = '2';

    const BOOMERANG_USER_TYPE_IGO    = 1;
    const BOOMERANG_USER_TYPE_VIBE_RIDER = 2;
    const BOOMERANG_USER_TYPE_VIBE_DRIVER = 3;
    const BOOMERANG_USER_TYPE_BILLGENIUS = 4;

    const BOOMERANG_USER_TYPE_IGO_TEXT = 'igo';
    const BOOMERANG_USER_TYPE_VIBE_RIDER_TEXT = 'vibe-rider';
    const BOOMERANG_USER_TYPE_VIBE_DRIVER_TEXT = 'vibe-driver';
    const BOOMERANG_USER_TYPE_BILLGENIUS_TEXT = 'billgenius';

    public static $matchTextToTypes = [
        self::BOOMERANG_USER_TYPE_IGO_TEXT         => self::BOOMERANG_USER_TYPE_IGO,
        self::BOOMERANG_USER_TYPE_VIBE_RIDER_TEXT  => self::BOOMERANG_USER_TYPE_VIBE_RIDER,
        self::BOOMERANG_USER_TYPE_VIBE_DRIVER_TEXT => self::BOOMERANG_USER_TYPE_VIBE_DRIVER,
        self::BOOMERANG_USER_TYPE_BILLGENIUS_TEXT  => self::BOOMERANG_USER_TYPE_BILLGENIUS

    ];


    public static function addNewInd($userId, $firstName, $lastName, $email, $mobile, $expDays, $userType)
    {
        $code = self::generateNewBoomerang();
        $boomerangTracker = new BoomerangTracker();
        $boomerangTracker->userid         = $userId;
        $boomerangTracker->boomerang_code = $code;
        $boomerangTracker->exp_dt         = self::getExpirationDate($expDays);
        $boomerangTracker->mode           = self::MODE_INDIVIDUAL;
        $boomerangTracker->is_used        = 0;
        $boomerangTracker->lead_firstname = $firstName;
        $boomerangTracker->lead_lastname  = $lastName;
        $boomerangTracker->lead_email     = $email;
        $boomerangTracker->lead_mobile    = $mobile;
        $boomerangTracker->date_created   = date('Y-m-d');
        $boomerangTracker->user_type      = $userType;
        $boomerangTracker->save();

        return $boomerangTracker;
    }

    public static function addNewGroup($userId, $campaignName, $noOfuses, $expDays)
    {
        $code = self::generateNewBoomerang();
        $r = new BoomerangTracker();
        $r->userid = $userId;
        $r->boomerang_code = $code;
        $r->exp_dt = self::getExpirationDate($expDays);
        $r->mode = self::MODE_GROUP;
        $r->is_used = 0;
        $r->group_campaign = $campaignName;
        $r->group_no_of_uses = $noOfuses;
        $r->group_available = $noOfuses;
        $r->date_created = date('Y-m-d');
        $r->save();
        $insert_id = $r->id;

        $result = array(
            'code' => $code,
            'insert_id' => $insert_id,
        );
        return $result;
    }

    private static function generateNewBoomerang()
    {
        $code = \utill::getRandomString(6, "0123456789");
        if (!self::isAvailable($code)) {
            return $code;
        } else {
            return self::generateNewBoomerang();
        }
    }

    public static function isAvailable($code)
    {
        $today = date('Y-m-d');
        $count = DB::table('boomerang_tracker')
            ->where('boomerang_code', $code)
            ->where('exp_dt', '>=', $today)
            ->where('is_used', 0)
            ->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getValidCodeExpiryDate($code)
    {
        $today = date('Y-m-d');
        $rec = DB::table('boomerang_tracker')
            ->select('exp_dt')
            ->where('boomerang_code', $code)
            ->where('exp_dt', '>=', $today)
            ->where('is_used', 0)
            ->first();
        if (!empty($rec)) {
            return $rec->exp_dt;
        } else {
            return null;
        }
    }

    public static function getValidCodeRecord($code)
    {
        $today = date('Y-m-d');
        return DB::table('boomerang_tracker')
            ->where('boomerang_code', $code)
            ->where('exp_dt', '>=', $today)
            ->where('is_used', 0)
            ->join('users', 'users.id', '=', 'boomerang_tracker.userid')
            ->select(
                'boomerang_tracker.*',
                'users.firstname as sponsor_first_name',
                'users.lastname  as sponsor_last_name'
            )
            ->first();
    }


    public static function getRecordById($code)
    {

        return DB::table('boomerang_tracker')
            ->where('boomerang_tracker.id', $code)
            ->join('users', 'users.id', '=', 'boomerang_tracker.userid')
            ->select(
                'boomerang_tracker.*',
                'users.firstname as sponsor_first_name',
                'users.lastname  as sponsor_last_name'
            )
            ->first();
    }

    private static function getExpirationDate($days)
    {
        return date('Y-m-d', strtotime('+' . ($days - 1) . ' days'));
    }

    public static function getUserId($code)
    {
        $today = date('Y-m-d');
        $rec = DB::table('boomerang_tracker')
            ->select('userid')
            ->where('boomerang_code', $code)
            ->where('exp_dt', '>=', $today)
            ->where('is_used', 0)
            ->first();
        if (empty($rec)) {
            return 0;
        } else {
            return $rec->userid;
        }
    }

    public static function markAsUsed($userId, $code)
    {
        $today = date('Y-m-d');
        $rec = BoomerangTracker::where('boomerang_code', $code)
            ->where('userid', $userId)
            ->where('exp_dt', '>=', $today)
            ->where('is_used', 0)
            ->first();
        if (!empty($rec)) {
            if ($rec->mode == self::MODE_INDIVIDUAL) {
                $rec->is_used = 1;
            } else {
                $group_available = $rec->group_available - 1;
                if ($group_available < 0) {
                    $group_available = 0;
                }
                $rec->group_available = $group_available;
                if ($rec->group_available <= 0) {
                    $rec->is_used = 1;
                }
            }
            $rec->save();
        }
    }

    public static function getExpiredNotUsed_ind()
    {
        $today = date('Y-m-d');
        return BoomerangTracker::query()
            ->where('exp_dt', '<', $today)
            ->where('is_used', 0)
            ->where('mode', self::MODE_INDIVIDUAL)
            ->get();
    }

    public static function getExpiredNotUsed_group()
    {
        $today = date('Y-m-d');
        return DB::table('boomerang_tracker')
            ->where('exp_dt', '<', $today)
            ->where('is_used', 0)
            ->where('mode', self::MODE_GROUP)
            ->where('group_available', '>', 0)
            ->get();
    }

    public static function deleteRec($recId)
    {
        DB::table('boomerang_tracker')
            ->where('id', $recId)
            ->delete();
    }

    public static function updateGroupCount($recId, $count)
    {
        $rec = BoomerangTracker::find($recId);

        $group_available = $rec->group_available - $count;
        if ($group_available < 0) {
            $group_available = 0;
        }

        $rec->group_no_of_uses = $rec->group_no_of_uses - $count;
        $rec->group_available = $group_available;
        $rec->save();
    }

    public static function setMobileNumber($userId, $code, $mobile)
    {
        DB::table('boomerang_tracker')
            ->where('userid', $userId)
            ->where('boomerang_code', $code)
            ->where('mode', self::MODE_INDIVIDUAL)
            ->where('is_used', 0)
            ->update([
                'lead_mobile' => $mobile,
            ]);
    }

    public static function isMobileSaved($userId, $code)
    {
        $rec = DB::table('boomerang_tracker')
            ->select('lead_mobile')
            ->where('userid', $userId)
            ->where('boomerang_code', $code)
            ->where('mode', self::MODE_INDIVIDUAL)
            ->where('is_used', 0)
            ->first();
        if (empty($rec)) {
            return false;
        } else {
            if (\utill::isNullOrEmpty($rec->lead_mobile)) {
                return false;
            } else {
                return true;
            }
        }
    }

    public static function setEmail($userId, $code, $email)
    {
        DB::table('boomerang_tracker')
            ->where('userid', $userId)
            ->where('boomerang_code', $code)
            ->where('mode', self::MODE_INDIVIDUAL)
            ->where('is_used', 0)
            ->update([
                'lead_email' => $email,
            ]);
    }

    public static function isEmailSaved($userId, $code)
    {
        $rec = DB::table('boomerang_tracker')
            ->select('lead_email')
            ->where('userid', $userId)
            ->where('boomerang_code', $code)
            ->where('mode', self::MODE_INDIVIDUAL)
            ->where('is_used', 0)
            ->first();
        if (empty($rec)) {
            return false;
        } else {
            if (\utill::isNullOrEmpty($rec->lead_email)) {
                return false;
            } else {
                return true;
            }
        }
    }

    // this will trigger as cron job
    public static function addExpiredToInventory()
    {
        // individual
        $recs = self::getExpiredNotUsed_ind();
        foreach ($recs as $rec) {
            try {
                $boomerangManger = new BoomerangManager($rec->user_type);
                $boomerangManger->manager->revertExpiredBoomerang($rec);
            } catch (\Exception $ex) {
                Log::critical('An error occurred while processing expired boomerangs. Message: ' . $ex->getMessage());
            }
        }

        // group
        $recs = self::getExpiredNotUsed_group();
        foreach ($recs as $rec) {
            \App\BoomerangInv::addBackToInventory($rec->userid, $rec->group_available);
            self::updateGroupCount($rec->id, $rec->group_available);
        }
        // dd('done');
    }

    public static function hasSentTextOrEmail($code)
    {
        $result = DB::table('boomerang_tracker')
            ->select('text_or_email_sent')
            ->where('boomerang_code', $code)
            ->first();

        return $result->text_or_email_sent;
    }

    public static function setTextOrEmailSent($code)
    {
        DB::table('boomerang_tracker')
            ->where('boomerang_code', $code)
            ->update(['text_or_email_sent' => true]);
    }
}
