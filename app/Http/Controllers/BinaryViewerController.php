<?php

namespace App\Http\Controllers;

use App\Facades\BinaryPlanManager;
use Auth;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Http\Request;
use utill;
use Validator;

class BinaryViewerController extends Controller
{
    const BINARY_LIST_ITEMS = 5;
    const BINARY_SEARCH_ITEMS = 10;
    const INIT_LIMIT = 5;

    public function __construct()
    {
//        $this->middleware('auth');
        $this->middleware('auth.affiliate');

    }


    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id = null)
    {
        \App\Facades\HoldingTank::getOrCreateRootNode(Auth::user());

        $rootNode = BinaryPlanManager::getRootBinaryNode(Auth::user());

        if ($id !== null) {
            $currentNode = BinaryPlanManager::getNodeById($id, Auth::user());
            if (!$currentNode) {
                return redirect()->route('binaryViewer');
            }
        } else {
            $currentNode = $rootNode;
        }

        $mondayDate = Carbon::now(utill::USER_TIME_ZONE)->startOfWeek()->toDateTimeString();

        $leftLeg = BinaryPlanManager::getLeftLeg($currentNode);
        $rightLeg = BinaryPlanManager::getRightLeg($currentNode);
        $currentLeftAmount = 0;
        $currentRightAmount = 0;

        if ($leftLeg) {
            $currentLeftAmount = BinaryPlanManager::getNodeTotal($leftLeg, $mondayDate);
        }

        if ($rightLeg) {
            $currentRightAmount = BinaryPlanManager::getNodeTotal($rightLeg, $mondayDate);
        }

        $lastRight = BinaryPlanManager::getLastRightNode($currentNode);
        $lastLeft = BinaryPlanManager::getLastLeftNode($currentNode);

        $distributors = $distributorsEnd = '[]';
        $distCount = 0;

        if ($currentNode->user_id != Auth::user()->id) {
            $distributors = BinaryPlanManager::getDistributorsInTree(
                Auth::user(),
                self::BINARY_LIST_ITEMS,
                0,
                null,
                $id ? $currentNode : null
            );

            $distCount = BinaryPlanManager::getDistributorsCount(Auth::user(), null, $id ? $currentNode : null);

            if (($offset = $distCount - count($distributors)) > 0) {
                $distributorsEnd = BinaryPlanManager::getDistributorsInTree(
                    Auth::user(),
                    self::BINARY_LIST_ITEMS,
                    $offset,
                    null,
                    $id ? $currentNode : null
                );
            }
        }

        $user = $currentNode->user;
        $rank = $user->getBinaryPaidPercent();
        $previousWeekTotal      = BinaryPlanManager::getPreviousWeekTotal($user);
        $previousWeekCarryOvers = BinaryPlanManager::getPreviousWeekCarryOvers($user);

        return view('affiliate.binary_viewer.index')->with([
            'rightCurrentWeek' => $currentRightAmount,
            'leftCurrentWeek' => $currentLeftAmount,
            'currentNode' => $currentNode,
            'rootNode' => $rootNode,
            'legend' => [
                'left' => BinaryPlanManager::leftLegNodes($currentNode),
                'right' => BinaryPlanManager::rightLegNodes($currentNode),
            ],
            'distributors' => $distributors,
            'distributorsEnd' => $distributorsEnd,
            'distCount' => $distCount,
            'lastRightNode' => $lastRight->id !== $currentNode->id ? $lastRight : null,
            'lastLeftNode' => $lastLeft->id !== $currentNode->id ? $lastLeft : null,
            'previousWeekTotal' => (object) $previousWeekTotal,
            'previousWeekCarryOvers' => $previousWeekCarryOvers,
            'ranks' => json_encode([
                \App\RankInterface::RANK_AMBASSADOR => 'ambassador',
                \App\RankInterface::RANK_DIRECTOR => 'director',
                \App\RankInterface::RANK_SENIOR_DIRECTOR => 'senior-director',
                \App\RankInterface::RANK_EXECUTIVE => 'executive',
                \App\RankInterface::RANK_SAPPHIRE_AMBASSADOR => 'sapphire-ambassador',
                \App\RankInterface::RANK_RUBY => 'ruby',
                \App\RankInterface::RANK_EMERALD => 'emerald',
                \App\RankInterface::RANK_DIAMOND => 'diamond',
                \App\RankInterface::RANK_BLUE_DIAMOND => 'blue-diamond',
                \App\RankInterface::RANK_BLACK_DIAMOND => 'black-diamond',
                \App\RankInterface::RANK_PRESIDENTIAL_DIAMOND => 'presidential-diamond',
                \App\RankInterface::RANK_CROWN_DIAMOND => 'crown-diamond',
                \App\RankInterface::RANK_DOUBLE_CROWN_DIAMOND => 'double-crown-diamond',
                \App\RankInterface::RANK_TRIPLE_CROWN_DIAMOND => 'triple-crown-diamond',
            ]),
            'packs' => json_encode([
                \App\Product::ID_NCREASE_ISBO => 'standby-class',
                \App\Product::ID_BASIC_PACK => 'coach-class',
                \App\Product::ID_VISIONARY_PACK => 'business-class',
                \App\Product::ID_FIRST_CLASS => 'first-class',
                \App\Product::ID_EB_FIRST_CLASS => 'first-class',
                \App\Product::ID_Traverus_Grandfathering => 'business-class',
                \App\Product::ID_PREMIUM_FIRST_CLASS => 'elite-class',
                \App\Product::ID_VIBE_OVERDRIVE_USER => 'vibe-overdrive-class',
            ]),
            'legKey' => $id && $currentNode->user_id != Auth::user()->id ? BinaryPlanManager::getLegKey(Auth::user(), $currentNode, $rootNode) : 0,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAjaxDistributors(Request $request)
    {
        $params = $request->json()->all();
        $search = isset($params['search']) ? $params['search'] : null;
        $limit = isset($params['limit']) ? $params['limit'] : self::BINARY_SEARCH_ITEMS;
        $offset = isset($params['offset']) ? $params['offset'] : 0;
        $leg = isset($params['leg']) ? $params['leg'] : null;
        $currentNode = isset($params['currentNode']) && !isset($params['search']) ? $params['currentNode'] : null;

        if ($currentNode) {
            $currentNode = BinaryPlanManager::getNodeById($currentNode, Auth::user());
        }

        $distCount = BinaryPlanManager::getDistributorsCount(Auth::user(), $search, $currentNode, $leg);

        if ($params['offset'] + $limit > $distCount - self::INIT_LIMIT) {
                $limit = $distCount - $offset - self::INIT_LIMIT;
        }

        $distributors = BinaryPlanManager::getDistributorsInTree(
            Auth::user(),
            $limit,
            $offset,
            $search,
            $currentNode,
            $leg
        );

        return response()->json([
            'distributors' => $distributors,
            'total' => $distCount,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInitSearchDistributors(Request $request)
    {
        $params = $request->json()->all();
        $search = isset($params['search']) ? $params['search'] : null;
        $limit = isset($params['limit']) ? $params['limit'] : self::INIT_LIMIT;
        $offset = 0;
        $leg = isset($params['leg']) ? $params['leg'] : null;
        $currentNode = isset($params['currentNode']) && !isset($params['search']) ? $params['currentNode'] : null;

        if ($currentNode) {
            $currentNode = BinaryPlanManager::getNodeById($currentNode, Auth::user());
        }

        $distributors = BinaryPlanManager::getDistributorsInTree(
            Auth::user(),
            $limit,
            $offset,
            $search,
            $currentNode,
            $leg
        );

        $distCount = BinaryPlanManager::getDistributorsCount(Auth::user(), $search, $currentNode, $leg);
        $distributorsEnd = [];

        if (($offset = $distCount - count($distributors)) > 0) {
            $distributorsEnd = BinaryPlanManager::getDistributorsInTree(
                Auth::user(),
                $limit,
                $offset,
                $search,
                $currentNode,
                $leg
            );
        }

        return response()->json([
            'distributors' => $distributors,
            'distributorsEnd' => $distributorsEnd,
            'total' => $distCount,
        ]);
    }
}
