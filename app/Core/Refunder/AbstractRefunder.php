<?php


namespace App\Core\Refunder;

use App\Core\Refunder\Finishers\IbuumerangPacksFinisher;
use App\Core\Refunder\Finishers\VoucherFinisher;
use App\DiscountCoupon;
use App\Helper;
use App\Order;
use App\OrderItem;
use App\Product;
use App\ProductType;
use App\SaveOn;
use App\Services\RefundService;
use App\UpdateHistory;
use App\User;
use App\Core\Refunder\Finishers\UpgradeFinisher;
use App\Core\Refunder\Finishers\EnrollmentFinisher;

abstract class AbstractRefunder
{
    /**
     * @var Order
     */
    protected $order;

    /** @var User */
    protected $user;

    /** @var string */
    protected $refundCurrency;

    /** @var string */
    protected $refundAmount;

    /** @var string */
    protected $refundId;

    /**
     * @var int
     */
    private $status;

    /** @var string */
    private $message;

    /** @var RefundService */
    protected $refundService;


    public abstract function refund();
    public abstract function finish();
    public abstract function createRefundOrder($refundQV = 0);

    /**
     * @param OrderItem $orderItem
     * @return AbstractRefunder
     * @throws \Exception
     */
    protected function finishOrderItem($orderItem)
    {
        /**@var Product $product */
        if (!$product = Product::where('id', $orderItem->productid)->first()) {
            return $this;
        }

        // handle exceptions
        $productType = $product->producttype;
        if ($productType === ProductType::TYPE_MEMBERSHIP
            && $product->id == Product::ID_IBUUMERANG_25
        ) {
            $productType = ProductType::TYPE_BOOMERANG;
        }

        switch ($productType) {
            case Product::ENROLLMENT:

                $finisher = new EnrollmentFinisher($product, $this->order);
                $finisher->finish();
                break;
            case Product::UPGRADES:
                $finisher = new UpgradeFinisher($product, $this->order);
                $finisher->finish();
                break;
            case ProductType::TYPE_BOOMERANG:
                $finisher = new IbuumerangPacksFinisher($product, $this->order);
                $finisher->finish();
                break;
            case ProductType::TYPE_PRE_PAID_CODES:
                $finisher = new VoucherFinisher($product, $this->order);
                $finisher->finish();
                break;
            default:
                break;
        }

        // set the order item as refunded
        $this->updateOrderItemRefundStatus($orderItem);
    }

    protected function updateOrderStatus($statusCode)
    {
        $this->order->statuscode = $statusCode;
        $this->order->save();
    }

    /**
     * @param OrderItem $orderItem
     */
    protected function updateOrderItemRefundStatus($orderItem)
    {
        OrderItem::where('id', $orderItem->id)->update(['is_refunded' => 1]);
    }

    /**
     * @param null $orderItem
     * @return AbstractRefunder
     */
    public function terminateUser($orderItem = null)
    {
        // only disable if there is a standby product
        if (!$orderItem) {
            $orderItem = $this->order->getStandByOrderItem();
        }

        if (empty($orderItem) || $orderItem->productid !== Product::ID_NCREASE_ISBO) {
            $this->message = 'Refund successful but was not able to terminate user.';
            return $this;
        }

        $user = User::query()->where('id', $this->order->userid)->first();
        $downlineUsers = User::select('id')
            ->where('sponsorid', $user->distid)
            ->pluck('id')
            ->toArray();

        $processType = (empty($downlineUsers) ? '' : 'UPDATE_SPONSOR');
        User::where('id', $this->order->userid)
            ->update(['account_status' => \App\User::ACC_STATUS_TERMINATED]);

        if ($processType == 'UPDATE_SPONSOR') {
            $newSponsorId = $this->getActiveSponsor($user->sponsorid);

            foreach ($downlineUsers as $userId) {
                \App\SponsorUpdateHistory::insert([
                    'user_id' => $userId,
                    'f_sponsor' => $user->distid,
                    't_sponsor' => $newSponsorId,
                    'status' => 'AFTER REFUND',
                    'created_at' => \utill::getCurrentDateTime()
                ]);
            }

            // update user
            User::query()
                ->whereIn('id', $downlineUsers)
                ->update(['sponsorid' => $newSponsorId]);
        }

        DiscountCoupon::query()
            ->where('code', $this->order->coupon_code)
            ->where('used_by', $this->order->userid)
            ->update([
                'is_used' => 0,
                'used_by' => null
            ]);

        Helper::deActivateIdecideUser($user->id);
        SaveOn::disableUser(
            $orderItem->productid,
            $user->distid,
            SaveOn::USER_DISABLE_CHANGE_NOTE
        );

        return $this;
    }

    private function getActiveSponsor($sponsorId)
    {
        $sponsor = \App\User::select('*')->where('distid', $sponsorId)->first();
        if (!empty($sponsor) && $sponsor->account_status == \App\User::ACC_STATUS_APPROVED) {
            return $sponsor->distid;
        } else {
            $this->getActiveSponsor($sponsor->sponsorid);
        }

        return $sponsor;
    }

    /**
     * @return AbstractRefunder
     */
    public function suspendUser()
    {
        User::where('id', $this->order->userid)
            ->update(['account_status' => \App\User::ACC_STATUS_SUSPENDED]);

        return $this;
    }

    /**
     * @param int $status
     * @param string $message
     * @return AbstractRefunder
     */
    public function setResponse($status, $message)
    {
        // set responses
        $this->status  = $status;
        $this->message = $message;

        return $this;
    }
    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function logAction()
    {
        UpdateHistory::addNew(
            UpdateHistory::TYPE_ORDER, $this->order->id,
            ['original_order_id' => $this->order->id],
            ['refund_order_id' => $this->refundId],
            UpdateHistory::MODE_REFUND
        );
    }
}
