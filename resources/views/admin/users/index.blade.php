@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

/* ── PAGE WRAPPER ── */
.users-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}

/* ── FLASH MESSAGES ── */
.flash {
    position: relative;
    padding: .85rem 3rem .85rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: .875rem;
    font-weight: 600;
}
.flash-success { background: #f0faf0; border: 1px solid #6aab6a; color: #2e6b2e; }
.flash-error   { background: #fff0f0; border: 1px solid #d87070; color: #8b2020; }
.flash-close {
    position: absolute;
    top: 0; right: 0; bottom: 0;
    padding: 0 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: inherit;
    opacity: .6;
    transition: opacity .15s;
}
.flash-close:hover { opacity: 1; }

/* ── FILTER BAR ── */
.filter-bar {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.25rem;
}
.filter-form { display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; }
.filter-field { min-width: 150px; }
.filter-field-search { flex: 1; min-width: 250px; }
.filter-label {
    display: block;
    font-size: .75rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .4rem;
    letter-spacing: .02em;
}
.filter-input,
.filter-select {
    width: 100%;
    padding: .5rem .9rem;
    font-size: .875rem;
    font-family: inherit;
    background: var(--cream);
    border: 1px solid var(--sand);
    border-radius: 7px;
    color: var(--charcoal);
    outline: none;
    transition: border-color .2s;
}
.filter-input:focus,
.filter-select:focus { border-color: var(--sienna); }

.btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .5rem 1.25rem;
    font-size: .875rem;
    font-weight: 600;
    font-family: inherit;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s, transform .1s;
}
.btn:hover  { opacity: .88; transform: translateY(-1px); }
.btn:active { transform: translateY(0); }
.btn-primary { background: var(--sienna);  color: #fff; }
.btn-muted   { background: #6b6966; color: #fff; }

/* ── STATS CARDS ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.stat-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.1rem;
}
.stat-label { font-size: .8rem; color: #6b6966; margin-bottom: .3rem; }
.stat-value { font-size: 1.75rem; font-weight: 700; color: var(--charcoal); }
.stat-value.green  { color: #4a8c4a; }
.stat-value.red    { color: #c0392b; }
.stat-value.blue   { color: #4a7fb5; }

/* ── TABLE CARD ── */
.table-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1rem;
}
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .875rem; }
thead tr { background: var(--cream); border-bottom: 2px solid var(--sand); }
thead th {
    padding: .85rem 1.2rem;
    text-align: left;
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--charcoal);
}
thead th:last-child { text-align: center; }
tbody tr { border-bottom: 1px solid #f0ece4; transition: background .12s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fdfbf7; }
tbody tr:nth-child(even) { background: #faf8f3; }
tbody tr:nth-child(even):hover { background: #f5f1e8; }
tbody td {
    padding: .85rem 1.2rem;
    color: var(--charcoal);
    vertical-align: middle;
}
tbody td:last-child { text-align: center; }
.td-name { font-weight: 600; color: var(--sienna); }

/* ── BADGES ── */
.badge {
    display: inline-block;
    padding: .25rem .65rem;
    font-size: .7rem;
    font-weight: 700;
    border-radius: 6px;
    letter-spacing: .04em;
    text-transform: uppercase;
}
.badge-admin    { background: #e8dcf4; color: #6b3da3; border: 1px solid #a975d9; }
.badge-user     { background: #d9ebf7; color: #2d5f8a; border: 1px solid #6ba3d4; }
.badge-active   { background: #eef6ee; color: #2e7d32; border: 1px solid #6aab6a; }
.badge-inactive { background: #ffe6e6; color: #a02f23; border: 1px solid #d87070; }

/* ── ACTION BUTTONS (small) ── */
.btn-sm {
    padding: .35rem .75rem;
    font-size: .75rem;
    font-weight: 600;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: opacity .15s;
}
.btn-sm:hover { opacity: .88; }
.btn-sm-edit   { background: #4a7fb5; color: #fff; }
.btn-sm-delete { background: #c0392b; color: #fff; }
.btn-sm-disabled { background: #9a9591; color: #fff; cursor: not-allowed; opacity: .5; }

/* ── EMPTY STATE ── */
.empty-state {
    padding: 4rem 1rem;
    text-align: center;
}
.empty-state svg { color: var(--sand); margin: 0 auto 1rem; display: block; }
.empty-state h3  { font-size: 1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .25rem; }
.empty-state p   { font-size: .85rem; color: #9a9591; }

/* ── PAGINATION INFO ── */
.pagination-info {
    margin-top: 1rem;
    font-size: .85rem;
    color: #6b6966;
}

/* ── DIVIDER ── */
.divider {
    border: none;
    border-top: 1px solid var(--sand);
    margin: 1.5rem 0;
}

/* ── BOTTOM ACTIONS ── */
.bottom-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    margin-bottom: 2rem;
}

/* ── PAGINATION (override Tailwind) ── */
.pagination-wrap nav span[aria-current="page"] > span,
.pagination-wrap nav a {
    border-color: var(--sand) !important;
    color: var(--charcoal) !important;
}
.pagination-wrap nav span[aria-current="page"] > span {
    background: var(--sienna) !important;
    color: #fff !important;
    border-color: var(--sienna) !important;
}

/* ── PRINT STYLES ── */
@media print {
    .filter-bar, .bottom-actions, .no-print { display: none; }
    body { background: white; }
    tbody tr, tbody tr:nth-child(even) { background: white !important; }
}

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .table-wrap { -webkit-overflow-scrolling: touch; }
}
</style>

<div class="users-page">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash flash-success">
            {{ session('success') }}
            <button type="button" class="flash-close" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="flash flash-error">
            {{ session('error') }}
            <button type="button" class="flash-close" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="flash flash-error">
            <ul style="margin:0; padding-left:1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="flash-close" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    {{-- Filters and Search --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.users.index') }}" class="filter-form">

            <div class="filter-field">
                <label for="status" class="filter-label">Filter by Status</label>
                <select name="status" id="status" class="filter-select">
                    <option value="">All Users</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="filter-field">
                <label for="unit" class="filter-label">Filter by Unit</label>
                <select name="unit" id="unit" class="filter-select">
                    <option value="">All Units</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->unit }}" {{ request('unit') == $unit->unit ? 'selected' : '' }}>
                            {{ $unit->unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-field-search">
                <label for="search" class="filter-label">Search</label>
                <input type="text"
                       name="search"
                       id="search"
                       placeholder="Search by name, username, or email…"
                       value="{{ request('search') }}"
                       class="filter-input">
            </div>

            <div>
                <button type="submit" class="btn btn-primary">🔍 Search</button>
            </div>

            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-muted">🔄 Reset</a>
            </div>
        </form>
    </div>

    {{-- Statistics Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $users->total() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active Users</div>
            <div class="stat-value green">{{ App\Models\User::whereNotNull('email_verified_at')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Inactive Users</div>
            <div class="stat-value red">{{ App\Models\User::whereNull('email_verified_at')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Admin Users</div>
            <div class="stat-value blue">{{ App\Models\User::where('type', 'admin')->count() }}</div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="table-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                       
                       <th></th>  {{-- avatar col --}}
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Unit</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
<td>
    @if($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}"
             style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid #D8D2C2;">
    @else
        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#AEDADD,#DB996C);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:#fff;border:2px solid #D8D2C2;">
            {{ strtoupper(substr($user->first_name,0,1)) }}{{ strtoupper(substr($user->last_name,0,1)) }}
        </div>
    @endif
</td>
<td class="td-name">{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->unit ?? 'N/A' }}</td>
                            <td>
                                @if($user->type == 'admin')
                                    <span class="badge badge-admin">Admin</span>
                                @else
                                    <span class="badge badge-user">User</span>
                                @endif
                            </td>
                            <td>
                                @if($user->email_verified_at !== null)
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex; justify-content:center; gap:.4rem;">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="btn-sm btn-sm-edit"
                                       title="Edit User">✏️ Edit</a>
                                       @if(!$user->email_verified_at || !$user->is_active)

                                           {{-- Activate Button (only show if inactive) --}}
                        <form action="{{ route('admin.users.activate', $user->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Activate {{ $user->first_name }} {{ $user->last_name }}?');"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit"
                                                class="btn-sm"
                                                style="background:#4a8c4a; color:#fff;"
                                                title="Activate User">✅ Activate</button>
                                    </form>
                                @endif
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete {{ $user->first_name }} {{ $user->last_name }}? This action cannot be undone.');"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn-sm btn-sm-delete"
                                                    title="Delete User">🗑️ Delete</button>
                                        </form>
                                    @else
                                        <button type="button"
                                                class="btn-sm btn-sm-disabled"
                                                title="You cannot delete your own account"
                                                disabled>🗑️ Delete</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <svg width="56" height="56" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <h3>No users found</h3>
                                    <p>Try adjusting your search or filter criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Info --}}
    @if($users->total() > 0)
        <div class="pagination-info">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </div>
    @endif

    <hr class="divider">

    {{-- Bottom Action Buttons --}}
    <div class="bottom-actions">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            ➕ New User
        </a>
        <a href="{{ route('admin.users.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
           class="btn btn-primary">
            📄 Generate PDF/CSV
        </a>
        <button type="button" onclick="window.print()" class="btn btn-primary">
            🖨️ Print
        </button>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="pagination-wrap">
            {{ $users->links() }}
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.flash');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() { alert.style.display = 'none'; }, 500);
        });
    }, 5000);
});
</script>

@endsection