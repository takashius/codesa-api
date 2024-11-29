<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // Configurar las credenciales de MercadoPago
        MercadoPagoConfig::setAccessToken('APP_USR-2167728592546578-112619-e3400fb784e32508fc61a624d5759b4b-2115836977');
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        $idempotenceKey = uniqid('', true);

        $data = $request->all();

        $client = new PaymentClient();
        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: $idempotenceKey"]);

        try {
            $payment = $client->create($data, $request_options);

            return response()->json(['payment' => $payment]);
        } catch (MPApiException $e) {
            var_dump($e->getApiResponse()->getContent());
            return response()->json([
                'error' => $e->getMessage(),
                'response' => $e->getApiResponse(),
                'trace' => $e->getTrace(),
                'code' => $e->getStatusCode()
            ], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
