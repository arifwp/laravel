<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;

class TransactionController extends Controller
{
    public function index()
    {
        $transaction = Transaction::latest()->paginate(5);
        return response()->json([
            'code' => 200,
            'message' => 'Get transaction success',
            'data' => $transaction,
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                $validator->errors()
            ], 422);
        }

        $hashApiKey = hash('sha256', 'POST:DATAUTAMA');
        $headers = [
            'Content-Type' => 'application/json',
            'X-API-KEY' => 'DATAUTAMA',
            'X-SIGNATURE' => $hashApiKey,
        ];

        $client = new GuzzleClient([
            'headers' => $headers,
        ]);
        
        $paymentAmount = $request->quantity * $request->product_id;

        $body = json_encode([
            "quantity" => $request->quantity,
            "price" => $request->product_id,
            "payment_amount" => $paymentAmount,
        ]);

        $hitApi = $client->request('POST', 'http://tes-skill.datautama.com/test-skill/api/v1/transactions', [
            'body' => $body
        ]);

        $response = json_decode($hitApi->getBody()->getContents());

        $transaction = Transaction::create([
            'reference_no' => $response->data->reference_no,
            'price' => $request->product_id,
            'quantity' => $request->quantity,
            'payment_amount' => $request->quantity * $request->product_id,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'code' => 200,
            'message'=> 'Success insert data',
            'data' => $transaction,
        ],200);
    }
}
