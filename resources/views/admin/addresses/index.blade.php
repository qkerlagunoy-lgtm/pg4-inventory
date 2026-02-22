{{-- resources/views/admin/addresses/index.blade.php --}}

@extends('layouts.admin')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

/* ── ADDRESS MANAGEMENT CONTAINER ── */
.address-container {
    background: var(--cream);
    min-height: calc(100vh - 160px);
    padding: 2rem;
    font-family: 'Georgia', serif;
}

/* ── FILTERS & SEARCH BAR ── */
.filters-section {
    background: linear-gradient(135deg, #f5f1e8 0%, #ede9e0 100%);
    border: 2px solid var(--sand);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 16px rgba(74,73,71,.08);
}

.filters-row {
    display: flex;
    gap: 1rem;
    align-items: flex-end;
    flex-wrap: wrap;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .5rem;
    text-transform: uppercase;
    letter-spacing: .06em;
}

.filter-select {
    width: 100%;
    padding: .75rem 1rem;
    background: white;
    border: 2px solid var(--sand);
    border-radius: 8px;
    color: var(--charcoal);
    font-size: .9rem;
    font-family: 'Georgia', serif;
    transition: all .2s;
    cursor: pointer;
}

.filter-select:hover {
    border-color: var(--sienna);
}

.filter-select:focus {
    outline: none;
    border-color: var(--sienna);
    box-shadow: 0 0 0 3px rgba(177,116,87,.15);
}

.search-group {
    flex: 2;
    min-width: 300px;
}

.search-bar {
    position: relative;
}

.search-input {
    width: 100%;
    padding: .75rem 3.5rem .75rem 1rem;
    background: white;
    border: 2px solid var(--sand);
    border-radius: 8px;
    color: var(--charcoal);
    font-size: .9rem;
    font-family: 'Georgia', serif;
    transition: all .2s;
}

.search-input::placeholder {
    color: #9a9591;
    font-style: italic;
}

.search-input:focus {
    outline: none;
    border-color: var(--sienna);
    box-shadow: 0 0 0 3px rgba(177,116,87,.15);
}

.search-btn,
.reset-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    padding: .5rem;
    background: none;
    border: none;
    cursor: pointer;
    transition: all .2s;
    border-radius: 6px;
}

.search-btn {
    right: 2.5rem;
    color: var(--sienna);
}

.search-btn:hover {
    background: rgba(177,116,87,.1);
    color: #8a5a40;
}

.reset-btn {
    right: .5rem;
    color: #9a9591;
}

.reset-btn:hover {
    background: rgba(154,149,145,.1);
    color: var(--charcoal);
}

/* ── TABLE SECTION ── */
.table-section {
    background: white;
    border: 2px solid var(--sand);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(74,73,71,.08);
}

.table-header {
    background: linear-gradient(135deg, var(--charcoal) 0%, #3a3937 100%);
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 3px solid var(--sienna);
}

.table-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--cream);
    letter-spacing: .02em;
}

.address-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.address-table thead th {
    background: #f5f1e8;
    padding: 1rem 1.5rem;
    text-align: left;
    font-size: .8rem;
    font-weight: 700;
    color: var(--charcoal);
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 2px solid var(--sand);
}

.address-table tbody td {
    padding: 1.25rem 1.5rem;
    color: #4A4947;
    font-size: .875rem;
    border-bottom: 1px solid #ede9e0;
    vertical-align: middle;
}

.address-table tbody tr {
    transition: background .15s;
}

.address-table tbody tr:hover {
    background: #fdfcf9;
}

.address-table tbody tr:last-child td {
    border-bottom: none;
}

/* ── EMPTY STATE ── */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    opacity: .3;
}

.empty-icon svg {
    width: 100%;
    height: 100%;
    color: var(--sienna);
}

.empty-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: .5rem;
}

.empty-message {
    font-size: .9rem;
    color: #9a9591;
    font-style: italic;
    line-height: 1.6;
}

/* ── ACTION BUTTONS ── */
.action-buttons {
    display: flex;
    gap: .75rem;
    margin-top: 1.5rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-family: 'Georgia', serif;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--sienna) 0%, #9a5f48 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(177,116,87,.25);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #9a5f48 0%, var(--sienna) 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(177,116,87,.35);
}

.btn-secondary {
    background: var(--sand);
    color: var(--charcoal);
    border: 2px solid var(--sand);
}

.btn-secondary:hover {
    background: #c5bfaf;
    border-color: #c5bfaf;
    transform: translateY(-1px);
}

.btn svg {
    width: 1.1rem;
    height: 1.1rem;
}

/* ── BADGE ── */
.unit-badge {
    display: inline-flex;
    align-items: center;
    padding: .35rem .75rem;
    background: linear-gradient(135deg, #e8dfd0 0%, #ddd5c6 100%);
    color: var(--charcoal);
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .03em;
}

/* ── DEVICE TYPE ── */
.device-type {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .35rem .75rem;
    background: #f5f1e8;
    border-radius: 6px;
    font-size: .8rem;
    color: #6b6966;
}

.device-icon {
    width: 14px;
    height: 14px;
    color: var(--sienna);
}

/* ── FOOTER ACTIONS ── */
.table-footer {
    padding: 1.5rem;
    background: #f5f1e8;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    border-top: 2px solid var(--sand);
}

/* ── ANIMATIONS ── */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.address-table tbody tr {
    animation: fadeIn .3s ease forwards;
    opacity: 0;
}

.address-table tbody tr:nth-child(1) { animation-delay: .05s; }
.address-table tbody tr:nth-child(2) { animation-delay: .1s; }
.address-table tbody tr:nth-child(3) { animation-delay: .15s; }
.address-table tbody tr:nth-child(4) { animation-delay: .2s; }
.address-table tbody tr:nth-child(5) { animation-delay: .25s; }

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
    .filters-row {
        flex-direction: column;
    }
    
    .filter-group,
    .search-group {
        width: 100%;
        min-width: auto;
    }
}

@media (max-width: 768px) {
    .address-container {
        padding: 1rem;
    }
    
    .table-section {
        border-radius: 8px;
    }
    
    .address-table {
        font-size: .8rem;
    }
    
    .address-table thead th,
    .address-table tbody td {
        padding: .75rem 1rem;
    }
    
    .table-footer {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="address-container">

    {{-- TOP BAR --}}
    <div class="table-header" style="border-radius:12px 12px 0 0;">
        <div>
            <h3 class="table-title">Address Management</h3>
            <small style="color: var(--cream); opacity:.7;">
                AFPPGMC Logistics Division
            </small>
        </div>

        <form method="GET" action="{{ route('admin.addresses.index') }}" style="display:flex; gap:.75rem; align-items:center;">
            
            <input 
                type="text" 
                name="search"
                class="search-input"
                placeholder="Search addresses..."
                value="{{ request('search') }}"
                style="width:260px;"
            >

            <button type="submit" class="btn btn-secondary" style="padding:.5rem 1rem;">
                Search
            </button>

            <a href="{{ route('admin.addresses.index') }}" 
               class="btn btn-secondary" 
               style="padding:.5rem 1rem;">
                Reset
            </a>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-section" style="border-top:none; border-radius:0 0 12px 12px;">
        
        @if(isset($addresses) && count($addresses) > 0)

            <table class="address-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Unit</th>
                        <th>IP Address</th>
                        <th>MAC Address</th>
                        <th>Device</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($addresses as $address)
                        <tr>
                            <td><strong>{{ $address->name }}</strong></td>

                            <td>
                                <span class="unit-badge">
                                    {{ $address->unit }}
                                </span>
                            </td>

                            <td>
                                <code style="font-family: 'Courier New', monospace; color: var(--sienna);">
                                    {{ $address->ip_address }}
                                </code>
                            </td>

                            <td>
                                <code style="font-family: 'Courier New', monospace;">
                                    {{ $address->mac_address }}
                                </code>
                            </td>

                            <td>
                                <div class="device-type">
                                    {{ $address->device_type }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- FOOTER BUTTONS --}}
            <div class="table-footer">
                <button type="button" class="btn btn-primary">
                    New Address
                </button>

                <button type="button" class="btn btn-secondary">
                    Update Selection
                </button>
            </div>

        @else

            {{-- EMPTY STATE --}}
            <div class="empty-state">
                <h3 class="empty-title">No addresses found</h3>
                <p class="empty-message">
                    Add a new address to begin network management.
                </p>

                <div class="action-buttons" style="justify-content:center;">
                    <button type="button" class="btn btn-primary">
                        Add First Address
                    </button>
                </div>
            </div>

        @endif

    </div>

</div>
@endsection