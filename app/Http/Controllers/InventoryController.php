<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockTransactionRequest;
use App\Models\Inventory;
use App\Models\StockTransaction;
use App\Services\LocationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InventoryController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }
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
        $transactions = $inventory->stockTransactions()->latest('transaction_date')->paginate(10);
        return view('inventory.show', compact('inventory', 'transactions'));
    }

    public function addTransaction(StoreStockTransactionRequest $request)
    {
        $inventory = Inventory::findOrFail($request->inventory_id);
        $qty  = $request->quantity;
        $type = $request->transaction_type;

        if ($type === 'Stock Out' && $qty > $inventory->quantity_available) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quantity' => '⚠️ Insufficient stock. Available: ' . $inventory->quantity_available]);
        }

        // Prepare location data
        $locationData = [
            'location_from'    => $request->location_from,
            'location_to'      => $request->location_to,
            'coordinates_from' => null,
            'coordinates_to'   => null,
        ];

        // Geocode `location_from` when address is present
        if ($request->location_from) {
            $coords = $this->locationService->geocode($request->location_from);
            if ($coords) {
                $locationData['coordinates_from'] = sprintf('%F, %F', $coords['lat'], $coords['lng']);
            }
        }

        // Geocode `location_to` when address is present
        if ($request->location_to) {
            $coordsTo = $this->locationService->geocode($request->location_to);
            if ($coordsTo) {
                $locationData['coordinates_to'] = sprintf('%F, %F', $coordsTo['lat'], $coordsTo['lng']);
            }
        }

        $transactionDate = now();

        DB::transaction(function () use ($inventory, $qty, $type, $transactionDate, $locationData) {
            $type === 'Stock In'
                ? $inventory->increment('quantity_available', $qty)
                : $inventory->decrement('quantity_available', $qty);

            $inventory->update(['last_updated' => now()]);

            StockTransaction::create([
                'inventory_id'     => $inventory->id,
                'transaction_type' => $type,
                'quantity'         => $qty,
                'transaction_date' => $transactionDate,
                'processed_by'     => auth()->id(),
                'location_from'    => $locationData['location_from'],
                'location_to'      => $locationData['location_to'],
                'coordinates_from' => $locationData['coordinates_from'],
                'coordinates_to'   => $locationData['coordinates_to'],
            ]);
        });

        return redirect()->route('inventory.show', $inventory)->with('success', 'Stock transaction recorded.');
    }
}
