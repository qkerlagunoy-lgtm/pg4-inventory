@extends('layouts.admin')

@section('title', 'IP Range: ' . $ipRange->name)
@section('page-title', 'IP Range Details')

@section('breadcrumb')
<nav class="mb-4">
    <ol class="flex items-center space-x-2 text-sm">
        <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li><a href="{{ route('admin.addresses.index') }}" class="text-gray-500 hover:text-gray-700">IP Address Ranges</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li class="text-blue-600 font-medium">{{ $ipRange->name }}</li>
    </ol>
</nav>
@endsection

@section('header-actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.addresses.edit', $ipRange) }}"
       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Edit Range
    </a>
    <a href="{{ route('admin.addresses.index') }}"
       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back
    </a>
</div>
@endsection

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.ip-detail-page {
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

/* ── RANGE BANNER ── */
.range-banner {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.banner-content {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
}
.banner-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.banner-icon {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 50%;
    background: #d9ebf7;
    border: 1px solid #b3d4ec;
    display: flex;
    align-items: center;
    justify-content: center;
}
.banner-icon svg {
    width: 1.25rem;
    height: 1.25rem;
    color: #2d5f8a;
}
.banner-title h2 {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--charcoal);
}
.banner-desc {
    font-size: .85rem;
    color: #9a9591;
    margin-top: .2rem;
}
.banner-right {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1.5rem;
}
.ip-stat {
    text-align: center;
}
.ip-stat-label {
    font-size: .7rem;
    color: #9a9591;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: .3rem;
}
.ip-stat-value {
    font-family: 'Courier New', monospace;
    font-size: .85rem;
    font-weight: 700;
    color: #2d5f8a;
}
.ip-divider {
    color: var(--sand);
    font-size: 1.25rem;
}
.status-badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
}
.badge-active {
    background: rgba(0, 255, 106, 0.15);
    color: #4a5878;
    border: 1px solid rgba(101, 241, 25, 0.2);
}
.badge-inactive {
    background: #f36181;
    color: #000000;
    border: 1px solid #e2e4ec;
}

/* ── TABLE HEADER ── */
.table-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}
.table-top h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--charcoal);
}
.table-top p {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .2rem;
}
.btn-register {
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
    border: none;
    cursor: pointer;
    transition: opacity .15s;
}
.btn-register:hover { opacity: .88; }
.btn-register svg { width: 1rem; height: 1rem; }

/* ── TABLE ── */
.devices-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
}
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .875rem; }
thead tr {
    background: var(--cream);
    border-bottom: 2px solid var(--sand);
}
thead th {
    padding: .85rem 1.25rem;
    text-align: left;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #6b6966;
}
tbody tr {
    border-bottom: 1px solid #f0ece4;
    transition: background .15s;
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fdfbf7; }
tbody td { padding: 1rem 1.25rem; color: var(--charcoal); }

.device-cell {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.device-img {
    width: 3rem;
    height: 3rem;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid var(--sand);
    flex-shrink: 0;
}
.device-placeholder {
    width: 3rem;
    height: 3rem;
    border-radius: 8px;
    background: #f5f1e8;
    border: 1px solid var(--sand);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.device-placeholder svg {
    width: 1.25rem;
    height: 1.25rem;
    color: var(--sand);
}
.device-name {
    font-size: .875rem;
    font-weight: 700;
    color: var(--charcoal);
}
.device-type {
    font-size: .75rem;
    color: #9a9591;
}
.device-ip {
    font-size: .75rem;
    font-family: 'Courier New', monospace;
    color: #2d5f8a;
    margin-top: .1rem;
}

.person-cell {
    display: flex;
    align-items: center;
    gap: .5rem;
}
.person-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid var(--sand);
    flex-shrink: 0;
}
.person-initials {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #d9ebf7;
    border: 1px solid #b3d4ec;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.person-initials span {
    font-size: .7rem;
    font-weight: 700;
    color: #2d5f8a;
}
.person-name {
    font-size: .875rem;
    font-weight: 700;
    color: var(--charcoal);
}

.rank-badge {
    display: inline-block;
    padding: .2rem .5rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    background: rgba(110,125,162,0.15);
    color: #4a5878;
    border: 1px solid rgba(110,125,162,0.2);
}

.actions-cell {
    display: flex;
    align-items: center;
    gap: .5rem;
}
.btn-view {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .4rem .75rem;
    background: var(--sienna);
    color: #fff;
    font-size: .75rem;
    font-weight: 700;
    border-radius: 6px;
    text-decoration: none;
    transition: opacity .15s;
}
.btn-view:hover { opacity: .88; }
.btn-view svg { width: .875rem; height: .875rem; }
.btn-delete {
    padding: .4rem .75rem;
    background: #ffe6e6;
    color: #c0392b;
    font-size: .75rem;
    font-weight: 700;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
}
.btn-delete:hover { opacity: .88; }

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
    background: none;
    border: none;
    cursor: pointer;
}
.empty-link:hover { text-decoration: underline; }

/* ── PAGINATION ── */
.pagination-wrap {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--sand);
}

/* ── MODAL ── */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(74,73,71,.6);
    backdrop-filter: blur(2px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
}
.modal.hidden { display: none; }
.modal-content {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 12px;
    width: calc(100% - 2rem);
    max-width: 48rem;
    max-height: 92vh;
    overflow-y: auto;
    margin: 1rem;
}
.modal-header {
    position: sticky;
    top: 0;
    background: #fff;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--sand);
    border-radius: 12px 12px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 10;
}
.modal-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
}
.btn-close {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #f5f1e8;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: opacity .15s;
}
.btn-close:hover { opacity: .88; }
.btn-close svg {
    width: 1rem;
    height: 1rem;
    color: #6b6966;
}
.modal-body {
    padding: 1.5rem;
}
.modal-section {
    margin-bottom: 1.5rem;
}
.modal-section h4 {
    font-size: .875rem;
    font-weight: 700;
    color: #6b6966;
    margin-bottom: .75rem;
    padding-bottom: .5rem;
    border-bottom: 1px solid #f0ece4;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.modal-section h4 svg {
    width: 1rem;
    height: 1rem;
    color: #2d5f8a;
}
.form-grid {
    display: grid;
    gap: 1rem;
}
.form-grid.cols-3 {
    grid-template-columns: repeat(3, 1fr);
}
.form-grid.cols-2 {
    grid-template-columns: repeat(2, 1fr);
}
@media (max-width: 768px) {
    .form-grid.cols-3, .form-grid.cols-2 { grid-template-columns: 1fr; }
}
.form-field label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: #6b6966;
    margin-bottom: .4rem;
}
.form-field input, .form-field select, .form-field textarea {
    width: 100%;
    padding: .5rem .75rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    background: var(--cream);
    color: var(--charcoal);
    font-size: .875rem;
    font-family: inherit;
    outline: none;
}
.form-field input:focus, .form-field select:focus, .form-field textarea:focus {
    border-color: var(--sienna);
}
.form-field textarea { resize: vertical; }
.col-span-3 { grid-column: span 3; }
.col-span-2 { grid-column: span 2; }
@media (max-width: 768px) {
    .col-span-3, .col-span-2 { grid-column: span 1; }
}
.img-preview-wrap {
    margin-bottom: .5rem;
}
.img-preview {
    border-radius: 10px;
    border: 2px solid #b3d4ec;
}
.img-preview.profile {
    width: 5rem;
    height: 5rem;
    border-radius: 50%;
    object-fit: cover;
}
.img-preview.device {
    width: 6rem;
    height: 6rem;
    object-fit: cover;
}
.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .5rem 1rem;
    background: #fff;
    border: 2px dashed #b3d4ec;
    border-radius: 10px;
    color: #2d5f8a;
    font-size: .85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
}
.upload-btn:hover {
    background: #f0f7fc;
    border-color: #6ba3d4;
}
.upload-btn svg {
    width: 1rem;
    height: 1rem;
}
.help-text {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .3rem;
}
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: .75rem;
    padding-top: 1rem;
    border-top: 1px solid #f0ece4;
}
.btn {
    padding: .5rem 1.25rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
}
.btn:hover { opacity: .88; }
.btn-muted { background: #f5f1e8; color: var(--charcoal); border: 1px solid var(--sand); }
.btn-primary { background: var(--sienna); color: #fff; }
.btn svg { width: 1rem; height: 1rem; }
</style>

<div class="ip-detail-page">

    @if(session('success'))
        <div class="flash flash-success">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Range Banner --}}
    <div class="range-banner">
        <div class="banner-content">
            <div class="banner-left">
                <div class="banner-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                    </svg>
                </div>
                <div class="banner-title">
                    <h2>{{ $ipRange->name }}</h2>
                    <p class="banner-desc">{{ $ipRange->description ?? 'No description' }}</p>
                </div>
            </div>
            <div class="banner-right">
                <div class="ip-stat">
                    <p class="ip-stat-label">Start</p>
                    <p class="ip-stat-value">{{ $ipRange->range_start }}</p>
                </div>
                <div class="ip-divider">—</div>
                <div class="ip-stat">
                    <p class="ip-stat-label">End</p>
                    <p class="ip-stat-value">{{ $ipRange->range_end }}</p>
                </div>
                @if($ipRange->subnet_mask)
                <div class="ip-stat">
                    <p class="ip-stat-label">Subnet</p>
                    <p class="ip-stat-value">{{ $ipRange->subnet_mask }}</p>
                </div>
                @endif
                @if($ipRange->gateway)
                <div class="ip-stat">
                    <p class="ip-stat-label">Gateway</p>
                    <p class="ip-stat-value">{{ $ipRange->gateway }}</p>
                </div>
                @endif
                <span class="status-badge {{ $ipRange->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $ipRange->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Table Header --}}
    <div class="table-top">
    <div>
        <h3>Registered Devices</h3>
        <p>{{ $devices->total() }} device(s) within {{ $ipRange->range_start }} — {{ $ipRange->range_end }}</p>
    </div>

    <!-- BUTTON GROUP -->
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.addresses.export', $ipRange) }}" 
           class="btn-register" 
           style="background:#4a5878;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export CSV
        </a>

        <button onclick="showModal()" class="btn-register">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4v16m8-8H4"/>
            </svg>
            Register Device
        </button>
    </div>
</div>
    {{-- Devices Table --}}
    <div class="devices-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Device</th>
                        <th>Name</th>
                        <th>Rank</th>
                        <th>Unit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devices as $device)
                        <tr>
                            {{-- Device --}}
                            <td>
                                <div class="device-cell">
                                    @if($device->image)
                                        <img src="{{ asset('storage/'.$device->image) }}" class="device-img">
                                    @else
                                        <div class="device-placeholder">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="device-name">{{ $device->device_name ?? '—' }}</div>
                                        <div class="device-type">{{ $device->device_type ?? '' }}</div>
                                        @if($device->ip_address)
                                            <div class="device-ip">{{ $device->ip_address }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Name --}}
                            <td>
                                <div class="person-cell">
                                    @if($device->profile_picture)
                                        <img src="{{ asset('storage/'.$device->profile_picture) }}" class="person-avatar">
                                    @else
                                        <div class="person-initials">
                                            <span>
                                                {{ strtoupper(substr($device->assigned_firstname ?? '?', 0, 1)) }}{{ strtoupper(substr($device->assigned_lastname ?? '', 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="person-name">
                                        {{ $device->assigned_lastname ?? '—' }}{{ $device->assigned_lastname ? ', ' : '' }}{{ $device->assigned_firstname ?? '' }}
                                        @if($device->assigned_middlename)
                                            {{ substr($device->assigned_middlename, 0, 1) }}.
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Rank --}}
                            <td>
                                @if($device->assigned_rank)
                                    <span class="rank-badge">{{ $device->assigned_rank }}</span>
                                @else
                                    <span style="color:var(--sand);">—</span>
                                @endif
                            </td>

                            {{-- Unit --}}
                            <td>
                                <span>{{ $device->assigned_unit ?? '—' }}</span>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.addresses.device-profile', $device->id) }}" class="btn-view">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        View
                                    </a>
                                    <form method="POST" action="{{ route('admin.addresses.device.delete', $device->id) }}"
                                          onsubmit="return confirm('Delete this device record?')" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p>No devices found in this IP range.</p>
                                    <button onclick="showModal()" class="empty-link">
                                        Register first device →
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($devices->hasPages())
            <div class="pagination-wrap">{{ $devices->links() }}</div>
        @endif
    </div>

</div>

{{-- Register Device Modal --}}
<div id="registerDeviceModal" class="modal hidden">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Register New Device</h3>
            <button onclick="closeModal()" class="btn-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.addresses.device.register') }}" enctype="multipart/form-data">
            
            @csrf
            <input type="hidden" name="ip_address_range_id" value="{{ $ipRange->id }}">
            <div class="modal-body">
                {{-- Personnel Info --}}
                <div class="modal-section">
                    <h4>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Personnel Information
                    </h4>

                    <div class="form-field" style="margin-bottom:1rem;">
                        <label>Profile Picture</label>
                        <div id="profilePreviewWrap" class="img-preview-wrap" style="display:none;">
                            <img id="profilePreview" src="" class="img-preview profile">
                        </div>
                        <label for="profilePictureInput" class="upload-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span id="profileUploadLabel">Upload Profile Picture</span>
                        </label>
                        <input type="file" id="profilePictureInput" name="profile_picture"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               style="display:none;" onchange="previewProfileImg(this)">
                    </div>

                    <div class="form-grid cols-3">
                        <div class="form-field">
                            <label>First Name</label>
                            <input type="text" name="assigned_firstname" placeholder="Juan">
                        </div>
                        <div class="form-field">
                            <label>Middle Name</label>
                            <input type="text" name="assigned_middlename" placeholder="Santos">
                        </div>
                        <div class="form-field">
                            <label>Last Name</label>
                            <input type="text" name="assigned_lastname" placeholder="Dela Cruz">
                        </div>
                        <div class="form-field">
                            <label>Rank</label>
                            <input type="text" name="assigned_rank" placeholder="SGT">
                        </div>
                        <div class="form-field">
                            <label>Unit</label>
                            <input type="text" name="assigned_unit" placeholder="PG4">
                        </div>
                        <div class="form-field">
                            <label>Personnel Category</label>
                            <input type="text" name="assigned_category" placeholder="Military">
                        </div>
                        <div class="form-field col-span-3">
                            <label>Designation</label>
                            <input type="text" name="assigned_designation" placeholder="Information Systems Officer">
                        </div>
                    </div>
                </div>

                {{-- Device Info --}}
                <div class="modal-section">
                    <h4>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Device Information
                    </h4>

                    <div class="form-field" style="margin-bottom:1rem;">
                        <label>Device Image</label>
                        <div id="deviceImgPreviewWrap" class="img-preview-wrap" style="display:none;">
                            <img id="deviceImgPreview" src="" class="img-preview device">
                        </div>
                        <label for="deviceImageInput" class="upload-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span id="deviceUploadLabel">Upload Device Image</span>
                        </label>
                        <input type="file" id="deviceImageInput" name="image"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               style="display:none;" onchange="previewDeviceImg(this)">
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-field">
                            <label>Device Name</label>
                            <input type="text" name="device_name" placeholder="LAPTOP-PG4-001">
                        </div>
                        <div class="form-field">
                            <label>Device Type</label>
                            <select name="device_type">
                                <option value="">— Select Type —</option>
                                @foreach(['Desktop','Laptop','Printer','Router','Switch','Server','Mobile','Other'] as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Serial Number</label>
                            <input type="text" name="serial_number" placeholder="SN-2024-00123" style="font-family:'Courier New',monospace;">
                        </div>
                        <div class="form-field">
                            <label>IP Address</label>
                            <input type="text" name="ip_address" placeholder="{{ $ipRange->range_start }}" style="font-family:'Courier New',monospace;">
                            <p class="help-text">Range: {{ $ipRange->range_start }} — {{ $ipRange->range_end }}</p>
                        </div>
                        <div class="form-field">
                            <label>MAC Address</label>
                            <input type="text" name="mac_address" placeholder="AA:BB:CC:DD:EE:FF" style="font-family:'Courier New',monospace;">
                        </div>
                        <div class="form-field">
                            <label>Date Registered</label>
                            <input type="date" name="date_registered" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="form-field col-span-2">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="2" placeholder="Device notes, location, purpose..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal()" class="btn btn-muted">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Register Device
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showModal() {
    document.getElementById('registerDeviceModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('registerDeviceModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function previewProfileImg(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('profilePreview').src = e.target.result;
        document.getElementById('profilePreviewWrap').style.display = '';
        document.getElementById('profileUploadLabel').textContent = 'Change Picture';
    };
    reader.readAsDataURL(input.files[0]);
}

function previewDeviceImg(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('deviceImgPreview').src = e.target.result;
        document.getElementById('deviceImgPreviewWrap').style.display = '';
        document.getElementById('deviceUploadLabel').textContent = 'Change Image';
    };
    reader.readAsDataURL(input.files[0]);
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});
</script>

@endsection