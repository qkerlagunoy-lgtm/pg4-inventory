@extends('layouts.admin')

@section('title', 'My Profile')

@section('page-title', 'My Profile')

@push('modals')
{{-- Standalone avatar upload form --}}
<form id="avatarUploadForm" method="POST" action="{{ route('admin.profile.avatar') }}" enctype="multipart/form-data" style="display:none;">
    @csrf
    <input type="file" id="avatarFileInput" name="avatar" accept="image/jpeg,image/png,image/webp">
</form>

{{-- Avatar preview modal --}}
<div class="avatar-modal-backdrop" id="avatarModal">
    <div class="avatar-modal">
        <h3>Update Profile Photo</h3>
        <p>Preview your new profile picture before saving.</p>
        <div class="avatar-preview-ring" id="avatarPreviewRing">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Preview">
            @else
                <span>{{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}</span>
            @endif
        </div>
        <div class="avatar-modal-btns">
            <button type="button" class="btn-modal-cancel" id="avatarModalCancel">Cancel</button>
            <button type="button" class="btn-modal-save" id="avatarModalSave">Save Photo</button>
        </div>
    </div>
</div>
@endpush

@section('content')
<style>
:root {
    --cream:      #FAF7F0;
    --sand:       #D8D2C2;
    --sienna:     #B17457;
    --sienna-dk:  #8a5a40;
    --charcoal:   #4A4947;
    --border:     #e8e3d9;
    --text-mid:   #6b6966;
    --text-soft:  #9a9591;
    --success:    #2e7d32;
}

.admin-profile-page * { box-sizing: border-box; font-family: 'Georgia', serif; }

.admin-profile-page {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
    align-items: start;
    padding: 1.5rem;
    background: var(--cream);
    min-height: 100vh;
}
@media(max-width:900px) { .admin-profile-page { grid-template-columns: 1fr; } }

/* ── LEFT PANEL ── */
.profile-left { display: flex; flex-direction: column; gap: 1.25rem; }

/* Identity Card */
.identity-card {
    background: var(--charcoal);
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(74,73,71,.22);
    border: 1px solid rgba(177,116,87,.3);
}
.identity-card::before {
    content: '';
    position: absolute; top: -40px; right: -40px;
    width: 140px; height: 140px; border-radius: 50%;
    background: rgba(177,116,87,.12); pointer-events: none;
}
.identity-card::after {
    content: '';
    position: absolute; bottom: -30px; left: -20px;
    width: 110px; height: 110px; border-radius: 50%;
    background: rgba(216,210,194,.07); pointer-events: none;
}

.avatar-wrap {
    position: relative; width: 84px; height: 84px;
    margin: 0 auto 1rem; z-index: 1; cursor: pointer;
}
.id-avatar {
    width: 100%; height: 100%; border-radius: 50%;
    background: linear-gradient(135deg, var(--sienna), var(--sienna-dk));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.85rem; font-weight: 700; color: #FAF7F0;
    border: 3px solid rgba(250,247,240,.15);
    box-shadow: 0 6px 20px rgba(177,116,87,.35);
    overflow: hidden;
}
.id-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }

.avatar-edit-btn {
    position: absolute; bottom: 0; right: 0;
    width: 26px; height: 26px; border-radius: 50%;
    background: var(--sienna); border: 2px solid rgba(250,247,240,.9);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .15s, transform .15s;
    box-shadow: 0 2px 8px rgba(0,0,0,.25);
}
.avatar-edit-btn:hover { background: var(--sienna-dk); transform: scale(1.1); }
.avatar-edit-btn svg { width: 12px; height: 12px; color: #fff; }

.id-name { font-size: 1.1rem; font-weight: 700; color: #FAF7F0; margin-bottom: .2rem; position: relative; z-index: 1; }
.id-role {
    display: inline-block; font-size: .68rem; font-weight: 700;
    padding: .25rem .8rem; border-radius: 20px;
    background: rgba(177,116,87,.25); color: #e8c4a8;
    border: 1px solid rgba(177,116,87,.4);
    text-transform: uppercase; letter-spacing: .06em;
    position: relative; z-index: 1; margin-top: .4rem;
}

/* Avatar Modal */
.avatar-modal-backdrop {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.55); backdrop-filter: blur(3px);
    z-index: 1000; align-items: center; justify-content: center;
}
.avatar-modal-backdrop.open { display: flex; }
.avatar-modal {
    background: #fff; border-radius: 16px; padding: 2rem;
    width: 90%; max-width: 360px; text-align: center;
    box-shadow: 0 24px 60px rgba(0,0,0,.2);
}
.avatar-modal h3 { font-size: .95rem; font-weight: 700; color: var(--charcoal); margin-bottom: .25rem; }
.avatar-modal p  { font-size: .75rem; color: var(--text-soft); margin-bottom: 1.25rem; }
.avatar-preview-ring {
    width: 110px; height: 110px; border-radius: 50%;
    margin: 0 auto 1.25rem; border: 3px solid var(--border); overflow: hidden;
    background: linear-gradient(135deg, var(--sienna), var(--sienna-dk));
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; font-weight: 700; color: #FAF7F0;
}
.avatar-preview-ring img { width: 100%; height: 100%; object-fit: cover; }
.avatar-modal-btns { display: flex; gap: .75rem; justify-content: center; }
.btn-modal-cancel {
    flex: 1; padding: .6rem; border: 1.5px solid var(--border); border-radius: 8px;
    font-size: .845rem; font-weight: 600; color: var(--text-mid);
    background: #fff; cursor: pointer; font-family: 'Georgia', serif;
}
.btn-modal-cancel:hover { border-color: var(--charcoal); }
.btn-modal-save {
    flex: 1; padding: .6rem; border: none; border-radius: 8px;
    font-size: .845rem; font-weight: 600; color: #fff;
    background: var(--sienna); cursor: pointer; font-family: 'Georgia', serif;
}
.btn-modal-save:hover { background: var(--sienna-dk); }

/* Info Card */
.info-card {
    background: #fff; border-radius: 12px;
    border: 1px solid var(--border);
    box-shadow: 0 2px 10px rgba(74,73,71,.06); overflow: hidden;
}
.info-card-title {
    font-size: .68rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--text-soft);
    padding: .85rem 1.2rem .55rem; border-bottom: 1px solid var(--border);
    background: var(--cream);
}
.info-row {
    display: flex; align-items: center;
    padding: .65rem 1.2rem; border-bottom: 1px solid var(--border); gap: .7rem;
}
.info-row:last-child { border-bottom: none; }
.info-row-icon {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.info-row-icon svg { width: 13px; height: 13px; }
.iri-sienna { background: rgba(177,116,87,.12); color: var(--sienna); }
.iri-charcoal { background: rgba(74,73,71,.1); color: var(--charcoal); }
.iri-green { background: rgba(46,125,50,.1); color: #2e7d32; }
.info-row-label { font-size: .67rem; font-weight: 700; color: var(--text-soft); text-transform: uppercase; letter-spacing: .04em; }
.info-row-value { font-size: .8rem; font-weight: 600; color: var(--charcoal); margin-top: .05rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.status-dot { display: inline-flex; align-items: center; gap: .3rem; font-size: .75rem; font-weight: 600; }
.dot { width: 7px; height: 7px; border-radius: 50%; }
.dot-green { background: #4a8c4a; } .dot-red { background: #c0392b; }

/* ── RIGHT PANEL ── */
.profile-right { display: flex; flex-direction: column; gap: 1.25rem; }

.s-card {
    background: #fff; border-radius: 12px;
    border: 1px solid var(--border);
    box-shadow: 0 2px 12px rgba(74,73,71,.07); overflow: hidden;
}
.s-card-head {
    display: flex; align-items: center; gap: .9rem;
    padding: 1rem 1.4rem; border-bottom: 1px solid var(--border);
    background: var(--cream);
}
.s-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.s-icon svg { width: 17px; height: 17px; }
.si-sienna { background: rgba(177,116,87,.12); color: var(--sienna); }
.si-charcoal { background: rgba(74,73,71,.1); color: var(--charcoal); }
.s-card-title { font-size: .9rem; font-weight: 700; color: var(--charcoal); }
.s-card-desc { font-size: .72rem; color: var(--text-soft); margin-top: .1rem; }
.s-card-body { padding: 1.4rem; }

/* Avatar section inside form */
.avatar-form-section {
    display: flex; align-items: center; gap: 1.2rem;
    padding: 1rem 1.2rem; background: var(--cream);
    border-radius: 10px; border: 1.5px dashed var(--border);
    margin-bottom: 1.1rem; cursor: pointer;
    transition: border-color .15s, background .15s;
}
.avatar-form-section:hover { border-color: var(--sienna); background: #f5ede5; }
.avatar-form-thumb {
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg, var(--sienna), var(--sienna-dk));
    border: 2.5px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; font-weight: 700; color: #FAF7F0;
    overflow: hidden; flex-shrink: 0;
}
.avatar-form-thumb img { width: 100%; height: 100%; object-fit: cover; }
.avatar-form-info { flex: 1; }
.avatar-form-info strong { display: block; font-size: .82rem; font-weight: 700; color: var(--charcoal); margin-bottom: .15rem; }
.avatar-form-info span { font-size: .72rem; color: var(--text-soft); }
.avatar-upload-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--sienna); color: #fff;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.avatar-upload-icon svg { width: 15px; height: 15px; }

/* Form fields */
.f-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem 1rem; }
@media(max-width:640px) { .f-grid { grid-template-columns: 1fr; } }
.f-group { display: flex; flex-direction: column; gap: .3rem; }
.f-label { font-size: .68rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-mid); }
.req { color: var(--sienna); }
.f-input {
    padding: .58rem .8rem; border: 1.5px solid var(--border); border-radius: 8px;
    font-size: .845rem; font-family: 'Georgia', serif; color: var(--charcoal);
    background: var(--cream); outline: none; width: 100%;
    transition: border-color .15s, box-shadow .15s;
}
.f-input:focus { border-color: var(--sienna); box-shadow: 0 0 0 3px rgba(177,116,87,.1); background: #fff; }
.f-input:disabled { background: #f0ece4; color: var(--text-soft); cursor: not-allowed; border-color: #e0dbd2; }
.f-hint { font-size: .67rem; color: var(--text-soft); margin-top: .1rem; display: flex; align-items: center; gap: .25rem; }
.f-error { font-size: .7rem; color: #c0392b; }

.radio-row { display: flex; gap: .5rem; flex-wrap: wrap; }
.radio-pill {
    display: flex; align-items: center; gap: .4rem;
    padding: .48rem .9rem; border: 1.5px solid var(--border); border-radius: 8px;
    cursor: pointer; font-size: .82rem; font-weight: 500; color: var(--text-mid);
    transition: all .12s; background: var(--cream); user-select: none;
}
.radio-pill:hover { border-color: var(--sienna); color: var(--sienna); }
.radio-pill.on { border-color: var(--sienna); background: rgba(177,116,87,.08); color: var(--sienna); font-weight: 600; }
.radio-pill input { accent-color: var(--sienna); width: 13px; height: 13px; }

.f-actions {
    display: flex; align-items: center; gap: 1rem;
    padding-top: 1rem; margin-top: 1rem; border-top: 1px solid var(--border);
}
.btn-save {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .58rem 1.3rem; border-radius: 8px; font-size: .845rem;
    font-weight: 600; font-family: 'Georgia', serif; cursor: pointer;
    border: none; background: var(--sienna); color: #fff;
    box-shadow: 0 2px 8px rgba(177,116,87,.28); transition: all .15s;
}
.btn-save:hover { background: var(--sienna-dk); transform: translateY(-1px); }
.btn-save svg { width: 14px; height: 14px; }
.save-flash {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .78rem; color: var(--success); font-weight: 600;
    background: rgba(46,125,50,.08); padding: .38rem .8rem;
    border-radius: 7px; border: 1px solid rgba(46,125,50,.18);
}
.save-flash svg { width: 13px; height: 13px; }

/* Request History Table */
.req-table { width: 100%; border-collapse: collapse; }
.req-table thead tr { background: var(--cream); }
.req-table thead th {
    padding: .6rem 1rem; text-align: left; font-size: .67rem;
    font-weight: 700; color: var(--text-soft); letter-spacing: .06em;
    text-transform: uppercase; white-space: nowrap;
    border-bottom: 2px solid var(--sand);
}
.req-table tbody tr { border-bottom: 1px solid #f0ece4; transition: background .1s; }
.req-table tbody tr:last-child { border-bottom: none; }
.req-table tbody tr:hover { background: #fdfbf7; }
.req-table td { padding: .8rem 1rem; font-size: .82rem; color: var(--charcoal); vertical-align: middle; }
.req-table td.muted { color: var(--text-soft); }

.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: .22rem .6rem; border-radius: 20px; font-size: .67rem; font-weight: 700; white-space: nowrap;
}
.badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
.b-pending  { background: rgba(245,158,11,.1);  color: #b45309; } .b-pending  .badge-dot { background: #f59e0b; }
.b-approved { background: rgba(46,125,50,.1);   color: #2e7d32; } .b-approved .badge-dot { background: #4a8c4a; }
.b-rejected { background: rgba(74,73,71,.1);    color: var(--charcoal); } .b-rejected .badge-dot { background: var(--charcoal); }
.b-cancelled{ background: rgba(245,158,11,.08); color: #92640a; } .b-cancelled .badge-dot { background: #d97706; }
.b-other    { background: #f0ece4; color: var(--text-soft); } .b-other .badge-dot { background: var(--text-soft); }

.priority-badge {
    display: inline-flex; align-items: center; gap: 3px;
    padding: .18rem .5rem; border-radius: 6px; font-size: .66rem; font-weight: 700;
}
.p-urgent { background: rgba(192,57,43,.12); color: #a02f23; }
.p-high   { background: rgba(220,38,38,.08); color: #b91c1c; }
.p-medium { background: rgba(74,73,71,.1);   color: var(--charcoal); }
.p-low    { background: rgba(46,125,50,.08); color: #2e7d32; }

.empty-state { text-align: center; padding: 2.5rem 1rem; display: flex; flex-direction: column; align-items: center; gap: .6rem; }
.empty-icon { width: 44px; height: 44px; border-radius: 10px; background: var(--cream); display: flex; align-items: center; justify-content: center; color: var(--sand); }
.empty-icon svg { width: 20px; height: 20px; }
.empty-state p { font-size: .82rem; color: var(--text-soft); font-weight: 500; }
</style>

<div class="admin-profile-page">

    {{-- ══ LEFT PANEL ══ --}}
    <aside class="profile-left">

        {{-- Identity Card --}}
        <div class="identity-card">
            <div class="avatar-wrap" onclick="triggerAvatarUpload()" title="Change profile photo">
                <div class="id-avatar">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" id="sidebarAvatar" alt="{{ $user->first_name }}">
                    @else
                        <span id="sidebarAvatarInitials">{{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}</span>
                    @endif
                </div>
                <div class="avatar-edit-btn" title="Upload photo">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="id-name">{{ $user->first_name }} {{ $user->last_name }}</div>
            <div class="id-role">{{ ucfirst($user->type) }}</div>
        </div>

        {{-- Account Details --}}
        <div class="info-card">
            <div class="info-card-title">Account Details</div>
            <div class="info-row">
                <div class="info-row-icon iri-charcoal">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="info-row-label">Email</div>
                    <div class="info-row-value">{{ $user->email }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-row-icon iri-sienna">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="info-row-label">Member Since</div>
                    <div class="info-row-value">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-row-icon iri-green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
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

        {{-- Personal Information --}}
        <div class="s-card">
            <div class="s-card-head">
                <div class="s-icon si-sienna">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <div class="s-card-title">Personal Information</div>
                    <div class="s-card-desc">Update your name, email, and profile photo.</div>
                </div>
            </div>
            <div class="s-card-body">

                {{-- Avatar click zone --}}
                <div class="avatar-form-section" onclick="triggerAvatarUpload()" title="Click to change photo">
                    <div class="avatar-form-thumb" id="formAvatarThumb">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" id="formAvatarImg" alt="Profile">
                        @else
                            <span id="formAvatarInitials">{{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}</span>
                        @endif
                    </div>
                    <div class="avatar-form-info">
                        <strong>Profile Photo</strong>
                        <span>Click to upload · JPG, PNG or WebP · Max 2MB</span>
                    </div>
                    <div class="avatar-upload-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="f-grid" style="margin-bottom:.85rem;">
                        <div class="f-group">
                            <label class="f-label">First Name <span class="req">*</span></label>
                            <input class="f-input" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')<p class="f-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="f-group">
                            <label class="f-label">Last Name <span class="req">*</span></label>
                            <input class="f-input" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')<p class="f-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="f-group">
                            <label class="f-label">Middle Name</label>
                            <input class="f-input" type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" placeholder="Optional">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Suffix</label>
                            <input class="f-input" type="text" name="suffix" value="{{ old('suffix', $user->suffix) }}" placeholder="Jr., Sr., III">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Username <span class="req">*</span></label>
                            <input class="f-input" type="text" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')<p class="f-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="f-group">
                            <label class="f-label">Email Address <span class="req">*</span></label>
                            <input class="f-input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<p class="f-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="f-group" style="margin-bottom:.85rem;">
                        <label class="f-label">Gender</label>
                        <div class="radio-row">
                            <label class="radio-pill {{ old('sex', $user->sex) === 'male' ? 'on' : '' }}">
                                <input type="radio" name="sex" value="male" {{ old('sex', $user->sex) === 'male' ? 'checked' : '' }} onchange="togglePill(this)"> Male
                            </label>
                            <label class="radio-pill {{ old('sex', $user->sex) === 'female' ? 'on' : '' }}">
                                <input type="radio" name="sex" value="female" {{ old('sex', $user->sex) === 'female' ? 'checked' : '' }} onchange="togglePill(this)"> Female
                            </label>
                        </div>
                    </div>

                    <div class="f-grid" style="margin-bottom:0;">
                        <div class="f-group">
                            <label class="f-label">Role</label>
                            <input class="f-input" type="text" value="{{ ucfirst($user->type) }}" disabled>
                            <span class="f-hint">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Role is managed by the system.
                            </span>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Unit / Department</label>
                            <input class="f-input" type="text" value="{{ $user->formatted_unit ?? ($user->unit ?? 'N/A') }}" disabled>
                        </div>
                    </div>

                    <div class="f-actions">
                        <button type="submit" class="btn-save">
                            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Save Changes
                        </button>
                        @if(session('status') === 'profile-updated')
                            <span class="save-flash">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Profile updated!
                            </span>
                        @endif
                        @if(session('status') === 'avatar-updated')
                            <span class="save-flash">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Photo updated!
                            </span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="s-card">
            <div class="s-card-head">
                <div class="s-icon si-charcoal">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <div class="s-card-title">Recent Activity</div>
                    <div class="s-card-desc">Requests you have recently approved or rejected.</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="req-table">
                    <thead>
                        <tr>
                            <th>Request #</th>
                            <th>Requester</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivity as $req)
                            <tr>
                                <td style="font-weight:600;">#{{ $req->id }}</td>
                                <td>{{ $req->user->first_name ?? '' }} {{ $req->user->last_name ?? '' }}</td>
                                <td class="muted">{{ Str::limit($req->purpose, 35) }}</td>
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
                                        <span class="badge-dot"></span>{{ ucfirst($req->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $pc = match($req->priority ?? 'medium') {
                                            'urgent' => 'p-urgent',
                                            'high'   => 'p-high',
                                            'medium' => 'p-medium',
                                            'low'    => 'p-low',
                                            default  => 'p-medium',
                                        };
                                    @endphp
                                    <span class="priority-badge {{ $pc }}">{{ ucfirst($req->priority ?? 'medium') }}</span>
                                </td>
                                <td class="muted">{{ $req->updated_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon"><svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                                        <p>No recent activity found.</p>
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

@push('scripts')
<script>
function togglePill(input) {
    document.querySelectorAll('.radio-pill').forEach(p => p.classList.remove('on'));
    input.closest('.radio-pill').classList.add('on');
}

const fileInput   = document.getElementById('avatarFileInput');
const modal       = document.getElementById('avatarModal');
const previewRing = document.getElementById('avatarPreviewRing');
let selectedFile  = null;

function triggerAvatarUpload() { fileInput.click(); }

fileInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('File too large. Max 2MB.');
        this.value = '';
        return;
    }
    selectedFile = file;
    const reader = new FileReader();
    reader.onload = function (e) {
        previewRing.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        // Update sidebar avatar
        const sb = document.getElementById('sidebarAvatar');
        if (sb) { sb.src = e.target.result; }
        else { const w = document.querySelector('.id-avatar'); if (w) w.innerHTML = `<img src="${e.target.result}" id="sidebarAvatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`; }
        // Update form thumb
        const fi = document.getElementById('formAvatarImg');
        if (fi) { fi.src = e.target.result; }
        else { const t = document.getElementById('formAvatarThumb'); if (t) t.innerHTML = `<img src="${e.target.result}" id="formAvatarImg" style="width:100%;height:100%;object-fit:cover;">`; }
        modal.classList.add('open');
    };
    reader.readAsDataURL(file);
});

document.getElementById('avatarModalCancel').addEventListener('click', function () {
    modal.classList.remove('open'); fileInput.value = ''; selectedFile = null;
});
document.getElementById('avatarModalSave').addEventListener('click', function () {
    if (!selectedFile) return;
    document.getElementById('avatarUploadForm').submit();
});
modal.addEventListener('click', function (e) {
    if (e.target === modal) { modal.classList.remove('open'); fileInput.value = ''; selectedFile = null; }
});
</script>
@endpush

@endsection