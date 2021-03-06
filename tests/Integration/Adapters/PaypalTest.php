<?php

namespace Tests\Integration\Repositories;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaypalTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->paypal = $this->app->make('EverestBill\Adapters\Paypal');
    }

    public function test_getAccessToken_WhenCalled_ReturnObjectWithAccessToken()
    {
        $result = $this->paypal->getAccessToken();

        $this->assertTrue(is_object($result));
        $this->assertTrue(is_string($result->access_token));
    }

    public function test_createPayment_WhenCalled_ReturnObjectWithPaymentId()
    {
        $accessTokenObject = $this->paypal->getAccessToken();

        $data = [
            'amount'      => 4,
            'planName'    => 'Test',
            'accessToken' => $accessTokenObject->access_token,
        ];

        $result = $this->paypal->createPayment($data);

        $this->assertTrue(is_object($result));
        $this->assertTrue(is_string($result->id));
    }

    public function test_executePayment_WhenCalledWithFakePaymentInfo_ReturnObjectWithUnapprovedMessage()
    {
        $accessTokenObject = $this->paypal->getAccessToken();

        $data = [
            'payer_id'   => '123',
            'payment_id' => 'PAY-5GS90686WC9195057LF25YDI'
        ];

        $result = $this->paypal->executePayment($accessTokenObject->access_token, $data);

        $this->assertTrue(is_object($result));
        $this->assertObjectHasAttribute('name', $result);
        $this->assertTrue(is_string($result->name));
        $this->assertEquals('INVALID_RESOURCE_ID', $result->name);
    }
}