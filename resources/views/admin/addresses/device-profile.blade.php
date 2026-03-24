@extends('layouts.admin')

@section('title', 'Device Profile')
@section('page-title', 'Device Profile')

@section('breadcrumb')
<nav class="mb-4">
    <ol class="flex items-center space-x-2 text-sm">
        <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li><a href="{{ route('admin.addresses.index') }}" class="text-gray-500 hover:text-gray-700">IP Ranges</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li class="text-blue-600 font-medium">Profile</li>
    </ol>
</nav>
@endsection

@section('header-actions')
<button onclick="history.back()"
        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2 text-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back
</button>
@endsection

@push('styles')
<style>

:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}
.profile-page { background: var(--cream); padding: 2rem; font-family: 'Georgia', serif; min-height: 100vh; }
.flash-success { display:flex; align-items:center; gap:.75rem; padding:.85rem 1rem; border-radius:8px; margin-bottom:1.5rem; border-left:4px solid #4a8c4a; background:#f0faf0; }
.flash-success svg { color:#4a8c4a; width:1.15rem; height:1.15rem; }
.flash-success p { color:#2e6b2e; font-size:.875rem; font-weight:600; }
.profile-header { background:#fff; border:1px solid var(--sand); border-radius:10px; padding:1.5rem; margin-bottom:1.5rem; }
.header-content { display:flex; flex-wrap:wrap; align-items:center; gap:1.5rem; }
.avatar-wrap { position:relative; flex-shrink:0; }
.avatar-img { width:6rem; height:6rem; border-radius:50%; object-fit:cover; border:4px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,.1); }
.avatar-placeholder { width:6rem; height:6rem; border-radius:50%; background:#d9ebf7; border:4px solid #fff; box-shadow:0 2px 8px rgba(0,0,0,.1); display:flex; align-items:center; justify-content:center; }
.avatar-placeholder span { font-size:1.875rem; font-weight:700; color:#2d5f8a; }
.edit-avatar-btn { position:absolute; bottom:0; right:0; width:2rem; height:2rem; background:var(--sienna); border-radius:50%; border:2px solid #fff; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,.15); transition:opacity .15s; }
.edit-avatar-btn:hover { opacity:.88; }
.edit-avatar-btn svg { width:.875rem; height:.875rem; color:#fff; }
.profile-info { flex:1; min-width:15rem; }
.profile-name { font-size:1.5rem; font-weight:700; color:var(--charcoal); }
.profile-badges { display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.5rem; }
.badge { display:inline-block; padding:.25rem .75rem; border-radius:20px; font-size:.7rem; font-weight:700; }
.badge-rank { background:rgba(110,125,162,0.15); color:#4a5878; border:1px solid rgba(110,125,162,0.2); }
.badge-unit { background:rgba(174,218,221,0.2); color:#5a8fa0; border:1px solid rgba(174,218,221,0.3); }
.badge-cat { background:rgba(219,153,108,0.12); color:#b07040; border:1px solid rgba(219,153,108,0.2); }
.badge-role { background:#f5f1e8; color:#6b6966; }
.profile-stats { display:flex; gap:1.5rem; text-align:center; }
.stat-box { flex-shrink:0; }
.stat-num { font-size:1.5rem; font-weight:700; color:var(--charcoal); }
.stat-label { font-size:.7rem; color:#9a9591; text-transform:uppercase; letter-spacing:.06em; margin-top:.2rem; }
.details-grid { display:grid; grid-template-columns:repeat(2, 1fr); gap:1.5rem; align-items:start; }
@media (max-width:1024px) { .details-grid { grid-template-columns:1fr; } }
.detail-card { background:#fff; border:1px solid #c5bfb3; border-radius:10px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.08); }
.card-header { padding:1rem 1.25rem; background:#e8e2d6; border-bottom:1px solid #c5bfb3; display:flex; align-items:center; gap:.5rem; }
.card-header svg { width:1rem; height:1rem; color:var(--sienna); }
.card-header h3 { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--charcoal); }
.card-header .count { display:inline-block; padding:.2rem .5rem; border-radius:20px; font-size:.7rem; font-weight:700; margin-left:auto; }
.count-red { background:#ffe6e6; color:#c0392b; }
.count-gray { background:#f5f1e8; color:#9a9591; }
.add-offense-btn { font-size:.75rem; color:#c0392b; font-weight:600; cursor:pointer; background:none; border:none; padding:0; margin-left:.5rem; }
.add-offense-btn:hover { text-decoration:underline; }
.personnel-grid { display:grid; grid-template-columns:repeat(2, 1fr); }
.personnel-cell { padding:.85rem 1.25rem; border-right:1px solid #e8e2d6; border-bottom:1px solid #e8e2d6; background:#fdfcfa; }
.personnel-cell:nth-child(2n) { border-right:none; }
.personnel-cell:nth-last-child(-n+2) { border-bottom:none; }
.personnel-cell.full { grid-column:span 2; border-right:none; border-bottom:none; }
.personnel-label { font-size:.68rem; color:var(--sienna); text-transform:uppercase; font-weight:700; letter-spacing:.05em; margin-bottom:.35rem; }
.personnel-value { font-size:.88rem; font-weight:600; color:var(--charcoal); }
.device-body { padding:0 1.5rem 1.5rem; background:#fdfcfa; }
.device-img-wrap { padding:1.5rem 1.5rem 1rem; background:#fdfcfa; }
.device-img { width:100%; height:20rem; object-fit:cover; border-radius:10px; border:1px solid #c5bfb3; box-shadow:0 1px 3px rgba(0,0,0,.08); }
.device-row { padding:1rem 0; border-bottom:1px solid #e8e2d6; display:flex; justify-content:space-between; align-items:center; }
.device-row:last-child { border-bottom:none; }
.device-row-label { font-size:.7rem; color:var(--sienna); text-transform:uppercase; font-weight:700; letter-spacing:.05em; }
.device-row-value { font-size:.9rem; font-weight:600; color:var(--charcoal); }
.ip-mono { font-family:'Courier New',monospace; font-size:.88rem; font-weight:700; color:#2d5f8a; }
.mac-mono { font-family:'Courier New',monospace; font-size:.8rem; color:#6b6966; }
.sn-mono { font-family:'Courier New',monospace; font-size:.8rem; font-weight:600; color:var(--charcoal); }
.remarks-row { padding:.85rem 0; display:block; }
.remarks-text { font-size:.875rem; color:#6b6966; line-height:1.5; }
.offense-empty { text-align:center; padding:3rem 1rem; background:#fdfcfa; }
.offense-empty svg { width:2.5rem; height:2.5rem; color:var(--sand); margin:0 auto .5rem; }
.offense-empty p { font-size:.875rem; color:#9a9591; }
.offense-list { padding:0; background:#fdfcfa; }
.offense-item { padding:1.25rem 1.5rem; border-bottom:1px solid #e8e2d6; }
.offense-item:last-child { border-bottom:none; }
.offense-top { display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; }
.offense-content { flex:1; }
.offense-title-row { display:flex; align-items:center; gap:.5rem; margin-bottom:.4rem; }
.offense-title { font-size:.875rem; font-weight:700; color:var(--charcoal); }
.offense-badge { padding:.2rem .5rem; border-radius:20px; font-size:.7rem; font-weight:700; }
.offense-pending { background:rgba(219,153,108,0.12); color:#b07040; }
.offense-resolved { background:rgba(110,125,162,0.12); color:#4a5878; }
.offense-dismissed { background:#f0f1f5; color:#6b7280; }
.offense-desc { font-size:.875rem; color:#6b6966; line-height:1.5; margin-bottom:.4rem; }
.offense-meta { display:flex; flex-wrap:wrap; gap:.75rem; font-size:.75rem; color:#9a9591; }
.offense-actions { display:flex; gap:.5rem; flex-shrink:0; align-items:flex-start; }
.btn-edit-offense { font-size:.75rem; color:#2d5f8a; background:none; border:none; cursor:pointer; padding:0; font-weight:600; }
.btn-edit-offense:hover { text-decoration:underline; }
.btn-delete-offense { font-size:.75rem; color:#c0392b; background:none; border:none; cursor:pointer; padding:0; }
.btn-delete-offense:hover { text-decoration:underline; }
/* PIE CHART */
.offense-stats { padding:1.5rem; background:#fdfcfa; display:flex; align-items:center; justify-content:center; gap:2rem; flex-wrap:wrap; }
.pie-wrap { position:relative; width:9rem; height:9rem; flex-shrink:0; }
.pie-wrap canvas { width:100% !important; height:100% !important; }
.pie-legend { display:flex; flex-direction:column; gap:.75rem; }
.legend-item { display:flex; align-items:center; gap:.65rem; font-size:.85rem; color:var(--charcoal); }
.legend-dot { width:.7rem; height:.7rem; border-radius:50%; flex-shrink:0; }
/* MODAL */
.modal { position:fixed; inset:0; background:rgba(74,73,71,.6); backdrop-filter:blur(2px); display:flex; align-items:center; justify-content:center; z-index:999; overflow-y:auto; }
.modal.hidden { display:none; }
.modal-content { background:#fff; border:1px solid var(--sand); border-radius:12px; width:calc(100% - 2rem); margin:1rem; }
.modal-content.small { max-width:28rem; }
.modal-content.large { max-width:48rem; max-height:92vh; overflow-y:auto; }
.modal-header { position:sticky; top:0; background:#fff; padding:1.25rem 1.5rem; border-bottom:1px solid var(--sand); border-radius:12px 12px 0 0; display:flex; align-items:center; justify-content:space-between; z-index:10; }
.modal-header h3 { font-size:1.1rem; font-weight:700; color:var(--charcoal); }
.btn-close { width:2rem; height:2rem; border-radius:50%; background:#f5f1e8; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:opacity .15s; }
.btn-close:hover { opacity:.88; }
.btn-close svg { width:1rem; height:1rem; color:#6b6966; }
.modal-body { padding:1.5rem; }
.modal-section { margin-bottom:1.5rem; }
.modal-section:last-child { margin-bottom:0; }
.modal-section h4 { font-size:.875rem; font-weight:700; color:#6b6966; margin-bottom:.75rem; padding-bottom:.5rem; border-bottom:1px solid #f0ece4; }
.form-grid { display:grid; gap:1rem; }
.form-grid.cols-3 { grid-template-columns:repeat(3, 1fr); }
.form-grid.cols-2 { grid-template-columns:repeat(2, 1fr); }
@media (max-width:768px) { .form-grid.cols-3,.form-grid.cols-2 { grid-template-columns:1fr; } }
.form-field label { display:block; font-size:.8rem; font-weight:600; color:#6b6966; margin-bottom:.4rem; }
.form-field input,.form-field select,.form-field textarea { width:100%; padding:.5rem .75rem; border:1px solid var(--sand); border-radius:7px; background:var(--cream); color:var(--charcoal); font-size:.875rem; font-family:inherit; outline:none; }
.form-field input:focus,.form-field select:focus,.form-field textarea:focus { border-color:var(--sienna); }
.form-field textarea { resize:vertical; }
.col-span-3 { grid-column:span 3; }
.col-span-2 { grid-column:span 2; }
@media (max-width:768px) { .col-span-3,.col-span-2 { grid-column:span 1; } }
.img-upload-section { margin-bottom:1rem; display:flex; align-items:center; gap:1rem; }
.preview-img { border-radius:10px; border:2px solid #b3d4ec; object-fit:cover; }
.preview-img.profile { width:4rem; height:4rem; border-radius:50%; }
.preview-img.device { width:5rem; height:5rem; }
.upload-btn { display:inline-flex; align-items:center; gap:.5rem; padding:.5rem 1rem; background:#fff; border:2px dashed #b3d4ec; border-radius:10px; color:#2d5f8a; font-size:.85rem; font-weight:700; cursor:pointer; transition:all .15s; }
.upload-btn:hover { background:#f0f7fc; border-color:#6ba3d4; }
.modal-footer { display:flex; justify-content:flex-end; gap:.75rem; padding-top:1rem; border-top:1px solid #f0ece4; }
.btn { padding:.5rem 1.25rem; font-size:.875rem; font-weight:600; border-radius:7px; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; transition:opacity .15s; }
.btn:hover { opacity:.88; }
.btn-muted { background:#f5f1e8; color:var(--charcoal); border:1px solid var(--sand); }
.btn-primary { background:var(--sienna); color:#fff; }
.btn-danger { background:#c0392b; color:#fff; }
.btn svg { width:1rem; height:1rem; }
.person-full-name { font-size:1.5rem; font-weight:700; color:var(--charcoal) !important; font-family:'Georgia',serif; }

</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const flash = document.querySelector('.flash-success');
    if (flash) {
        setTimeout(function() {
            flash.style.transition = 'opacity 0.5s ease';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 200);
        }, 3000);
    }
});
</script>
@endpush

@section('content')

<div class="profile-page">

    @if(session('success'))
        <div class="flash-success">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Profile Header --}}
    <div class="profile-header">
        <div class="header-content">
            <div class="avatar-wrap">
                @if($device->profile_picture)
                    <img src="{{ asset('storage/'.$device->profile_picture) }}" alt="{{ $device->assigned_firstname }}" class="avatar-img">
                @else
                    <div class="avatar-placeholder">
                        <span>{{ strtoupper(substr($device->assigned_firstname ?? '?', 0, 1)) }}{{ strtoupper(substr($device->assigned_lastname ?? '', 0, 1)) }}</span>
                    </div>
                @endif
                <button onclick="showEditModal()" class="edit-avatar-btn" title="Edit Profile">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
            </div>
            <div class="profile-info">
               <h2 class="person-full-name">
                    {{ $device->assigned_lastname ?? '—' }}{{ $device->assigned_lastname ? ', ' : '' }}{{ $device->assigned_firstname ?? '' }}
                    @if($device->assigned_middlename) {{ $device->assigned_middlename }} @endif
                </h2>
                <div class="profile-badges">
                    @if($device->assigned_rank)
                        <span class="badge badge-rank">{{ $device->assigned_rank }}</span>
                    @endif
                    @if($device->assigned_unit)
                        <span class="badge badge-unit">{{ $device->assigned_unit }}</span>
                    @endif
                    @if($device->assigned_category)
                        <span class="badge badge-cat">{{ $device->assigned_category }}</span>
                    @endif
                    @if($device->assigned_designation)
                        <span class="badge badge-role">{{ $device->assigned_designation }}</span>
                    @endif
                </div>
            </div>
            <div class="profile-stats">
                <div class="stat-box">
                    <p class="stat-num">{{ $device->offenses->count() }}</p>
                    <p class="stat-label">Offenses</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $pending   = $device->offenses->where('status','pending')->count();
        $resolved  = $device->offenses->where('status','resolved')->count();
        $dismissed = $device->offenses->where('status','dismissed')->count();
        $total     = $device->offenses->count();
    @endphp

    {{-- Details Grid --}}
    <div class="details-grid">
        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            {{-- Personnel Details --}}
            <div class="detail-card" style="height:fit-content;">
                <div class="card-header">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <h3>Personnel Details</h3>
                </div>
                <div class="personnel-grid">
                    <div class="personnel-cell">
                        <p class="personnel-label">First Name</p>
                        <p class="personnel-value">{{ $device->assigned_firstname ?? '—' }}</p>
                    </div>
                    <div class="personnel-cell">
                        <p class="personnel-label">Middle Name</p>
                        <p class="personnel-value">{{ $device->assigned_middlename ?? '—' }}</p>
                    </div>
                    <div class="personnel-cell">
                        <p class="personnel-label">Last Name</p>
                        <p class="personnel-value">{{ $device->assigned_lastname ?? '—' }}</p>
                    </div>
                    <div class="personnel-cell">
                        <p class="personnel-label">Rank</p>
                        <p class="personnel-value">{{ $device->assigned_rank ?? '—' }}</p>
                    </div>
                    <div class="personnel-cell">
                        <p class="personnel-label">Unit</p>
                        <p class="personnel-value">{{ $device->assigned_unit ?? '—' }}</p>
                    </div>
                    <div class="personnel-cell">
                        <p class="personnel-label">Personnel Category</p>
                        <p class="personnel-value">{{ $device->assigned_category ?? '—' }}</p>
                    </div>
                    <div class="personnel-cell full">
                        <p class="personnel-label">Designation</p>
                        <p class="personnel-value">{{ $device->assigned_designation ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Offense Statistics Card --}}
            @if($total > 0)
            <div class="detail-card">
                <div class="card-header">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3>Offense Statistics</h3>
                </div>
                <div class="offense-stats">
                    <div class="pie-wrap">
                        <canvas id="offensePie"></canvas>
                    </div>
                    <div class="pie-legend">
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#b07040;"></span>
                            <span>Pending: <strong>{{ $pending }}</strong></span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#4a5878;"></span>
                            <span>Resolved: <strong>{{ $resolved }}</strong></span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#9ca3af;"></span>
                            <span>Dismissed: <strong>{{ $dismissed }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN - Device Info --}}
        <div class="detail-card" style="height:fit-content;">
            <div class="card-header">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3>Device Info</h3>
            </div>
            @if($device->image)
                <div class="device-img-wrap">
                    <img src="{{ asset('storage/'.$device->image) }}" alt="{{ $device->device_name }}" class="device-img">
                </div>
            @endif
            <div class="device-body">
                <div class="device-rows">
                    <div class="device-row">
                        <span class="device-row-label">Device Name</span>
                        <span class="device-row-value">{{ $device->device_name ?? '—' }}</span>
                    </div>
                    <div class="device-row">
                        <span class="device-row-label">Type</span>
                        <span class="device-row-value">{{ $device->device_type ?? '—' }}</span>
                    </div>
                    <div class="device-row">
                        <span class="device-row-label">Serial No.</span>
                        <span class="sn-mono">{{ $device->serial_number ?? '—' }}</span>
                    </div>
                    <div class="device-row">
                        <span class="device-row-label">IP Address</span>
                        <span class="ip-mono">{{ $device->ip_address ?? '—' }}</span>
                    </div>
                    <div class="device-row">
                        <span class="device-row-label">MAC Address</span>
                        <span class="mac-mono">{{ $device->mac_address ?? '—' }}</span>
                    </div>
                    <div class="device-row">
                        <span class="device-row-label">Date Registered</span>
                        <span class="device-row-value">{{ $device->date_registered?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    @if($device->remarks)
                        <div class="remarks-row">
                            <span class="device-row-label" style="display:block;margin-bottom:.3rem;">Remarks</span>
                            <p class="remarks-text">{{ $device->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Offense Records (Full Width Below) --}}
    <div class="detail-card" style="margin-top:1.5rem;">
        <div class="card-header">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3>Offense Records</h3>
            <span class="count {{ $total > 0 ? 'count-red' : 'count-gray' }}">{{ $total }}</span>
            <button onclick="showAddOffenseModal()" class="add-offense-btn">+ Add Offense</button>
        </div>

        @if($device->offenses->isEmpty())
            <div class="offense-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>No offense records.</p>
            </div>
        @else
            <div class="offense-list">
                @foreach($device->offenses->sortByDesc('offense_date') as $offense)
                    <div class="offense-item">
                        <div class="offense-top">
                            <div class="offense-content">
                                <div class="offense-title-row">
                                    <span class="offense-title">{{ $offense->title }}</span>
                                    <span class="offense-badge offense-{{ $offense->status }}">
                                        {{ ucfirst($offense->status) }}
                                    </span>
                                </div>
                                @if($offense->description)
                                    <p class="offense-desc">{{ $offense->description }}</p>
                                @endif
                                <div class="offense-meta">
                                    @if($offense->offense_date)
                                        <span>📅 {{ $offense->offense_date->format('M d, Y') }}</span>
                                    @endif
                                    @if($offense->filedBy)
                                        <span>Filed by: {{ $offense->filedBy->first_name }} {{ $offense->filedBy->last_name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="offense-actions">
                                <button onclick="showEditOffenseModal({{ $offense->id }}, '{{ addslashes($offense->title) }}', '{{ addslashes($offense->description ?? '') }}', '{{ $offense->offense_date?->format('Y-m-d') ?? '' }}', '{{ $offense->status }}')"
                                        class="btn-edit-offense">Edit</button>
                                <form method="POST" action="{{ route('admin.addresses.device.offense.delete', $offense) }}"
                                      onsubmit="return confirm('Delete this offense?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-offense">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

{{-- Add Offense Modal --}}
<div id="addOffenseModal" class="modal hidden">
    <div class="modal-content small">
        <div class="modal-header">
            <h3>Add Offense Record</h3>
            <button onclick="closeAddOffenseModal()" class="btn-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.addresses.device.offense.store', $device) }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-field">
                        <label>Title *</label>
                        <input type="text" name="title" placeholder="e.g. Unauthorized Access" required>
                    </div>
                    <div class="form-field">
                        <label>Description</label>
                        <textarea name="description" rows="3" placeholder="Describe the offense..."></textarea>
                    </div>
                    <div class="form-field">
                        <label>Date of Offense</label>
                        <input type="date" name="offense_date">
                    </div>
                    <div class="form-field">
                        <label>Status</label>
                        <select name="status">
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="dismissed">Dismissed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeAddOffenseModal()" class="btn btn-muted">Cancel</button>
                    <button type="submit" class="btn btn-danger">Save Offense</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Offense Modal --}}
<div id="editOffenseModal" class="modal hidden">
    <div class="modal-content small">
        <div class="modal-header">
            <h3>Edit Offense Record</h3>
            <button onclick="closeEditOffenseModal()" class="btn-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="editOffenseForm" method="POST" action="">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-field">
                        <label>Title *</label>
                        <input type="text" id="editOffenseTitle" name="title" required>
                    </div>
                    <div class="form-field">
                        <label>Description</label>
                        <textarea id="editOffenseDesc" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-field">
                        <label>Date of Offense</label>
                        <input type="date" id="editOffenseDate" name="offense_date">
                    </div>
                    <div class="form-field">
                        <label>Status</label>
                        <select id="editOffenseStatus" name="status">
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="dismissed">Dismissed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeEditOffenseModal()" class="btn btn-muted">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Profile Modal --}}
<div id="editModal" class="modal hidden">
    <div class="modal-content large" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Edit Profile & Device</h3>
            <button onclick="closeEditModal()" class="btn-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.addresses.device.update', $device) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="modal-section">
                    <h4>Personnel Information</h4>
                    <div class="img-upload-section">
                        @if($device->profile_picture)
                            <img id="editProfilePreview" src="{{ asset('storage/'.$device->profile_picture) }}" class="preview-img profile">
                        @else
                            <div id="editProfilePreviewWrap" style="display:none;">
                                <img id="editProfilePreview" src="" class="preview-img profile">
                            </div>
                        @endif
                        <label for="editProfilePic" class="upload-btn">Change Profile Picture</label>
                        <input type="file" id="editProfilePic" name="profile_picture" accept="image/*" style="display:none;" onchange="previewEditProfile(this)">
                    </div>
                    <div class="form-grid cols-3">
                        <div class="form-field">
                            <label>First Name</label>
                            <input type="text" name="assigned_firstname" value="{{ $device->assigned_firstname }}">
                        </div>
                        <div class="form-field">
                            <label>Middle Name</label>
                            <input type="text" name="assigned_middlename" value="{{ $device->assigned_middlename }}">
                        </div>
                        <div class="form-field">
                            <label>Last Name</label>
                            <input type="text" name="assigned_lastname" value="{{ $device->assigned_lastname }}">
                        </div>
                        <div class="form-field">
                            <label>Rank</label>
                            <input type="text" name="assigned_rank" value="{{ $device->assigned_rank }}">
                        </div>
                        <div class="form-field">
                            <label>Unit</label>
                            <input type="text" name="assigned_unit" value="{{ $device->assigned_unit }}">
                        </div>
                        <div class="form-field">
                            <label>Personnel Category</label>
                            <input type="text" name="assigned_category" value="{{ $device->assigned_category }}">
                        </div>
                        <div class="form-field col-span-3">
                            <label>Designation</label>
                            <input type="text" name="assigned_designation" value="{{ $device->assigned_designation }}">
                        </div>
                    </div>
                </div>
                <div class="modal-section">
                    <h4>Device Information</h4>
                    <div class="img-upload-section">
                        @if($device->image)
                            <img id="editDeviceImgPreview" src="{{ asset('storage/'.$device->image) }}" class="preview-img device">
                        @else
                            <div id="editDeviceImgPreviewWrap" style="display:none;">
                                <img id="editDeviceImgPreview" src="" class="preview-img device">
                            </div>
                        @endif
                        <label for="editDeviceImg" class="upload-btn">Change Device Image</label>
                        <input type="file" id="editDeviceImg" name="image" accept="image/*" style="display:none;" onchange="previewEditDevice(this)">
                    </div>
                    <div class="form-grid cols-2">
                        <div class="form-field">
                            <label>Device Name</label>
                            <input type="text" name="device_name" value="{{ $device->device_name }}">
                        </div>
                        <div class="form-field">
                            <label>Device Type</label>
                            <select name="device_type">
                                <option value="">— Select —</option>
                                @foreach(['Desktop','Laptop','Printer','Router','Switch','Server','Mobile','Other'] as $type)
                                    <option value="{{ $type }}" {{ $device->device_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Serial Number</label>
                            <input type="text" name="serial_number" value="{{ $device->serial_number }}" style="font-family:'Courier New',monospace;">
                        </div>
                        <div class="form-field">
                            <label>IP Address</label>
                            <input type="text" name="ip_address" value="{{ $device->ip_address }}" style="font-family:'Courier New',monospace;">
                        </div>
                        <div class="form-field">
                            <label>MAC Address</label>
                            <input type="text" name="mac_address" value="{{ $device->mac_address }}" style="font-family:'Courier New',monospace;">
                        </div>
                        <div class="form-field">
                            <label>Date Registered</label>
                            <input type="date" name="date_registered" value="{{ $device->date_registered?->format('Y-m-d') }}">
                        </div>
                        <div class="form-field col-span-2">
                            <label>Remarks</label>
                            <textarea name="remarks" rows="2">{{ $device->remarks }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeEditModal()" class="btn btn-muted">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
@if($total > 0)
const ctx = document.getElementById('offensePie').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Resolved', 'Dismissed'],
        datasets: [{
            data: [{{ $pending }}, {{ $resolved }}, {{ $dismissed }}],
            backgroundColor: ['#b07040', '#4a5878', '#9ca3af'],
            borderWidth: 2,
            borderColor: '#fdfcfa',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
        },
        cutout: '65%'
    }
});
@endif

function showAddOffenseModal() {
    document.getElementById('addOffenseModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeAddOffenseModal() {
    document.getElementById('addOffenseModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function showEditOffenseModal(id, title, desc, date, status) {
    document.getElementById('editOffenseTitle').value  = title;
    document.getElementById('editOffenseDesc').value   = desc;
    document.getElementById('editOffenseDate').value   = date;
    document.getElementById('editOffenseStatus').value = status;
    document.getElementById('editOffenseForm').action  = '/admin/addresses/offense/' + id + '/update';
    document.getElementById('editOffenseModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditOffenseModal() {
    document.getElementById('editOffenseModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function showEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function previewEditProfile(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('editProfilePreview');
        const wrap = document.getElementById('editProfilePreviewWrap');
        if (img) img.src = e.target.result;
        if (wrap) wrap.style.display = '';
    };
    reader.readAsDataURL(input.files[0]);
}
function previewEditDevice(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('editDeviceImgPreview');
        const wrap = document.getElementById('editDeviceImgPreviewWrap');
        if (img) img.src = e.target.result;
        if (wrap) wrap.style.display = '';
    };
    reader.readAsDataURL(input.files[0]);
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeAddOffenseModal();
        closeEditOffenseModal();
        closeEditModal();
    }
});
</script>

@endsection