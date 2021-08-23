<?php

namespace App\Http\Controllers;

use App\Models\SiteSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class SiteSettingsController
 * @package App\Http\Controllers
 */
class SiteSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * @param Request $request
     * @param $setting
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $setting)
    {
        if ($setting) {
            if ($setting === 'placement-lounge') {
                /** @var Model $holdingTankSetting */
                $holdingTankSetting = SiteSettings::where('key', 'is_holding_tank_active')->first();

                if ($request->getMethod() === Request::METHOD_POST) {
                    $isEnable = intval($request->request->get('enable_holding_tank'));

                    if ($holdingTankSetting) {
                        $holdingTankSetting->value = $isEnable;
                        $holdingTankSetting->save();
                    }
                    return view('admin.settings.placement_lounge')->with([
                        'isEnable' => intval($holdingTankSetting->value),
                        'message' => 'Settings saved successfully.'
                    ]);
                }

                return view('admin.settings.placement_lounge')->with([
                    'isEnable' => intval($holdingTankSetting->value)
                ]);

            } else {
                return view('admin.errors.404');
            }
        } else {
            return view('admin.errors.404');
        }
    }
}
