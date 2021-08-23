<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model {

    // SIDEBAR, start
    public static function sidebar_sales() {
        return true;
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        if (User::admin_cs())
            return true;
        //
        return false;
    }

    public static function sidebar_discount() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        //
        return false;
    }

    public static function sidebar_commission() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        //
        return false;
    }

    public static function sidebar_admin_users() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        //
        return false;
    }

    public static function sidebar_cs_users() {
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        //
        return false;
    }

    public static function sidebar_product() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        //
        return false;
    }

    public static function sidebar_marketing() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_sales())
            return true;
        //
        return false;
    }

    public static function events_manage() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_sales())
            return true;
        //
        return false;
    }

    public static function sidebar_api_token() {
        if (User::admin_super_admin())
            return true;
        //
        return false;
    }

    public static function sidebar_binary_controller() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        //
        return false;
    }

    public static function sidebar_misc() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        //
        return false;
    }

    public static function sidebar_active_override() {
        if (User::admin_super_admin()) {
            return true;
        }
        if (User::admin_super_exec()) {
            return true;
        }
        if (User::admin_cs_exec()) {
            return true;
        }
        if (User::admin_cs_manager()) {
            return true;
        }
        //
        return false;
    }

    public static function sidebar_upgrade_control() {
        if (User::admin_super_admin()) {
            return true;
        }
        if (User::admin_super_exec()) {
            return true;
        }
        if (User::admin_cs_exec()) {
            return true;
        }
        if (User::admin_cs_manager())
            return true;
        //
        return false;
    }

    public static function sidebar_subscription_reactivate() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        if (User::admin_cs())
            return true;
        //
        return false;
    }

    public static function sidebar_ambassador_reactivate() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        if (User::admin_cs())
            return true;
        //
        return false;
    }

    // SIDEBAR, end
    // FUNCTIONS, start
    public static function fn_add_new_ambassador() {
        return false;

        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        //
    }

    public static function fn_update_sponsor_id() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        //
        return false;
    }

    // allow for all admin users
    public static function fn_update_username() {
        return true;
        // if (User::admin_super_admin())
        //     return true;
        // if (User::admin_super_exec())
        //     return true;
        // if (User::admin_cs_exec())
        //     return true;
        // if (User::admin_cs_manager())
        //     return true;
        // //
        // return false;
    }

    public static function fn_create_idecide() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        if (User::admin_cs())
            return true;
        //
        return false;
    }

    public static function fn_create_sor() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        if (User::admin_cs())
            return true;
        //
        return false;
    }

    public static function fn_update_boomerang() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        if (User::admin_cs())
            return true;
        //
        return false;
    }

    public static function fn_show_graph() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_sales())
            return true;
        //
        return false;
    }

    public static function fn_corp_people_in_search() {
        if (User::admin_super_admin())
            return true;
        //
        return false;
    }

    public static function sidebar_countries() {
        if (User::admin_super_admin()) {
            return true;
        }
        if (User::admin_super_exec()) {
            return true;
        }

        return false;
    }

    public static function add_edit_refund_orders_and_order_items() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_cs_exec())
            return true;
        if (User::admin_cs_manager())
            return true;
        if (User::admin_cs())
            return true;
        //
        return false;
    }
    // FUNCTIONS, end

    public static function export_reports()
    {
        $enabled = env('EXPORT_REPORTS_ENABLED', false);
        if (!$enabled) {
            return false;
        }
        if (User::admin_super_admin()) {
            return true;
        }
        if (User::admin_super_exec()) {
            return true;
        }
        //
        return false;
    }

    public static function sidebar_user_transfer()
    {
        if (User::admin_super_admin()) {
            return true;
        }
        if (User::admin_super_exec()) {
            return true;
        }
        if (User::admin_cs_exec()) {
            return true;
        }
        if (User::admin_cs_manager())
            return true;
        //
        return false;
    }
    public static function sidebar_ranks_settings()
    {
        if (User::admin_super_admin()) {
            return true;
        }

        if (User::admin_super_exec()) {
            return true;
        }

        return false;
    }

    public static function ticket_system() {
        if (User::admin_super_admin())
            return true;
        if (User::admin_super_exec())
            return true;
        if (User::admin_sales())
            return false;
        //
        return false;
    }

}
