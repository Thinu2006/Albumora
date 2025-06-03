<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with('order')->paginate(10);
        return PaymentResource::collection($payments);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'card_type' => 'required|string|in:visa,mastercard,amex,discover',
            'cardholder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16', 
            'expiration_month' => 'required|string|size:2',
            'expiration_year' => 'required|string|size:4',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Basic "validation" of card 
        $isValid = $this->validateCardDetails($validated);
        
        $payment = Payment::create([
            'order_id' => $validated['order_id'],
            'card_type' => $validated['card_type'],
            'cardholder_name' => $validated['cardholder_name'],
            'last_four' => substr($validated['card_number'], -4),
            'amount' => $validated['amount'],
            'payment_status' => $isValid ? 'paid' : 'failed',
            'transaction_id' => uniqid('tr_'),
            'payment_date' => now(),
        ]);

        return new PaymentResource($payment->load('order'));
    }

    private function validateCardDetails($data)
    {
        // Very basic validation 
        $currentYear = date('y');
        $currentMonth = date('m');
        
        $expired = ($data['expiration_year'] < $currentYear) || 
                ($data['expiration_year'] == $currentYear && 
                $data['expiration_month'] < $currentMonth);
                
        return !$expired && is_numeric($data['card_number']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with('orders')->findOrFail($id);
        return new PaymentResource($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
