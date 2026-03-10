@extends('layouts.user')

@section('title', 'My Profile')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
    --cream:      #FCF8F3;
    --teal:       #AEDADD;
    --teal-dark:  #7bbfc3;
    --terra:      #DB996C;
    --slate:      #6E7DA2;
    --slate-dark: #4e5d82;
    --cream-dark: #EDE5D8;
    --border:     #eaecf2;
    --text-dark:  #1e2535;
    --text-mid:   #5A5A5A;
    --text-soft:  #9ca3af;
    --success:    #4a8c4a;
    --bg:         #f4f5f8;
}

.profile-page * { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

.profile-page {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.5rem;
    align-items: start;
}
@media(max-width: 900px) {
    .profile-page { grid-template-columns: 1fr; }
}

/* ══ LEFT PANEL ══ */
.profile-left { display: flex; flex-direction: column; gap: 1.25rem; }

/* Identity Card */
.identity-card {
    background: linear-gradient(145deg, var(--slate-dark), var(--slate));
    border-radius: 20px;
    padding: 2rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(78,93,130,.22);
}
.identity-card::before {
    content: '';
    position: absolute; top: -50px; right: -50px;
    width: 160px; height: 160px; border-radius: 50%;
    background: rgba(174,218,221,.1); pointer-events: none;
}
.identity-card::after {
    content: '';
    position: absolute; bottom: -30px; left: -20px;
    width: 120px; height: 120px; border-radius: 50%;
    background: rgba(219,153,108,.08); pointer-events: none;
}

.id-avatar {
    width: 80px; height: 80px; border-radius: 50%;
    background: linear-gradient(135deg, var(--teal), var(--terra));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; font-weight: 700; color: #fff;
    margin: 0 auto 1rem;
    border: 3px solid rgba(255,255,255,.2);
    box-shadow: 0 6px 20px rgba(0,0,0,.2);
    position: relative; z-index: 1;
}
.id-name {
    font-size: 1.05rem; font-weight: 700; color: #fff;
    margin-bottom: .25rem; position: relative; z-index: 1;
    line-height: 1.3;
}
.id-username {
    font-size: .78rem; color: rgba(255,255,255,.6);
    margin-bottom: .85rem; position: relative; z-index: 1;
}
.id-badges {
    display: flex; gap: .5rem; justify-content: center;
    flex-wrap: wrap; position: relative; z-index: 1;
}
.id-badge {
    font-size: .68rem; font-weight: 600;
    padding: .25rem .75rem; border-radius: 20px;
}
.id-badge-role {
    background: rgba(174,218,221,.2); color: var(--teal);
    border: 1px solid rgba(174,218,221,.35);
}
.id-badge-unit {
    background: rgba(219,153,108,.2); color: #f0b992;
    border: 1px solid rgba(219,153,108,.35);
}

/* Stats Mini */
.stats-mini {
    background: #fff;
    border-radius: 16px;
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(110,125,162,.06);
}
.stats-mini-title {
    font-size: .7rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--text-soft);
    padding: 1rem 1.25rem .6rem;
    border-bottom: 1px solid var(--border);
}
.stats-mini-grid {
    display: grid; grid-template-columns: 1fr 1fr;
}
.stat-mini-item {
    padding: 1rem 1.1rem;
    border-right: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    text-align: center;
}
.stat-mini-item:nth-child(2n) { border-right: none; }
.stat-mini-item:nth-last-child(-n+2) { border-bottom: none; }
.stat-mini-num {
    font-size: 1.5rem; font-weight: 700; line-height: 1;
    margin-bottom: .25rem;
}
.stat-mini-label {
    font-size: .68rem; font-weight: 600;
    color: var(--text-soft); text-transform: uppercase; letter-spacing: .04em;
}
.c-slate  { color: var(--slate); }
.c-terra  { color: var(--terra); }
.c-green  { color: #4a8c4a; }
.c-amber  { color: #d97706; }

/* Account Info */
.info-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(110,125,162,.06);
}
.info-card-title {
    font-size: .7rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--text-soft);
    padding: 1rem 1.25rem .6rem;
    border-bottom: 1px solid var(--border);
}
.info-row {
    display: flex; align-items: center;
    padding: .7rem 1.25rem;
    border-bottom: 1px solid var(--border);
    gap: .75rem;
}
.info-row:last-child { border-bottom: none; }
.info-row-icon {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.info-row-icon svg { width: 13px; height: 13px; }
.iri-slate { background: rgba(110,125,162,.1); color: var(--slate); }
.iri-teal  { background: rgba(174,218,221,.2); color: var(--teal-dark); }
.iri-terra { background: rgba(219,153,108,.1); color: var(--terra); }
.iri-green { background: rgba(74,140,74,.1);   color: #4a7c4a; }
.info-row-content { flex: 1; min-width: 0; }
.info-row-label { font-size: .68rem; font-weight: 600; color: var(--text-soft); text-transform: uppercase; letter-spacing: .04em; }
.info-row-value { font-size: .8rem; font-weight: 600; color: var(--text-dark); margin-top: .05rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.status-dot {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .75rem; font-weight: 600;
}
.dot { width: 7px; height: 7px; border-radius: 50%; }
.dot-green { background: #4a8c4a; }
.dot-red   { background: #c0392b; }

/* ══ RIGHT PANEL ══ */
.profile-right { display: flex; flex-direction: column; gap: 1.25rem; }

/* Section Card */
.s-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(110,125,162,.07);
    transition: box-shadow .2s;
}
.s-card:hover { box-shadow: 0 4px 28px rgba(110,125,162,.11); }

.s-card-head {
    display: flex; align-items: center; gap: .9rem;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(to right, #fafbfd, #fff);
}
.s-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.s-icon svg { width: 18px; height: 18px; }
.si-blue  { background: rgba(110,125,162,.1); color: var(--slate); }
.si-teal  { background: rgba(174,218,221,.2); color: var(--teal-dark); }
.s-card-title { font-size: .92rem; font-weight: 700; color: var(--text-dark); }
.s-card-desc  { font-size: .73rem; color: var(--text-soft); margin-top: .1rem; }
.s-card-body  { padding: 1.5rem; }

/* Form */
.f-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem 1.1rem; }
.f-grid.g1 { grid-template-columns: 1fr; }
.f-grid.g3 { grid-template-columns: 1fr 1fr 1fr; }
@media(max-width:640px){ .f-grid, .f-grid.g3 { grid-template-columns: 1fr; } }

.f-group { display: flex; flex-direction: column; gap: .3rem; }
.f-label {
    font-size: .7rem; font-weight: 700;
    letter-spacing: .05em; text-transform: uppercase; color: var(--text-mid);
    display: flex; gap: .2rem;
}
.req { color: var(--terra); }

.f-input, .f-select {
    padding: .6rem .85rem;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    font-size: .845rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-dark);
    background: var(--cream);
    outline: none;
    width: 100%;
    transition: border-color .15s, box-shadow .15s, background .15s;
}
.f-input:focus, .f-select:focus {
    border-color: var(--slate);
    box-shadow: 0 0 0 3px rgba(110,125,162,.1);
    background: #fff;
}
.f-input:disabled {
    background: #f3f4f6; color: var(--text-soft);
    cursor: not-allowed; border-color: #e5e7eb;
}
.f-input::placeholder { color: var(--text-soft); font-size: .82rem; }
.f-hint { font-size: .68rem; color: var(--text-soft); margin-top: .1rem; display: flex; align-items: center; gap: .2rem; }
.f-error { font-size: .7rem; color: #e53e3e; }

/* Radio */
.radio-row { display: flex; gap: .6rem; flex-wrap: wrap; }
.radio-pill {
    display: flex; align-items: center; gap: .4rem;
    padding: .5rem .95rem;
    border: 1.5px solid var(--border);
    border-radius: 9px; cursor: pointer;
    font-size: .825rem; font-weight: 500; color: var(--text-mid);
    transition: all .12s; background: var(--cream); user-select: none;
}
.radio-pill:hover { border-color: var(--slate); color: var(--slate); }
.radio-pill.on { border-color: var(--slate); background: rgba(110,125,162,.08); color: var(--slate); font-weight: 600; }
.radio-pill input { accent-color: var(--slate); width: 13px; height: 13px; }

/* Actions */
.f-actions {
    display: flex; align-items: center; gap: 1rem;
    padding-top: 1.1rem; margin-top: 1.1rem;
    border-top: 1px solid var(--border);
}
.btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .6rem 1.35rem; border-radius: 9px;
    font-size: .845rem; font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; border: none; transition: all .15s;
}
.btn svg { width: 14px; height: 14px; }
.btn-slate {
    background: var(--slate); color: #fff;
    box-shadow: 0 2px 10px rgba(110,125,162,.28);
}
.btn-slate:hover { background: var(--slate-dark); transform: translateY(-1px); }
.save-flash {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .78rem; color: var(--success); font-weight: 600;
    background: rgba(74,140,74,.08);
    padding: .4rem .85rem; border-radius: 8px;
    border: 1px solid rgba(74,140,74,.18);
}
.save-flash svg { width: 13px; height: 13px; }

/* Request History Table */
.req-table { width: 100%; border-collapse: collapse; }
.req-table thead tr { background: #f8f9fb; }
.req-table thead th {
    padding: .65rem 1rem;
    text-align: left; font-size: .68rem; font-weight: 700;
    color: var(--text-soft); letter-spacing: .06em;
    text-transform: uppercase; white-space: nowrap;
    border-bottom: 1px solid var(--border);
}
.req-table tbody tr { border-bottom: 1px solid #f2f3f7; transition: background .1s; }
.req-table tbody tr:last-child { border-bottom: none; }
.req-table tbody tr:hover { background: #fafbfd; }
.req-table td { padding: .8rem 1rem; font-size: .82rem; color: var(--text-dark); vertical-align: middle; }
.req-table td.muted { color: var(--text-soft); }

.purpose-cell {
    max-width: 220px; white-space: nowrap;
    overflow: hidden; text-overflow: ellipsis;
    font-weight: 500;
}
.tracking-cell { font-family: monospace; font-size: .75rem; color: var(--text-soft); }

.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: .25rem .65rem; border-radius: 20px;
    font-size: .68rem; font-weight: 700; white-space: nowrap;
}
.badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
.b-pending  { background: rgba(245,158,11,.1);  color: #b45309; }
.b-pending  .badge-dot { background: #f59e0b; }
.b-approved { background: rgba(74,140,74,.1);   color: #4a7c4a; }
.b-approved .badge-dot { background: #4a8c4a; }
.b-rejected { background: rgba(110,125,162,.1); color: var(--slate-dark); }
.b-rejected .badge-dot { background: var(--slate); }
.b-cancelled{ background: rgba(245,158,11,.08); color: #92640a; }
.b-cancelled .badge-dot { background: #d97706; }
.b-other    { background: #f3f4f6; color: var(--text-soft); }
.b-other    .badge-dot { background: var(--text-soft); }

.priority-badge {
    display: inline-flex; align-items: center; gap: 3px;
    padding: .2rem .55rem; border-radius: 6px;
    font-size: .67rem; font-weight: 700;
}
.p-urgent { background: rgba(219,153,108,.15); color: #a0522d; }
.p-high   { background: rgba(220,38,38,.08);   color: #b91c1c; }
.p-medium { background: rgba(110,125,162,.1);  color: var(--slate-dark); }
.p-low    { background: rgba(74,140,74,.08);   color: #4a7c4a; }

.empty-state {
    text-align: center; padding: 3rem 1rem;
    display: flex; flex-direction: column; align-items: center; gap: .6rem;
}
.empty-icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: #f4f5f8;
    display: flex; align-items: center; justify-content: center; color: #c4c8d4;
    margin-bottom: .25rem;
}
.empty-icon svg { width: 22px; height: 22px; }
.empty-state p { font-size: .83rem; color: var(--text-soft); font-weight: 500; }
</style>

<div class="profile-page">

    {{-- ══ LEFT PANEL ══ --}}
    <aside class="profile-left">

        {{-- Identity Card --}}
        <div class="identity-card">
            <div class="id-avatar">
                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
            </div>
            <div class="id-name">{{ $user->first_name }} {{ $user->last_name }}</div>
            <div class="id-username">@{{ $user->username }}</div>
            <div class="id-badges">
                <span class="id-badge id-badge-role">{{ ucfirst($user->type) }}</span>
                @if($user->unit)
                    <span class="id-badge id-badge-unit">{{ $user->unit }}</span>
                @endif
            </div>
        </div>

        {{-- Request Stats --}}
        <div class="stats-mini">
            <div class="stats-mini-title">Request Summary</div>
            <div class="stats-mini-grid">
                <div class="stat-mini-item">
                    <div class="stat-mini-num c-amber">{{ $user->pendingRequests()->count() }}</div>
                    <div class="stat-mini-label">Pending</div>
                </div>
                <div class="stat-mini-item">
                    <div class="stat-mini-num c-green">{{ $user->approvedRequests()->count() }}</div>
                    <div class="stat-mini-label">Approved</div>
                </div>
                <div class="stat-mini-item">
                    <div class="stat-mini-num c-slate">{{ $user->rejectedRequests()->count() }}</div>
                    <div class="stat-mini-label">Rejected</div>
                </div>
                <div class="stat-mini-item">
                    <div class="stat-mini-num c-terra">{{ $user->cancelledRequests()->count() }}</div>
                    <div class="stat-mini-label">Cancelled</div>
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="info-card">
            <div class="info-card-title">Account Details</div>

            <div class="info-row">
                <div class="info-row-icon iri-slate">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="info-row-content">
                    <div class="info-row-label">Email</div>
                    <div class="info-row-value">{{ $user->email }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-row-icon iri-teal">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="info-row-content">
                    <div class="info-row-label">Unit</div>
                    <div class="info-row-value">{{ $user->formatted_unit }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-row-icon iri-terra">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="info-row-content">
                    <div class="info-row-label">Member Since</div>
                    <div class="info-row-value">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-row-icon iri-green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="info-row-content">
                    <div class="info-row-label">Status</div>
                    <div class="info-row-value">
                        <span class="status-dot">
                            <span class="dot {{ $user->is_active ? 'dot-green' : 'dot-red' }}"></span>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </aside>

    {{-- ══ RIGHT PANEL ══ --}}
    <div class="profile-right">

        {{-- Profile Edit Form --}}
        <div class="s-card">
            <div class="s-card-head">
                <div class="s-icon si-blue">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="s-card-title">Personal Information</div>
                    <div class="s-card-desc">Update your name, email, and contact details.</div>
                </div>
            </div>
            <div class="s-card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="f-grid" style="margin-bottom:.85rem;">
                        <div class="f-group">
                            <label class="f-label">First Name <span class="req">*</span></label>
                            <input class="f-input" type="text" name="first_name"
                                   value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')<p class="f-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="f-group">
                            <label class="f-label">Last Name <span class="req">*</span></label>
                            <input class="f-input" type="text" name="last_name"
                                   value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')<p class="f-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="f-group">
                            <label class="f-label">Middle Name</label>
                            <input class="f-input" type="text" name="middle_name"
                                   value="{{ old('middle_name', $user->middle_name) }}"
                                   placeholder="Optional">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Suffix</label>
                            <input class="f-input" type="text" name="suffix"
                                   value="{{ old('suffix', $user->suffix) }}"
                                   placeholder="Jr., Sr., III">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Username <span class="req">*</span></label>
                            <input class="f-input" type="text" name="username"
                                   value="{{ old('username', $user->username) }}" required>
                            @error('username')<p class="f-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="f-group">
                            <label class="f-label">Email Address <span class="req">*</span></label>
                            <input class="f-input" type="email" name="email"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<p class="f-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="f-group" style="margin-bottom:.85rem;">
                        <label class="f-label">Gender</label>
                        <div class="radio-row">
                            <label class="radio-pill {{ old('sex', $user->sex) === 'male' ? 'on' : '' }}">
                                <input type="radio" name="sex" value="male"
                                       {{ old('sex', $user->sex) === 'male' ? 'checked' : '' }}
                                       onchange="togglePill(this)"> Male
                            </label>
                            <label class="radio-pill {{ old('sex', $user->sex) === 'female' ? 'on' : '' }}">
                                <input type="radio" name="sex" value="female"
                                       {{ old('sex', $user->sex) === 'female' ? 'checked' : '' }}
                                       onchange="togglePill(this)"> Female
                            </label>
                        </div>
                    </div>

                    <div class="f-grid" style="margin-bottom:0;">
                        <div class="f-group">
                            <label class="f-label">Unit / Department</label>
                            <input class="f-input" type="text" value="{{ $user->formatted_unit }}" disabled>
                            <span class="f-hint">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Contact admin to change your unit.
                            </span>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Role</label>
                            <input class="f-input" type="text" value="{{ ucfirst($user->type) }}" disabled>
                        </div>
                    </div>

                    <div class="f-actions">
                        <button type="submit" class="btn btn-slate">
                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                        @if(session('status') === 'profile-updated')
                            <span class="save-flash">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Profile updated successfully!
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Request History --}}
        <div class="s-card">
            <div class="s-card-head">
                <div class="s-icon si-teal">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <div class="s-card-title">Request History</div>
                    <div class="s-card-desc">Your recent item requests and their current status.</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="req-table">
                    <thead>
                        <tr>
                            <th>Purpose</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Tracking</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->itemRequests()->latest()->limit(8)->get() as $req)
                            <tr>
                                <td>
                                    <span class="purpose-cell" title="{{ $req->purpose }}">
                                        {{ Str::limit($req->purpose, 40) ?? '—' }}
                                    </span>
                                </td>
                                <td class="muted">{{ $req->created_at->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $sc = match($req->status) {
                                            'pending'   => 'b-pending',
                                            'approved'  => 'b-approved',
                                            'rejected'  => 'b-rejected',
                                            'cancelled' => 'b-cancelled',
                                            default     => 'b-other',
                                        };
                                    @endphp
                                    <span class="badge {{ $sc }}">
                                        <span class="badge-dot"></span>
                                        {{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $pc = match($req->priority) {
                                            'urgent' => 'p-urgent',
                                            'high'   => 'p-high',
                                            'medium' => 'p-medium',
                                            'low'    => 'p-low',
                                            default  => 'p-medium',
                                        };
                                    @endphp
                                    <span class="priority-badge {{ $pc }}">
                                        {{ ucfirst($req->priority ?? 'medium') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="tracking-cell">
                                        {{ $req->tracking_number ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p>No requests found yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
function togglePill(input) {
    document.querySelectorAll('.radio-pill').forEach(p => p.classList.remove('on'));
    input.closest('.radio-pill').classList.add('on');
}
</script>

@endsection