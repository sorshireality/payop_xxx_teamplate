<?php

namespace Payop\Traits;

use Payop\Exceptions\ApiClientNotFoundException;

trait InvoiceTrait
{
    public function collectInvoiceData($data, $secret_key): array
    {
        return array_filter(
            [
                "order" => [
                    "id" => $data->order->id,
                    "amount" => $data->order->amount,
                    "currency" => $data->order->currency,
                    "description" => $data->order->description,
                    "items" => $data->order->items
                ],
                "payer" => [
                    "email" => $data->customer->email,
                    "phone" => $data->customer->phone,
                    "name" => $data->customer->name,
                    "extraFields" => $data->customer->extra
                ],
                "signature" => $this->getSignature(
                    order_id: $data->order->id,
                    order_amount: $data->order->amount,
                    order_currency: $data->order->currency,
                    secretKey: $secret_key
                ),
                "paymentMethod" => static::ID,
                "language" => "en",
                "resultUrl" => $data->success_url,
                'failPath' => $data->fail_url
            ]
        );
    }

    public function createInvoice(object $data, string $secret_key): array
    {
        if (!isset($this->api_client)) {
            throw new ApiClientNotFoundException();
        }
        return $this->api_client->createInvoice(
            data: $this->collectInvoiceData(
                data: $data,
                secret_key: $secret_key),
        );
    }

    public function getSignature($order_id, $order_amount, $order_currency, $secretKey): false|string
    {
        $order = ['id' => $order_id, 'amount' => $order_amount, 'currency' => $order_currency];
        ksort($order, SORT_STRING);
        $dataSet = array_values($order);
        $dataSet[] = $secretKey;
        return hash('sha256', implode(':', $dataSet));
    }
}