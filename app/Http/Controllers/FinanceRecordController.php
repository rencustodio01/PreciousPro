<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinanceRecordRequest;
use App\Models\FinanceRecord;
use App\Models\Production;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

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

        $exchangeCurrencies = ['PHP', 'EUR', 'JPY', 'CNY'];

        return view('finance.index', compact('records', 'costSummary', 'exchangeCurrencies'));
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

    public function exchangeRates(): JsonResponse
    {
        $symbols = 'PHP,EUR,JPY,CNY';
        $accessKey = trim(env('EXCHANGERATE_API_KEY', ''));
        $http = Http::timeout(10)
            ->retry(2, 100)
            ->withOptions([
                'verify' => app()->environment('local') ? false : true,
            ]);

        try {
            $response = $http->get('https://api.exchangerate.host/latest', array_filter([
                'base' => 'USD',
                'symbols' => $symbols,
                'access_key' => $accessKey ?: null,
            ]));

            $data = $response->json();
            if ($response->ok() && is_array($data) && array_key_exists('rates', $data)) {
                return response()->json([
                    'success' => true,
                    'rates' => $data['rates'],
                    'timestamp' => $data['timestamp'] ?? now()->timestamp,
                ]);
            }

            logger()->warning('Primary exchange rate provider returned an invalid response.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            logger()->warning('Primary exchange rate provider failed.', ['error' => $e->getMessage()]);
        }

        try {
            $fallback = $http->get('https://open.er-api.com/v6/latest/USD');
            $fallbackData = $fallback->json();

            if ($fallback->ok() && is_array($fallbackData) && ($fallbackData['result'] ?? null) === 'success' && isset($fallbackData['rates'])) {
                $rates = collect($fallbackData['rates'])->only(explode(',', $symbols))->toArray();
                return response()->json([
                    'success' => true,
                    'rates' => $rates,
                    'timestamp' => $fallbackData['time_last_update_unix'] ?? now()->timestamp,
                ]);
            }

            logger()->warning('Fallback exchange rate provider returned an invalid response.', [
                'status' => $fallback->status(),
                'body' => $fallback->body(),
            ]);
        } catch (\Throwable $e) {
            logger()->warning('Fallback exchange rate provider failed.', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to fetch exchange rates at this time.',
        ], 502);
    }
}
