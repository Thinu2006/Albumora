<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'albums', 'payment', 'shipment']);
        
        if ($request->has('user_id')) {
            $query->where('customer_id', $request->user_id);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:users,id',
        'total_amount' => 'required|numeric|min:0',
        'status' => 'required|string|in:pending,accepted,declined,shipped,delivered,completed,cancelled',
        'albums' => 'required|array',
        'albums.*.id' => 'required|exists:albums,id',
        'albums.*.quantity' => 'required|integer|min:1',
        'albums.*.unit_price' => 'required|numeric|min:0',
        'shipment' => 'required|array',
        'shipment.address_line1' => 'required|string|max:255',
        'shipment.address_line2' => 'nullable|string|max:255',
        'shipment.city' => 'required|string|max:100',
        'shipment.state' => 'required|string|max:100',
        'shipment.country' => 'required|string|max:100',
        'payment' => 'required|array',
        'payment.cardholder_name' => 'required|string|max:255',
        'payment.card_number' => 'required|string|size:16',
        'payment.expiration_month' => 'required|string|size:2',
        'payment.expiration_year' => 'required|string|size:4',
        'payment.card_type' => 'required|string|in:visa,mastercard,amex,discover',
        'update_stock' => 'sometimes|boolean'
    ]);

    // Start database transaction
    DB::beginTransaction();

    try {
        // First check stock availability if we're updating stock
        if ($request->input('update_stock', false)) {
            foreach ($validated['albums'] as $album) {
                $dbAlbum = \App\Models\Album::find($album['id']);
                if ($dbAlbum->stock < $album['quantity']) {  // Changed from stock_quantity to stock
                    throw new \Exception("Not enough stock for album ID {$album['id']} (Requested: {$album['quantity']}, Available: {$dbAlbum->stock})");
                }
            }
        }

        // Create the order
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'total_amount' => $validated['total_amount'],
            'status' => $validated['status'],
        ]);

        // Attach albums with pivot data and update stock
        foreach ($validated['albums'] as $album) {
            $order->albums()->attach($album['id'], [
                'quantity' => $album['quantity'],
                'unit_price' => $album['unit_price']
            ]);

            // Update album stock if requested
            if ($request->input('update_stock', false)) {
                \App\Models\Album::where('id', $album['id'])
                    ->decrement('stock', $album['quantity']); 
            }
        }

        // Create shipment
        $shipment = $order->shipment()->create([
            'address_line1' => $validated['shipment']['address_line1'],
            'address_line2' => $validated['shipment']['address_line2'] ?? null,
            'city' => $validated['shipment']['city'],
            'state' => $validated['shipment']['state'],
            'country' => $validated['shipment']['country'],
        ]);

        // Create payment
        $payment = $order->payment()->create([
            'card_type' => $validated['payment']['card_type'],
            'cardholder_name' => $validated['payment']['cardholder_name'],
            'last_four' => substr($validated['payment']['card_number'], -4),
            'amount' => $validated['total_amount'],
            'payment_status' => 'paid',
            'transaction_id' => uniqid('tr_'),
            'payment_date' => now(),
        ]);

        DB::commit();

        return new OrderResource($order->load(['customer', 'albums', 'payment', 'shipment']));

    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'message' => 'Order processing failed: ' . $e->getMessage(),
            'error' => $e->getMessage()
        ], 409);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['customer', 'albums', 'payment', 'shipment'])->findOrFail($id);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,accepted,declined,shipped,delivered,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        
        // Prevent updating cancelled or completed orders
        if (in_array($order->status, ['cancelled', 'completed'])) {
            return response()->json([
                'message' => 'Cannot update a cancelled or completed order'
            ], 422);
        }
        
        $order->update($validated);
        return new OrderResource($order);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}