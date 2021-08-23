<?php

namespace Tests\Unit;

use App\Address;
use App\Console\Commands\UpdateInventoryPendingTotals;
use App\Models\OrderConversion;
use App\Order;
use App\PaymentMethod;
use App\PaymentMethodType;
use App\Product;
use App\Services\SubscriptionCronService;
use App\SubscriptionHistory;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SubscriptionCronServiceTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var SubscriptionCronService
     */
    private $cron;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'estimated_balance' => 100,
            'original_subscription_date' => '1000-01-05 00:00:00',
            'next_subscription_date' => '1000-01-05 00:00:00',
            'subscription_product' => 11
        ]);

        $this->cron = $this->getMockBuilder('App\Services\SubscriptionCronService')
        ->setMethods([
            'detokenizeCard',
            'convertCurrency',
            'chargePaymentGateway',
        ])->getMock();
    }

    public function tearDown()
    {
        $this->user->delete();
        parent::tearDown();
    }

    public function testEwallet()
    {
        $this->cron->run('1000-03-01');

        $this->user->refresh();

        $product = Product::find(11);
        $expectedBalance = 100 - $product->price;

        $this->assertEquals($expectedBalance, $this->user->estimated_balance);
        $expectedDate = now()->day(5)->addMonthNoOverflow()->format('Y-m-d 00:00:00');
        $this->assertEquals($expectedDate, $this->user->original_subscription_date->format('Y-m-d H:i:s'));
        $this->assertEquals($expectedDate, $this->user->next_subscription_date->format('Y-m-d H:i:s'));
        $order = $this->user->orders()->first();

        if (!$order) {
            $this->fail('No order found!');
        }

        $this->assertEquals(0, $order->conversion()->count());
    }

    public function testNoEwalletNoCard()
    {
        $this->user->update([
            'estimated_balance' => 0,
            'subscription_attempts' => 0
        ]);

        $this->cron->run('1000-03-01');

        $this->user->refresh();

        // Should have not changed
        $this->assertEquals(0, $this->user->estimated_balance);

        // Suspended because no card
        $expectedDate = now()->day(5)->addMonthNoOverflow()->format('Y-m-d 00:00:00');
        $this->assertEquals($expectedDate, $this->user->next_subscription_date->format('Y-m-d H:i:s'));
        $this->assertEquals(1, $this->user->subscription_attempts);
        $this->assertEquals(1, $this->user->is_sites_deactivate);

        $order = $this->user->orders()->first();

        if ($order) {
            $this->fail('Order found!');
        }
    }

    public function testCard()
    {
        $this->cron->expects($this->once())
            ->method('detokenizeCard')
            ->will($this->returnValue(4111111111111111));

        $this->cron->expects($this->never())
            ->method('convertCurrency');

        $mockPaymentResult = [
            'error' => 0,
            'transactionid' => Str::random()
        ];

        $this->cron->expects($this->once())
            ->method('chargePaymentGateway')
            ->will($this->returnValue($mockPaymentResult));

        $this->user->update([
            'estimated_balance' => 0,
            'subscription_attempts' => 0
        ]);

        $address = factory(Address::class)->create([
            'userid' => $this->user->id,
            'countrycode' => 'US'
        ]);

        $paymentMethod = factory(PaymentMethod::class)->create([
            'userID' => $this->user->id,
            'bill_addr_id' => $address->id
        ]);

        $this->cron->run('1000-03-01');

        $this->user->refresh();

        // Should have not changed
        $this->assertEquals(0, $this->user->estimated_balance);

        $expectedDate = now()->setDate(now()->year, now()->month + 1, 05)->format('Y-m-d 00:00:00');
        $this->assertEquals($expectedDate, $this->user->original_subscription_date->format('Y-m-d H:i:s'));
        $this->assertEquals($expectedDate, $this->user->next_subscription_date->format('Y-m-d H:i:s'));
        $this->assertEquals(0, $this->user->subscription_attempts);

        // Should not have been suspended
        $this->assertEquals(User::ACC_STATUS_APPROVED, $this->user->account_status);
        $this->assertEquals(0, $this->user->is_sites_deactivate);

        $order = $this->user->orders()->first();

        if (!$order) {
            $this->fail('No order found!');
        }

        $this->assertEquals(0, $order->conversion()->count());
    }

    public function testCardWithConversion()
    {
        $this->cron->expects($this->once())
            ->method('detokenizeCard')
            ->will($this->returnValue(4111111111111111));

        $mockCurrencyResult = [
            'success' => 1,
            'errors' => [],
            'amount' => '4939',
            'currency' => 'MXN',
            'display_amount' => '49.39 MXN',
            'rate' => 49.39
        ];

        $this->cron->expects($this->once())
            ->method('convertCurrency')
            ->will($this->returnValue($mockCurrencyResult));

        $mockPaymentResult = [
            'error' => 0,
            'transactionid' => Str::random(),
            'currency' => 'MXN',
            'currencyAmount' => 4939,
            'displayAmount' => '49.39 MXN',
            'exchange_rate' => 49.39
        ];

        $this->cron->expects($this->once())
            ->method('chargePaymentGateway')
            ->will($this->returnValue($mockPaymentResult));

        $this->user->update([
            'estimated_balance' => 0,
            'subscription_attempts' => 0
        ]);

        $address = factory(Address::class)->create([
            'userid' => $this->user->id,
            'countrycode' => 'MX'
        ]);

        $paymentMethod = factory(PaymentMethod::class)->create([
            'userID' => $this->user->id,
            'bill_addr_id' => $address->id
        ]);

        $this->cron->run('1000-03-01');

        $this->user->refresh();

        // Should have not changed
        $this->assertEquals(0, $this->user->estimated_balance);

        $expectedDate = now()->day(5)->addMonthNoOverflow()->format('Y-m-d 00:00:00');
        $this->assertEquals($expectedDate, $this->user->next_subscription_date->format('Y-m-d H:i:s'));
        $this->assertEquals($expectedDate, $this->user->original_subscription_date->format('Y-m-d H:i:s'));
        $this->assertEquals(0, $this->user->subscription_attempts);

        // Should not have been suspended
        $this->assertEquals(User::ACC_STATUS_APPROVED, $this->user->account_status);
        $this->assertEquals(0, $this->user->is_sites_deactivate);

        $order = $this->user->orders()->first();

        if (!$order) {
            $this->fail('No order found!');
        }

        $this->assertEquals(1, $order->conversion()->count());
    }
}
