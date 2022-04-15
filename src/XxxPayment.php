<?php

namespace Payop;

use Payop\Client\ApiClient;
use Payop\Client\HttpClient;
use Payop\Traits\InvoiceTrait;

class XxxPayment
{
    use InvoiceTrait;

    const ID = '666';

    private ApiClient $api_client;

    public function __construct(string $public_key)
    {
        $this->api_client = new ApiClient(
            new HttpClient(
                endpoint: "https://payop.com/v1",
                public_key: $public_key
            )
        );
    }


    public function createHostedPage(\stdClass $data, string $secret_key)
    {
        $invoice_id = $this->createInvoice($data, $secret_key)["identifier"];
        http_redirect(
            sprintf("https://checkout.payop.com/%s/payment/invoice-preprocessing/%s", 'en', $invoice_id)
        );
    }
}