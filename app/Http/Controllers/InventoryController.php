<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockTransactionRequest;
use App\Models\Inventory;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('product.category')
            ->when(request('low_stock'), fn($q) => $q->where('quantity_available', '<', 10))
            ->latest('last_updated')->paginate(15)->withQueryString();
        return view('inventory.index', compact('inventories'));
    }

    public function show(Inventory $inventory)
    {
        $inventory->load(['product', 'stockTransactions.processor']);
        $transactions = $inventory->stockTransactions()->latest()->paginate(10);
        return view('inventory.show', compact('inventory', 'transactions'));
    }

    public function addTransaction(StoreStockTransactionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $inventory = Inventory::findOrFail($request->inventory_id);
            $qty  = $request->quantity;
            $type = $request->transaction_type;

            if ($type === 'Stock Out' && $qty > $inventory->quantity_available) {
                throw new \Exception('Insufficient stock. Available: ' . $inventory->quantity_available);
            }

            $type === 'Stock In'
                ? $inventory->increment('quantity_available', $qty)
                : $inventory->decrement('quantity_available', $qty);

            $inventory->update(['last_updated' => now()]);

            StockTransaction::create([
                'inventory_id'     => $inventory->id,
                'transaction_type' => $type,
                'quantity'         => $qty,
                'transaction_date' => $request->transaction_date,
                'processed_by'     => auth()->id(),
            ]);
        });

        return redirect()->route('inventory.index')->with('success', 'Stock transaction recorded.');
    }
}
