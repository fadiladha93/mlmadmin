<?php

namespace App\Http\Controllers;

use App\Facades\BinaryPlanManager;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;

/**
 * Class BinaryModificationController
 * @package App\Http\Controllers
 */
class BinaryModificationController extends Controller {

    const ROOT_SPONSOR_UID = 242;
    const REQUEST_EXECUTION_TIME = 60 * 30;

    public function __construct() {
        $this->middleware('auth.admin');
        $this->middleware(function ($request, $next) {
            if (!(\App\User::admin_super_admin() ||
                    \App\User::admin_super_exec()
                    )) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect('/');
                }
            }
            return $next($request);
        });
        set_time_limit(self::REQUEST_EXECUTION_TIME);
    }

    /**
     * @param Request $request
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($type) {
        switch ($type) {
            case 'move':
                return view('admin.binary_modification.move');
            case 'replace':
                return view('admin.binary_modification.replace');
            case 'terminate':
                return view('admin.binary_modification.terminate');
            case 'import':
                return view('admin.binary_modification.import');
            case 'insert':
                return view('admin.binary_modification.insert');
            default:
                return view('admin.errors.404');
        }
    }

    public function postAjaxInsertLeg(Request $request) {
        set_time_limit(1800);
        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'direction' => 'required|in:left,right',
                    'parentTsa' => 'required',
                    'agentTsa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tsa = $body['agentTsa'];
        $newParentTsa = $body['parentTsa'];
        $direction = $body['direction'];

        // user check
        $newAgent = User::where('distid', $tsa)->first();
        if (!$newAgent) {
            return response()->json(
                            ['error' => 'TSA is not found in the system'], Response::HTTP_BAD_REQUEST
            );
        }

        // user node check
        $newAgentNode = BinaryPlanManager::getNodeByAgentTsa($tsa);
        if ($newAgentNode) {
            // check the node is single without children and parents
            if(BinaryPlanManager::isSingleDetached($newAgentNode)) {
                $newNode = $newAgentNode;
            } else {
                return response()->json(
                    ['error' => 'TSA already exists in the tree'], Response::HTTP_BAD_REQUEST
                );
            }
        } else {
            $newNode = BinaryPlanManager::createNode($newAgent);
        }

        // parent node check
        $parentNode = BinaryPlanManager::getNodeByAgentTsa($newParentTsa);
        if (!$parentNode) {
            return response()->json(
                            ['error' => 'TSA is not found in the tree'], Response::HTTP_BAD_REQUEST
            );
        }

        // process inserting
        BinaryPlanManager::insertAfter($parentNode, $newNode, $direction);

        return response()->json(
                        ['message' => 'Success'], Response::HTTP_OK
        );
    }

    public function postAjaxMoveLeg(Request $request) {
        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'direction' => 'required|in:left,right',
                    'fromTsa' => 'required',
                    'toTsa' => 'required',
                    'isMirror' => 'required|boolean',
                    'isDownline' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $fromTsa = $body['fromTsa'];
        $toTsa = $body['toTsa'];
        $direction = $body['direction'];
        $isMirror = $body['isMirror'];
        $isDownline = $body['isDownline'];

        $fromNode = BinaryPlanManager::getNodeByAgentTsa($fromTsa);
        if (!$fromNode) {
            return response()->json(
                            ['error' => 'TSA is not exists in the tree'], Response::HTTP_BAD_REQUEST
            );
        }

        if ($fromNode->user_id === self::ROOT_SPONSOR_UID && $fromNode->parent_id === null) {
            return response()->json(
                            ['error' => 'Cannot move the root sponsor'], Response::HTTP_BAD_REQUEST
            );
        }

        $toNode = BinaryPlanManager::getNodeByAgentTsa($toTsa);
        if (!$toNode) {
            return response()->json(
                            ['error' => 'TSA is not found in the tree'], Response::HTTP_BAD_REQUEST
            );
        }

        if ($fromNode->id === $toNode->id) {
            return response()->json(
                            ['error' => 'TSA Numbers should be different'], Response::HTTP_BAD_REQUEST
            );
        }

        try {
            BinaryPlanManager::moveNode($fromNode, $toNode, $direction, $isMirror, $isDownline);
        } catch (\Exception $e) {
            return response()->json(
                            ['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST
            );
        }

        // start move process

        return response()->json(
                        ['message' => 'Success'], Response::HTTP_OK
        );
    }

    public function postAjaxReplaceLeg(Request $request) {
        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'fromTsa' => 'required',
                    'toTsa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $fromTsa = $body['fromTsa'];
        $toTsa = $body['toTsa'];

        // user node check
        $fromNode = BinaryPlanManager::getNodeByAgentTsa($fromTsa);
        $toNode = BinaryPlanManager::getNodeByAgentTsa($toTsa);

        if (!$fromNode) {
            $newAgent = User::where('distid', $fromTsa)->first();

            if ($newAgent) {
                $fromNode = BinaryPlanManager::createNode($newAgent);
            } else {
                return response()->json(
                                ['error' => 'TSA is not found in the system'], Response::HTTP_BAD_REQUEST
                );
            }
        }

        if (!$toNode) {
            return response()->json(
                            ['error' => 'TSA is not found in the tree'], Response::HTTP_BAD_REQUEST
            );
        }

        if ($fromNode->user_id === $toNode->user_id) {
            return response()->json(
                            ['error' => 'Agents should have different TSA numbers'], Response::HTTP_BAD_REQUEST
            );
        }

        try {
            // try to replace nodes
            BinaryPlanManager::replaceWith($fromNode, $toNode);
        } catch (\Exception $e) {
            return response()->json(
                            ['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
                        ['message' => 'Success'], Response::HTTP_OK
        );
    }

    public function postAjaxTerminateAgent(Request $request) {
        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'agentTsa' => 'required',
                    'action' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $agentTsa = $body['agentTsa'];
        $action = $body['action'];

        // user node check
        $agentNode = BinaryPlanManager::getNodeByAgentTsa($agentTsa);

        if (!$agentNode) {
            return response()->json(
                            ['error' => 'TSA is not found in the tree'], Response::HTTP_BAD_REQUEST
            );
        }

        try {
            switch ($action) {
                case 'delete':
                    BinaryPlanManager::deleteNode($agentNode);
                    break;
                case 'inactivate':
                    BinaryPlanManager::inactivateNode($agentNode);
                    break;
                case 'reactivate':
                    BinaryPlanManager::reactivateNode($agentNode);
                    break;
                default:
                    throw new \Exception('Selected action cannot be applied for the agent.');
            }
        } catch (\Exception $e) {
            return response()->json(
                            ['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json(
                        ['message' => 'Success'], Response::HTTP_OK
        );
    }

    public function getAjaxAgentTsa(Request $request) {
        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'agentTsa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $agentTsa = $body['agentTsa'];

        // user check
        /** @var User $newAgent */
        $newAgent = User::where('distid', $agentTsa)->first();
        if (!$newAgent) {
            return response()->json(
                            ['error' => 'TSA is not found in the system'], Response::HTTP_BAD_REQUEST
            );
        }

        // check existing node in the binary tree
        /** @var User $newAgent */
        $existingNode = BinaryPlanManager::getNodeByAgentTsa($agentTsa);
        if ($existingNode) {
            if (!BinaryPlanManager::isSingleDetached($existingNode)) {
                return response()->json(
                    ['error' => 'TSA already exists in the tree'], Response::HTTP_BAD_REQUEST
                );
            }
        }

        return response()->json([
                    'data' => [
                        'firstname' => $newAgent->firstname,
                        'lastname' => $newAgent->lastname,
                        'username' => $newAgent->username,
                        'tsanumber' => $newAgent->distid,
                        'enrollmentdate' => $newAgent->getEnrolledDate(),
                        'class' => $newAgent->product ? $newAgent->product->productname : 'No Product',
                        'rank' => $newAgent->rank()->rankdesc,
                    ]
                        ], Response::HTTP_OK);
    }

    public function getAjaxParentTsa(Request $request) {
        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'parentTsa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $parentTsa = $body['parentTsa'];

        // user check
        /** @var User $newAgent */
        // parent node check
        $parentNode = BinaryPlanManager::getNodeByAgentTsa($parentTsa);
        if (!$parentNode) {
            return response()->json(
                            ['error' => 'TSA is not found in the tree'], Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json([
                    'data' => [
                        'firstname' => $parentNode->user->firstname,
                        'lastname' => $parentNode->user->lastname,
                        'username' => $parentNode->user->username,
                        'tsanumber' => $parentNode->user->distid,
                        'enrollmentdate' => $parentNode->user->getEnrolledDate(),
                        'class' => $parentNode->user->product ? $parentNode->user->product->productname : 'No Product',
                        'rank' => $parentNode->user->rank()->rankdesc,
                    ]
                        ], Response::HTTP_OK);
    }

    public function getAjaxNodeInBinaryTree(Request $request) {
        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'nodeTsa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $nodeTsa = $body['nodeTsa'];

        /** @var User $newAgent */
        $nodeTsa = BinaryPlanManager::getNodeByAgentTsa($nodeTsa);
        if (!$nodeTsa) {
            return response()->json(
                            ['error' => 'TSA is not found in the tree'], Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json([
                    'data' => [
                        'firstname' => $nodeTsa->user->firstname,
                        'lastname' => $nodeTsa->user->lastname,
                        'username' => $nodeTsa->user->username,
                        'tsanumber' => $nodeTsa->user->distid,
                        'enrollmentdate' => $nodeTsa->user->getEnrolledDate(),
                        'class' => $nodeTsa->user->product ? $nodeTsa->user->product->productname : 'No Product',
                        'rank' => $nodeTsa->user->rank()->rankdesc,
                        'active' => $nodeTsa->user->isActiveStatus(),
                    ]
                        ], Response::HTTP_OK);
    }

    public function getAjaxAgentReplaceTsa(Request $request) {
        $body = $request->json()->all();

        $validator = Validator::make($body, [
                    'agentTsa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $agentTsa = $body['agentTsa'];

        // user check
        /** @var User $newAgent */
        $newAgent = User::where('distid', $agentTsa)->first();
        if (!$newAgent) {
            return response()->json(
                            ['error' => 'TSA is not found in the system'], Response::HTTP_BAD_REQUEST
            );
        }

        return response()->json([
                    'data' => [
                        'firstname' => $newAgent->firstname,
                        'lastname' => $newAgent->lastname,
                        'username' => $newAgent->username,
                        'tsanumber' => $newAgent->distid,
                        'enrollmentdate' => $newAgent->getEnrolledDate(),
                        'class' => $newAgent->product ? $newAgent->product->productname : 'No Product',
                        'rank' => $newAgent->rank()->rankdesc,
                    ]
                        ], Response::HTTP_OK);
    }

}
