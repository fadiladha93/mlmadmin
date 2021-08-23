<?php

namespace App\Http\Controllers;

use App\Facades\BinaryPlanManager;
use App\Facades\HoldingTank;
use App\Models\BinaryPlanNode;
use App\User;
use DB;

class ApiController extends Controller {

    const TYPE_GET_USER_DETAIL = "get-user-detail";
    const TYPE_GET_USERS_BY_ENROLLMENT_DATE = "get-users-by-enrollment-date";
    const TYPE_GET_USERS = "get-users";
    const TYPE_SET_BINARY_PLACEMENT = "set-binary-placement";
    const TYPE_RANK_CHECK = "rank-check";

    public function run($type) {
        $res = $this->validateRequest($type);
        if (!$res['valid']) {
            \App\ApiRequest::addNew($res['token'], "error - " . $res['msg']);
            return json_encode(array("status" => "error", "msg" => $res['msg']));
        } else {
            \App\ApiRequest::addNew($res['token'], "success");
            if ($type == self::TYPE_GET_USERS) {
                return json_encode($this->getAllUsers());
            } else if ($type == self::TYPE_GET_USER_DETAIL) {
                return json_encode($this->getUserDetail());
            } else if ($type == self::TYPE_GET_USERS_BY_ENROLLMENT_DATE) {
                return json_encode($this->getByEnrollementDate());
            } else if ($type == self::TYPE_SET_BINARY_PLACEMENT) {
                return json_encode($this->setBinaryPlacement());
            } else if ($type == self::TYPE_RANK_CHECK) {
                return json_encode($this->rankCheck());
            }
        }
    }

    private function getAllUsers() {
        $recsPerPage = 200;
        $recs = \App\User::getRecordsForAPI($recsPerPage);
        $d = array();
        $data = array();
        foreach ($recs as $rec) {
            array_push($data, $rec);
        }
        $d['status'] = "success";
        $d['users'] = $data;
        $d['next_page'] = $recs->nextPageUrl();
        $d['prev_page'] = $recs->previousPageUrl();
        $d['records_per_page'] = $recs->perPage();
        return $d;
    }

    private function getUserDetail() {
        $req = request();
        $distid = $req->distid;
        $rec = \App\User::getByDistIdForAPI($distid);
        $d['status'] = "success";
        if (empty($rec)) {
            $d['user'] = "not found";
        } else {
            $d['user'] = $rec;
        }
        return $d;
    }

    private function getByEnrollementDate() {
        $req = request();
        $enrollmentDate = $req->date;
        $recs = \App\User::getByEnrollmentDateForAPI($enrollmentDate);
        $d['status'] = "success";
        if ($recs->count() == 0) {
            $d['users'] = "not found";
        } else {
            $d['users'] = $recs;
        }
        return $d;
    }

    private function rankCheck()
    {
        $req = request();
        if (!empty($req->username)) {
            $user = \App\User::getByDistIdOrUsername($req->username);
        } elseif (!empty($req->distid)) {
            $user = \App\User::getByDistIdOrUsername($req->distid);
        } else {
            $d['status'] = "error";
            return $d;
        }
        if (empty($user)) {
            $d['status'] = "error";
            $d['user'] = [];
            return $d;
        }
        $rank = \App\UserRankHistory::getCurrentMonthUserInfo($user->id);
        $d['status'] = "success";
        $d['rank'] = $rank;
        $d['user'] = ['firstname' => $user->firstname, 'lastname' => $user->lastname];
        return $d;
    }

    private function setBinaryPlacement() {
        $req = request();
        $distid = $req->distid;
        $d = array();
        $d['status'] = "success";
        // check if this dist id is available
        $newUser = \App\User::where('distid', $distid)
                ->get();

        if ($newUser->count() == 0) {
            $d['error'] = 'Distributor not found';
            return $d;
        }
        // check this user already in the binary tree
        $inBinary = DB::table('binary_plan')
                ->where('user_id', $newUser[0]->id)
                ->count();
        if ($inBinary > 0) {
            $d['error'] = 'This user already placed in the binary tree';
            return $d;
        }
        // get the sponsor info
        $sponsorDistId = $newUser[0]->sponsorid;
        $sponsor = User::where('distid', $sponsorDistId)
                ->first();
        //
        $targetNode = \App\Models\BinaryPlanNode::where('user_id', $sponsor->id)->first();
        if (empty($targetNode)) {
            $d['error'] = 'Sponsor is not in tree';
            return $d;
        }
        //
        try {
            if (\utill::isNullOrEmpty($sponsor->binary_placement)) {
                $direction = $targetNode->direction == "R" ? "right" : "left";
            } else {
                $direction = $sponsor->binary_placement;
            }
            if ($direction == "stronger" || $direction == "lesser") {
                $currentLeftAmount = $this->getCurrentLeftAmount($targetNode);
                $totalLeft = $currentLeftAmount + $sponsor->getCurrentLeftCarryover();
                //
                $currentRightAmount = $this->getCurrentRightAmount($targetNode);
                $totalRight = $currentRightAmount + $sponsor->getCurrentRightCarryover();
                if ($totalLeft == $totalRight) {
                    $direction = $targetNode->direction == "R" ? "right" : "left";
                }
                if ($direction == "stronger") {
                    if ($totalLeft > $totalRight) {
                        $direction = "left";
                    } else {
                        $direction = "right";
                    }
                } else if ($direction == "lesser") {
                    if ($totalLeft > $totalRight) {
                        $direction = "right";
                    } else {
                        $direction = "left";
                    }
                }
            }

            if ($direction == BinaryPlanManager::DIRECTION_AUTO) {
                $lastEnrolled = $this->getLastEnrolledUser($sponsor, $newUser[0]);

                if ($lastEnrolled) {
                    $node = BinaryPlanManager::getNodeByAgentTsa($lastEnrolled);

                    if ($node) {
                        $direction = $node->direction === BinaryPlanNode::DIRECTION_LEFT
                            ? BinaryPlanManager::DIRECTION_RIGHT
                            : BinaryPlanManager::DIRECTION_LEFT;
                    }
                }
            }

            HoldingTank::placeAgentsToBinaryViewer($targetNode, $newUser, $direction);
        } catch (\App\Exceptions\BinaryNodeIsChangedException $ex) {

        }
        return $d;
    }

    /**
     * @param $sponsor
     * @param $currentUser
     * @return string
     */
    private function getLastEnrolledUser($sponsor, $currentUser)
    {
        return DB::table('users')
            ->select('distid')
            ->where('users.sponsorid', $sponsor->distid)
            ->where('users.distid', '<>', $currentUser->distid)
            ->orderBy('created_dt', 'DESC')
            ->pluck('distid')
            ->first();
    }

    // for binary placement
    private function getCurrentLeftAmount($targetNode) {
        $mondayDate = date('Y-m-d', strtotime('monday this week'));
        $leftLeg = BinaryPlanManager::getLeftLeg($targetNode);
        $currentLeftAmount = 0;
        if ($leftLeg) {
            $currentLeftAmount = BinaryPlanManager::getNodeTotal($leftLeg, $mondayDate);
        }
        return $currentLeftAmount;
    }

    private function getCurrentRightAmount($targetNode) {
        $mondayDate = date('Y-m-d', strtotime('monday this week'));
        $rightLeg = BinaryPlanManager::getRightLeg($targetNode);
        $currentRightAmount = 0;
        if ($rightLeg) {
            $currentRightAmount = BinaryPlanManager::getNodeTotal($rightLeg, $mondayDate);
        }
        return $currentRightAmount;
    }

    private function validateRequest($type) {
        $res = array();
        $valid = true;
        $error_msg = "";
        //
        $req = request();
        $token = $req->header('ibuumerang-token');
        if (\utill::isNullOrEmpty($token)) {
            $valid = false;
            $error_msg = "Token not found";
        } else {
            $isValid = \App\ApiToken::isValidToken($token);
            if (!$isValid) {
                $valid = false;
                $error_msg = "Invalid or inactive token";
            } else if (!$this->validateRequestType($type)) {
                $valid = false;
                $error_msg = "Invalid request type";
            } else {
                // required param validations
                if ($type == self::TYPE_GET_USER_DETAIL) {
                    $distid = $req->distid;
                    if (\utill::isNullOrEmpty($distid)) {
                        $valid = false;
                        $error_msg = "Distributor ID is required";
                    }
                } else if ($type == self::TYPE_GET_USERS_BY_ENROLLMENT_DATE) {
                    $date = $req->date;
                    if (\utill::isNullOrEmpty($date)) {
                        $valid = false;
                        $error_msg = "Enrollment date is required";
                    } else if (\DateTime::createFromFormat('Y-m-d', $date) === FALSE) {
                        $valid = false;
                        $error_msg = "Invalid date format";
                    } else {
                        $temp = explode("-", $date);
                        if (checkdate($temp[1], $temp[2], $temp[0]) === FALSE) {
                            $valid = false;
                            $error_msg = "Invalid date";
                        }
                    }
                } else if ($type == self::TYPE_SET_BINARY_PLACEMENT) {
                    $distid = $req->distid;
                    if (\utill::isNullOrEmpty($distid)) {
                        $valid = false;
                        $error_msg = "Distributor ID is required";
                    }
                } else if ($type == self::TYPE_RANK_CHECK) {
                    $distid = $req->distid;
                    $username = $req->username;
                    $n = 1;
                    if (empty($distid) && empty($username)) {
                        $n = null;
                    }
                    if (\utill::isNullOrEmpty($n)) {
                        $valid = false;
                        $error_msg = "Distributor ID or Username is required";
                    }
                }
            }
        }

        //
        $res['valid'] = $valid;
        $res['msg'] = $error_msg;
        $res['token'] = $token;
        return $res;
    }

    private function validateRequestType($type) {
        $allowed_type = array();
        //array_push($allowed_type, self::TYPE_GET_USERS);
        array_push($allowed_type, self::TYPE_GET_USERS_BY_ENROLLMENT_DATE);
        array_push($allowed_type, self::TYPE_GET_USER_DETAIL);
        array_push($allowed_type, self::TYPE_SET_BINARY_PLACEMENT);
        array_push($allowed_type, self::TYPE_RANK_CHECK);
        //
        if (in_array($type, $allowed_type)) {
            return true;
        }
        return false;
    }

}
