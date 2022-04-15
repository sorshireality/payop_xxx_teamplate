<?php

namespace Payop\Tests;

use Payop\Exceptions\ApiClientNotFoundException;
use PHPUnit\Framework\TestCase;
use Payop\Traits\InvoiceTrait;
use stdClass;

class CreateInvoiceTest extends TestCase
{
    use InvoiceTrait;

    private stdClass $data;

    public function setUp(): void
    {
        require_once __DIR__ . "/../../vendor/autoload.php";
        $this->data = new stdClass();
        $this->data->order = new stdClass();
        $this->data->order->id = '15';
        $this->data->order->amount = 10500;
        $this->data->order->currency = 'EUR';
        $this->data->order->description = "You order %s in DemoShop";

//Basically there is a place for abstract collection, but ill skip it part of the code due to minimal effect on the payment process
        $basket = [];
        $basket["item_id"] = "102";
        $basket["description"] = "Best Milk ever!";
        $basket["amount"] = 5250;
        $basket["quantity"] = 2;
        $basket["tax_percentage"] = 0;


        $this->data->order->items = [
            $basket
        ];

        $this->data->customer = new stdClass();
        $this->data->customer->email = "cat@world.wide";
        $this->data->customer->phone = "12-12-55-23";
        $this->data->customer->name = "Oleg";
        $this->data->customer->extra = [];

        $this->data->success_url = "www.my_web_shop/payment/success";
        $this->data->fail_url = "www.my_web_shop/payment/fail";

    }

    public function test_collecting_data()
    {
        $expected = [
            'order' => [
                'id' => '15',
                'amount' => 10500,
                'currency' => 'EUR',
                'description' => 'You order %s in DemoShop',
                'items' => [
                    [
                        'item_id' => '102',
                        'description' => 'Best Milk ever!',
                        'amount' => 5250,
                        'quantity' => 2,
                        'tax_percentage' => 0,
                    ]
                ]
            ],
            'payer' => [
                'email' => 'cat@world.wide',
                'phone' => '12-12-55-23',
                'name' => 'Oleg',
                'extraFields' => [],
            ],
            'signature' => 'c539229014c1549b839f9588f9950bb0f2a2da9f31b14342cd6770eafcb2a56f',
            'paymentMethod' => '666',
            'language' => 'en',
            'resultUrl' => 'www.my_web_shop/payment/success',
            'failPath' => 'www.my_web_shop/payment/fail',

        ];
        $payment = new \Payop\XxxPayment("lineage2_pika_na_25");
        $real = $payment->collectInvoiceData(
            data: $this->data,
            secret_key: 'dota2_lose_before_creeps'
        );
        self::assertSame($expected, $real);
    }

    public function test_exception_api_client_not_found()
    {
        self::expectException(ApiClientNotFoundException::class);
        $this->createInvoice(
            data: $this->data,
            secret_key: "flowers_on_the_wall"
        );
    }
}