<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductionRequest;
use App\Models\Product;
use App\Models\Production;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with('product')
            ->when(request('status'), fn($q, $s) => $q->where('status', $s))
            ->when(request('product'), fn($q, $p) => $q->where('product_id', $p))
            ->latest('production_date')->paginate(15)->withQueryString();
        $products = Product::where('status', '!=', 'Discontinued')->get();
        return view('production.index', compact('productions', 'products'));
    }

    public function create()
    {
        $products = Product::whereIn('status', ['Active', 'In Production'])->get();
        return view('production.create', compact('products'));
    }

    public function store(StoreProductionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $production = Production::create($request->validated());
            $production->product->update(['status' => 'In Production']);
        });
        return redirect()->route('production.index')->with('success', 'Production run created successfully.');
    }

    public function show(Production $production)
    {
        $production->load(['product', 'qualityInspections.inspector', 'financeRecords.recorder']);
        return view('production.show', compact('production'));
    }

    public function edit(Production $production)
    {
        $products = Product::all();
        return view('production.edit', compact('production', 'products'));
    }

    public function update(StoreProductionRequest $request, Production $production)
    {
        $wasCompleted  = $production->status === 'Completed';
        $isNowCompleted = $request->status === 'Completed';

        DB::transaction(function () use ($request, $production, $wasCompleted, $isNowCompleted) {
            $production->update($request->validated());

            if (!$wasCompleted && $isNowCompleted) {
                $inventory = $production->product->inventory;
                if ($inventory) {
                    $inventory->increment('quantity_available', $production->quantity_produced);
                    $inventory->update(['last_updated' => now()]);
                    $inventory->stockTransactions()->create([
                        'transaction_type' => 'Stock In',
                        'quantity'         => $production->quantity_produced,
                        'transaction_date' => now(),
                        'processed_by'     => auth()->id(),
                    ]);
                }
                $production->product->update(['status' => 'Active']);
            }
        });

        return redirect()->route('production.index')->with('success', 'Production run updated.');
    }

    public function destroy(Production $production)
    {
        if ($production->status === 'Completed') {
            return back()->with('error', 'Completed production runs cannot be deleted.');
        }
        $production->delete();
        return redirect()->route('production.index')->with('success', 'Production run deleted.');
    }
}
