<?php

namespace App\Http\Controllers;

use App\Exceptions\BinaryNodeInUseException;
use App\Exceptions\BinaryNodeIsChangedException;
use App\Facades\HoldingTank;
use App\Models\BinaryPlanNode;
use App\Models\SiteSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;
use Illuminate\Http\Response;
use Validator;
use App\Facades\BinaryPlanManager;

class HoldingTankController extends Controller {

    /**
     * HoldingTankController constructor.
     */
    public function __construct() {
        $this->middleware('auth.affiliate');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        if (!$this->isHoldingTankEnable()) {
            return redirect()->route('page404');
        }
        if (!\App\BinaryPermission::isPermit(Auth::user()->distid)) {
            return redirect('/');
        }
        HoldingTank::getOrCreateRootNode(Auth::user());
        // will be replaced with database seeds (create a root node)
        $rootNode = HoldingTank::getRootBinaryNode(Auth::user());
        $search = $request->input('distributor_search');

        return view('affiliate.holding_tank.index')
                        ->with([
                            'options' => HoldingTank::getLastNodes($rootNode),
                            'distributors' => HoldingTank::getFreeDistributorsList(Auth::user(), $search),
                            'distributorSearch' => $search ?: '',
        ]);
    }

    public function postAjaxDistributors(Request $request) {
        if (!$this->isHoldingTankEnable()) {
            throw new \Exception('Placement Lounge feature is disabled.');
        }

        set_time_limit(0);

        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'direction' => 'required|in:left,right,auto',
                    'distributors' => 'required|array',
                    'distributors.*' => 'integer',
                    'nodeId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        // delete invalid users that not belongs to this root node (security reasons)
        $distributors = HoldingTank::filterDistributors($body['distributors'], Auth::user());

        if (!$distributors->count()) {
            throw new \Exception('There is no any relevant distributor for the target node.');
        }

        $direction = $body['direction'];
        $targetNodeId = $body['nodeId'];

        // will check if the target node belongs to the current user structure (security reasons)
        $targetNode = HoldingTank::filterTargetNode($targetNodeId);

        if (!$targetNode) {
            throw new \Exception('Invalid target node.');
        }

        try {
            HoldingTank::placeAgentsToBinaryViewer($targetNode, $distributors, $direction);
        } catch (BinaryNodeInUseException $exception) {
            return response()->json(
                            ['error' => 'Node with the target user is already exists.'], Response::HTTP_BAD_REQUEST
            );
        } catch (BinaryNodeIsChangedException $exception) {
            return response()->json(
                            [
                        'message' => sprintf(
                                'The distributor has been placed under the [#%s - #%s]', $exception->getNode()->user->distid, $exception->getNode()->user->username
                        ),
                        'options' => HoldingTank::getLastNodes(HoldingTank::getRootBinaryNode(Auth::user())),
                            ], Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            throw $exception;
        }

        // notify users
        $this->notifyUsers($distributors);

        return response()->json([
                    'options' => HoldingTank::getLastNodes(HoldingTank::getRootBinaryNode(Auth::user()))
        ]);
    }

    private function notifyUsers($distributors) {
        foreach ($distributors as $dist) {
            // send sms
            if (!\utill::isNullOrEmpty($dist->phonenumber)) {
                \MySMS::sendAddedToBinaryTree($dist->firstname, $dist->lastname, $dist->distid, "+" . $dist->phonenumber);
            }

            // send email
            try {
                \MyMail::sendAddedToBinaryTree($dist->firstname, $dist->lastname, $dist->distid, $dist->email);
            } catch (\GuzzleHttp\Exception\ClientException $ex) {

            }
        }
    }

    private function isHoldingTankEnable() {
        /** @var Model $holdingTankSetting */
        $holdingTankSetting = SiteSettings::where('key', 'is_holding_tank_active')->first();

        if (!$holdingTankSetting) {
            return false;
        }

        return intval($holdingTankSetting->value) === 1;
    }

}
