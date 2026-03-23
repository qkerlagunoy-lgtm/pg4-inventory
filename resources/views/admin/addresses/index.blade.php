@extends('layouts.admin')

@section('title', 'IP Address Ranges')
@section('page-title', 'IP Address Ranges')

@section('breadcrumb')
<nav class="mb-4">
    <ol class="flex items-center space-x-2 text-sm">
        <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li class="text-blue-600 font-medium">IP Address Ranges</li>
    </ol>
</nav>
@endsection

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.ip-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}

/* ── FLASH ── */
.flash {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .85rem 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid;
}
.flash-success {
    background: #f0faf0;
    border-color: #4a8c4a;
}
.flash-success svg { color: #4a8c4a; width: 1.15rem; height: 1.15rem; }
.flash-success p { color: #2e6b2e; font-size: .875rem; font-weight: 600; }
.flash-error {
    background: #fff0f0;
    border-color: #c0392b;
}
.flash-error svg { color: #c0392b; width: 1.15rem; height: 1.15rem; }
.flash-error p { color: #8b2020; font-size: .875rem; font-weight: 600; }

/* ── SEARCH CARD ── */
.search-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}
.search-form {
    display: flex;
    gap: 1rem;
}
.search-input-wrap {
    flex: 1;
    position: relative;
}
.search-input {
    width: 100%;
    padding: .5rem .75rem .5rem 2.5rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    background: var(--cream);
    color: var(--charcoal);
    font-size: .875rem;
    font-family: inherit;
    outline: none;
}
.search-input:focus { border-color: var(--sienna); }
.search-icon {
    position: absolute;
    left: .75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.15rem;
    height: 1.15rem;
    color: #9a9591;
}
.btn {
    padding: .5rem 1.25rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
    text-decoration: none;
    display: inline-block;
}
.btn:hover { opacity: .88; }
.btn-primary { background: var(--sienna); color: #fff; }
.btn-muted { background: #f5f1e8; color: var(--charcoal); }

/* ── TABLE CARD ── */
.table-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
}
.table-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--sand);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.table-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
}
.table-header p {
    font-size: .85rem;
    color: #9a9591;
    margin-top: .2rem;
}
.header-right {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.count-badge {
    font-size: .85rem;
    color: #9a9591;
}
.btn-add {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .5rem 1rem;
    background: var(--sienna);
    color: #fff;
    border-radius: 7px;
    font-size: .875rem;
    font-weight: 600;
    text-decoration: none;
    transition: opacity .15s;
}
.btn-add:hover { opacity: .88; }
.btn-add svg { width: 1rem; height: 1rem; }

/* ── TABLE ── */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .875rem; }
thead tr {
    background: var(--cream);
    border-bottom: 2px solid var(--sand);
}
thead th {
    padding: .85rem 1.5rem;
    text-align: left;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #6b6966;
}
tbody tr { border-bottom: 1px solid #f0ece4; transition: background .15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fdfbf7; }
tbody td { padding: 1rem 1.5rem; color: var(--charcoal); }

.item-name {
    font-size: .875rem;
    font-weight: 700;
    color: var(--charcoal);
}
.item-desc {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .2rem;
}
.ip-mono {
    font-family: 'Courier New', monospace;
    font-size: .85rem;
    color: var(--charcoal);
}
.ip-muted {
    font-family: 'Courier New', monospace;
    font-size: .85rem;
    color: #0b0b0b;
}

/* ── BADGES ── */
.status-badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
}
.badge-active {
    background: rgba(104, 255, 96, 0.15);
    color: #4a5878;
    border: 1px solid rgba(110,125,162,0.2);
}
.badge-inactive {
    background: #f0f1f5;
    color: #6b7280;
    border: 1px solid #e2e4ec;
}

/* ── ACTIONS ── */
.actions {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.action-link {
    font-size: .85rem;
    font-weight: 600;
    text-decoration: none;
    transition: opacity .15s;
}
.action-link:hover { opacity: .88; }
.action-view { color: #2d5f8a; }
.action-edit { color: #4a8c4a; }
.action-delete {
    background: none;
    border: none;
    padding: 0;
    font-size: .85rem;
    font-weight: 600;
    color: #c0392b;
    cursor: pointer;
    transition: opacity .15s;
}
.action-delete:hover { opacity: .88; }
.action-divider {
    width: 1px;
    height: 1rem;
    background: var(--sand);
}

/* ── EMPTY STATE ── */
.empty-state {
    text-align: center;
    padding: 4rem 1rem;
}
.empty-icon {
    width: 3.5rem;
    height: 3.5rem;
    background: #f5f1e8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.empty-icon svg {
    width: 1.75rem;
    height: 1.75rem;
    color: var(--sand);
}
.empty-state p {
    font-size: .875rem;
    color: #9a9591;
    margin-bottom: .5rem;
}
.empty-link {
    display: inline-block;
    margin-top: .5rem;
    font-size: .85rem;
    color: var(--sienna);
    text-decoration: none;
}
.empty-link:hover { text-decoration: underline; }

/* ── PAGINATION ── */
.pagination-wrap {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--sand);
}
</style>

<div class="ip-page">

    @if(session('success'))
        <div class="flash flash-success">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flash flash-error">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Search Bar --}}
    <div class="search-card">
        <form method="GET" class="search-form">
            <div class="search-input-wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name, IP range, or description..."
                       class="search-input">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.addresses.index') }}" class="btn btn-muted">Reset</a>
        </form>
    </div>

    <div class="table-card">
        {{-- Table Header with Create Button --}}
        <div class="table-header">
            <div>
                <h3>IP Address Ranges</h3>
                <p>Manage network IP address blocks and ranges</p>
            </div>
            <div class="header-right">
                <span class="count-badge">{{ $ranges->total() }} total</span>
                <a href="{{ route('admin.addresses.create') }}" class="btn-add">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Range
                </a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Range Start</th>
                        <th>Range End</th>
                        <th>Subnet Mask</th>
                        <th>Gateway</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ranges as $range)
                        <tr>
                            <td>
                                <div class="item-name">{{ $range->name }}</div>
                                @if($range->description)
                                    <div class="item-desc">{{ Str::limit($range->description, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="ip-mono">{{ $range->range_start }}</span>
                            </td>
                            <td>
                                <span class="ip-mono">{{ $range->range_end }}</span>
                            </td>
                            <td>
                                <span class="ip-muted">{{ $range->subnet_mask ?? '—' }}</span>
                            </td>
                            <td>
                                <span class="ip-muted">{{ $range->gateway ?? '—' }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $range->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $range->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.addresses.show', $range) }}" class="action-link action-view">View</a>
                                    <div class="action-divider"></div>
                                    <a href="{{ route('admin.addresses.edit', $range) }}" class="action-link action-edit">Edit</a>
                                    <div class="action-divider"></div>
                                    <form method="POST" action="{{ route('admin.addresses.destroy', $range) }}"
                                          onsubmit="return confirm('Delete this IP range?')" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                                        </svg>
                                    </div>
                                    <p>No IP ranges defined yet.</p>
                                    <a href="{{ route('admin.addresses.create') }}" class="empty-link">
                                        Add first range →
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ranges->hasPages())
            <div class="pagination-wrap">
                {{ $ranges->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

@endsection