<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinanceRecordRequest;
use App\Models\FinanceRecord;
use App\Models\Production;

class FinanceRecordController extends Controller
{
    public function index()
    {
        $records = FinanceRecord::with(['production.product', 'recorder'])
            ->when(request('cost_type'), fn($q, $t) => $q->where('cost_type', $t))
            ->when(request('month'), fn($q, $m) => $q->whereMonth('record_date', $m))
            ->latest('record_date')->paginate(15)->withQueryString();

        $costSummary = FinanceRecord::selectRaw('cost_type, SUM(amount) as total')
            ->whereMonth('record_date', now()->month)
            ->groupBy('cost_type')->pluck('total', 'cost_type');

        return view('finance.index', compact('records', 'costSummary'));
    }

    public function create()
    {
        $productions = Production::with('product')->latest()->get();
        return view('finance.create', compact('productions'));
    }

    public function store(StoreFinanceRecordRequest $request)
    {
        $data = $request->validated();
        $data['recorded_by'] = auth()->id();
        FinanceRecord::create($data);
        return redirect()->route('finance.index')->with('success', 'Finance record added successfully.');
    }

    public function show(FinanceRecord $financeRecord)
    {
        $financeRecord->load(['production.product', 'recorder']);
        return view('finance.show', compact('financeRecord'));
    }

    public function edit(FinanceRecord $financeRecord)
    {
        $productions = Production::with('product')->get();
        return view('finance.edit', compact('financeRecord', 'productions'));
    }

    public function update(StoreFinanceRecordRequest $request, FinanceRecord $financeRecord)
    {
        $financeRecord->update($request->validated());
        return redirect()->route('finance.index')->with('success', 'Finance record updated.');
    }

    public function destroy(FinanceRecord $financeRecord)
    {
        $financeRecord->delete();
        return redirect()->route('finance.index')->with('success', 'Finance record deleted.');
    }
}
