<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Validator;

class UserRankHistoryController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }


    public function getRankValues() {
        $req = request();
        $rank = $req->rank - 10;
        $qv = \App\UserRankHistory::getQV(Auth::user()->distid, $rank);
        $tsa = \App\UserRankHistory::getTSA(Auth::user()->distid, $rank);
        //
        $rank_matric = \App\UserRankHistory::getRankMatrics(Auth::user()->distid, $rank);
        $qualification = "-";
        $current_monthly_qv = 0;
        if ($rank_matric != null) {
            $qualification = strtoupper($rank_matric->nextlevel_rankdesc);
            $current_monthly_qv = number_format($rank_matric->nextlevel_qv);
            $active_tsa_needed = number_format($rank_matric->nextlevel_tsa);
            $percentage = $rank_matric->nextlevel_percentage;
            $qcVolume = $rank_matric->nextlevel_qc;
            $qcPercent = $rank_matric->next_qc_percentage;
            $binaryCount = $rank_matric->binary_limit;
        }

        $limit = Auth::user()->getRankLimit($rank);
        $qcTopUsers = Auth::user()->getTopQCLegs($limit);

        // top 3 contributors
        $d['contributors'] = \App\UserRankHistory::getTopContributors(Auth::user()->distid, $rank);
        $d['font'] = ['brand', 'success', 'info', 'warning', 'danger'];
        $v_contributors = (string) view('affiliate.dashboard.top_contributors')->with($d);
        $qc_contributors = (string) view('affiliate.dashboard.top_qc_contributors')->with([
            'qcContributors' => $qcTopUsers,
            'limit' => $limit,
            'font' => $d['font'],
        ]);
        //
        return response()->json(['error' => '0',
                    'qv' => number_format($qv),
                    'tsa' => $tsa,
                    'qualification' => $qualification,
                    'current_monthly_qv' => $current_monthly_qv,
                    'active_tsa_needed' => $active_tsa_needed,
                    'percentage' => $percentage,
                    'v_contributors' => $v_contributors,
                    'qc_contributors' => $qc_contributors,
                    'qc_volume' => number_format($qcVolume),
                    'qc_percent' => $qcPercent,
                    'binary_count' => $binaryCount,
                    'qualifying_qc' => number_format(Auth::user()->getQualifyingQC($rank), 2),
        ]);
    }

    public function getBSThisMonth() {
        $rec = \App\UserRankHistory::getCurrentMonthlyRec(Auth::user()->id);
        if (empty($rec)) {
            $biz_acheived_rank = "-";
            $biz_monthly_qv = 0;
            $biz_qulified_vol = 0;
        } else {
            $biz_acheived_rank = $rec->rankdesc;
            $biz_monthly_qv = number_format($rec->monthly_qv);
            $biz_qulified_vol = number_format($rec->qualified_qv);
        }
        $comm = \App\Commission::getCurrentMonthCommission(Auth::user()->id);
        return response()->json(['error' => '0',
                    'rankdesc' => $biz_acheived_rank,
                    'monthly_qv' => $biz_monthly_qv,
                    'qualified_qv' => $biz_qulified_vol,
                    'comm' => $comm
        ]);
    }

    public function getBSLastMonth() {
        $rec = \App\UserRankHistory::getPreviousMonthlyRec(Auth::user()->id);
        if (empty($rec)) {
            $biz_acheived_rank = "-";
            $biz_monthly_qv = 0;
            $biz_qulified_vol = 0;
        } else {
            $biz_acheived_rank = $rec->rankdesc;
            $biz_monthly_qv = number_format($rec->monthly_qv);
            $biz_qulified_vol = number_format($rec->qualified_qv);
        }
        $comm = \App\Commission::getLastMonthCommission(Auth::user()->id);
        return response()->json(['error' => '0',
                    'rankdesc' => $biz_acheived_rank,
                    'monthly_qv' => $biz_monthly_qv,
                    'qualified_qv' => $biz_qulified_vol,
                    'comm' => $comm
        ]);
    }

}
