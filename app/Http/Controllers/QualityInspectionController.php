<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInspectionRequest;
use App\Models\Production;
use App\Models\QualityInspection;

class QualityInspectionController extends Controller
{
    public function index()
    {
        $inspections = QualityInspection::with(['production.product', 'inspector'])
            ->when(request('result'), fn($q, $r) => $q->where('result', $r))
            ->latest('inspection_date')->paginate(15)->withQueryString();
        return view('quality.index', compact('inspections'));
    }

    public function create()
    {
        $productions = Production::with('product')
            ->whereDoesntHave('qualityInspections', fn($q) => $q->where('result', 'Pass'))
            ->get();
        return view('quality.create', compact('productions'));
    }

    public function store(StoreInspectionRequest $request)
    {
        $data = $request->validated();
        $data['inspector_id'] = auth()->id(); // Always bind to authenticated QC Officer
        QualityInspection::create($data);
        return redirect()->route('quality.index')->with('success', 'Quality inspection recorded.');
    }

    public function show(QualityInspection $qualityInspection)
    {
        $qualityInspection->load(['production.product', 'inspector']);
        return view('quality.show', compact('qualityInspection'));
    }

    public function edit(QualityInspection $qualityInspection)
    {
        $productions = Production::with('product')->get();
        return view('quality.edit', compact('qualityInspection', 'productions'));
    }

    public function update(StoreInspectionRequest $request, QualityInspection $qualityInspection)
    {
        $qualityInspection->update($request->validated());
        return redirect()->route('quality.index')->with('success', 'Inspection updated.');
    }

    public function destroy(QualityInspection $qualityInspection)
    {
        $qualityInspection->delete();
        return redirect()->route('quality.index')->with('success', 'Inspection record deleted.');
    }
}
