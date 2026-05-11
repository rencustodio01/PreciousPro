@extends('layouts.app')
@section('title', 'Finance Records')

@section('content')
@php
$exchangeCurrencies = $exchangeCurrencies ?? ['PHP', 'EUR', 'JPY', 'CNY'];
@endphp
<div class="page-header">
    <div>
        <h2>Finance Records</h2><small>Track production costs (Material, Labor, Overhead)</small>
    </div>
    <a href="{{ route('finance.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg me-1"></i> Add Record</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="exchange-card p-3">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-3">
                <div>
                    <div class="exchange-heading">Live USD Exchange Rates</div>
                    <div class="exchange-subtitle">Auto-updated reference rates for finance costing.</div>
                </div>
                <div id="financeExchangeStatus" class="exchange-status">Loading…</div>
            </div>
            <div class="exchange-currency-grid">
                @foreach($exchangeCurrencies as $currencyCode)
                <div class="exchange-currency-item">
                    <div class="currency-label">{{ $currencyCode }}</div>
                    <div class="exchange-value" data-currency-code="{{ $currencyCode }}">-</div>
                </div>
                @endforeach
            </div>
            <div class="exchange-error-custom mt-3" id="financeExchangeError"></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach(['Material','Labor','Overhead'] as $ct)
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-{{ $ct === 'Material' ? 'box' : ($ct === 'Labor' ? 'person-workspace' : 'building') }}"></i></div>
            <div class="kpi-value">₱{{ number_format($costSummary[$ct] ?? 0, 0) }}</div>
            <div class="kpi-label">{{ $ct }} Cost (Month)</div>
        </div>
    </div>
    @endforeach
</div>

<form class="row g-2 mb-4" method="GET">
    <div class="col-md-3">
        <select name="cost_type" class="form-select">
            <option value="">All Cost Types</option>
            @foreach(['Material','Labor','Overhead'] as $ct)
            <option value="{{ $ct }}" {{ request('cost_type') == $ct ? 'selected' : '' }}>{{ $ct }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-gold w-100">Filter</button>
    </div>
</form>

<div class="data-card">
    <div class="data-card-header">
        <h5>Finance Records ({{ $records->total() }} total)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Production / Product</th>
                    <th>Cost Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Recorded By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                <tr>
                    <td>{{ $rec->id }}</td>
                    <td>
                        <strong>{{ $rec->production->product->product_name }}</strong>
                        <div class="text-muted" style="font-size:0.75rem">Run #{{ $rec->production_id }}</div>
                    </td>
                    <td><span class="badge badge-gold">{{ $rec->cost_type }}</span></td>
                    <td><strong>₱{{ number_format($rec->amount, 2) }}</strong></td>
                    <td>{{ $rec->record_date->format('M d, Y') }}</td>
                    <td>{{ $rec->recorder->full_name }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('finance.show', $rec) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('finance.edit', $rec) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('finance.destroy', $rec) }}" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No finance records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $records->links() }}</div>
</div>
@endsection

@push('styles')
<style>
    .exchange-card {
        background: #ffffff;
        color: #1C1C2E;
        border-radius: 16px;
        border: 1px solid rgba(184, 150, 12, 0.14);
        box-shadow: 0 14px 24px rgba(15, 12, 184, 0.05);
    }

    .exchange-heading {
        font-size: 0.96rem;
        font-weight: 700;
        color: #1C1C2E;
        margin-bottom: 2px;
    }

    .exchange-subtitle {
        font-size: 0.78rem;
        color: #6F6F85;
        margin-bottom: 0;
    }

    .exchange-status {
        font-size: 0.78rem;
        color: #8C8C9E;
    }

    .exchange-currency-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 10px;
    }

    @media (min-width: 768px) {
        .exchange-currency-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    .exchange-currency-item {
        background: #FFF8E1;
        border: 1px solid rgba(184, 150, 12, 0.16);
        border-radius: 12px;
        padding: 12px 12px;
        min-height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .currency-label {
        font-size: 0.72rem;
        color: #8C8C9E;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 6px;
    }

    .exchange-value {
        font-size: 1rem;
        font-weight: 700;
        color: #B8960C;
    }

    .exchange-error-custom {
        display: none;
        background: #FDF2F2;
        border: 1px solid #F5C2C7;
        color: #842029;
        border-radius: 14px;
        padding: 10px 12px;
    }

    .exchange-error-custom.visible {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
    (function() {
        const status = document.getElementById('financeExchangeStatus');
        const errorBox = document.getElementById('financeExchangeError');
        const exchangeCurrencyCodes = @json($exchangeCurrencies);
        const elements = Object.fromEntries(
            Array.from(document.querySelectorAll('[data-currency-code]')).map(el => [el.dataset.currencyCode, el])
        );

        function formatRate(code, value) {
            if (value === null || value === undefined) {
                return '—';
            }
            return `1 USD = ${value.toLocaleString(undefined, {
            minimumFractionDigits: code === 'JPY' ? 0 : 2,
            maximumFractionDigits: code === 'JPY' ? 0 : 4,
        })} ${code}`;
        }

        async function fetchRates() {
            const apiUrl = "{{ route('exchange.rates') }}";
            errorBox.classList.remove('visible');
            errorBox.textContent = '';
            status.textContent = 'Updating rates…';

            try {
                const res = await fetch(apiUrl, {
                    cache: 'no-cache'
                });
                if (!res.ok) {
                    const errorText = await res.text();
                    throw new Error(res.statusText || errorText || 'Request failed');
                }
                const data = await res.json();
                if (!data || !data.success || !data.rates) {
                    throw new Error(data.message || 'Invalid API response');
                }

                exchangeCurrencyCodes.forEach(code => {
                    const rate = data.rates[code] ?? null;
                    elements[code].textContent = formatRate(code, rate);
                });

                const updatedAt = data.timestamp ? new Date(data.timestamp * 1000) : new Date();
                status.textContent = `Last updated ${updatedAt.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}`;
            } catch (err) {
                status.textContent = 'Rate update failed';
                errorBox.textContent = `Unable to load rates: ${err.message}`;
                errorBox.classList.add('visible');
                exchangeCurrencyCodes.forEach(code => {
                    elements[code].textContent = '—';
                });
                console.error('Exchange rate widget error:', err);
            }
        }

        fetchRates();
        setInterval(fetchRates, 300000);
    })();
</script>
@endpush