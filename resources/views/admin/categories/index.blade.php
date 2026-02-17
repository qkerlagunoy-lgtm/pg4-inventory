@extends('layouts.admin')

@section('title', 'Category Management')

@section('content')

<style>
    :root {
        --cream:    #FAF7F0;
        --sand:     #D8D2C2;
        --sienna:   #B17457;
        --charcoal: #4A4947;
    }

    /* ── page background ── */
    .cat-page {
        min-height: 100vh;
        background: var(--cream);
        padding: 2rem;
        font-family: 'Georgia', serif;
    }

    /* ── page heading ── */
    .cat-heading {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--charcoal);
        letter-spacing: .03em;
        margin-bottom: 1.5rem;
    }

    /* ── flash messages ── */
    .flash {
        padding: .75rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: .875rem;
        font-weight: 600;
    }
    .flash-success {
        background: #f0faf0;
        border: 1px solid #6aab6a;
        color: #2e6b2e;
    }
    .flash-error {
        background: #fff0f0;
        border: 1px solid #d87070;
        color: #8b2020;
    }

    /* ── toolbar card ── */
    .toolbar-card {
        background: #fff;
        border: 1px solid var(--sand);
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
    }

    .btn-group { display: flex; gap: .625rem; flex-wrap: wrap; }

    /* ── buttons ── */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .5rem 1.25rem;
        border-radius: 8px;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: opacity .15s, transform .1s;
        text-decoration: none;
        line-height: 1.4;
    }
    .btn:hover  { opacity: .88; transform: translateY(-1px); }
    .btn:active { transform: translateY(0); }
    .btn:disabled { opacity: .4; cursor: not-allowed; transform: none; }

    .btn-primary   { background: var(--sienna);  color: #fff; }
    .btn-dark      { background: var(--charcoal); color: #fff; }
    .btn-muted     { background: var(--sand);     color: var(--charcoal); }
    .btn-blue      { background: #4a7fb5;          color: #fff; }

    /* ── search bar ── */
    .search-form { display: flex; gap: .5rem; flex-wrap: wrap; }
    .search-input {
        padding: .5rem 1rem;
        border: 1px solid var(--sand);
        border-radius: 8px;
        font-size: .875rem;
        background: var(--cream);
        color: var(--charcoal);
        outline: none;
        transition: border-color .2s;
        min-width: 220px;
    }
    .search-input:focus { border-color: var(--sienna); }

    /* ── table card ── */
    .table-card {
        background: #fff;
        border: 1px solid var(--sand);
        border-radius: 10px;
        overflow: hidden;
    }

    .table-wrap { overflow-x: auto; }

    table { width: 100%; border-collapse: collapse; }

    thead tr {
        background: var(--cream);
        border-bottom: 2px solid var(--sand);
    }
    thead th {
        padding: .9rem 1rem;
        text-align: left;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--charcoal);
    }
    thead th:last-child { text-align: right; }

    tbody tr { border-bottom: 1px solid #f0ece4; transition: background .12s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fdfbf7; }

    tbody td {
        padding: .85rem 1rem;
        font-size: .875rem;
        color: var(--charcoal);
        vertical-align: middle;
    }
    tbody td:last-child { text-align: right; }

    .col-code {
        font-weight: 700;
        letter-spacing: .02em;
        color: var(--sienna);
    }

    /* ── status badges ── */
    .badge {
        display: inline-block;
        padding: .2rem .65rem;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .badge-active   { background: #eef6ee; color: #2e7d32; }
    .badge-inactive { background: #f5f3f0; color: #9a9591; }

    /* ── checkbox ── */
    input[type="checkbox"] {
        accent-color: var(--sienna);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    /* ── empty state ── */
    .empty-row td {
        padding: 3.5rem 1rem;
        text-align: center;
        color: var(--sand);
        font-size: .9rem;
    }

    /* ── pagination wrapper ── */
    .pagination-wrap {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--sand);
    }

    /* ── Tailwind pagination override to match palette ── */
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
</style>

<div class="cat-page">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif

    {{-- Toolbar --}}
    <div class="toolbar-card">

        {{-- Left: action buttons --}}
        <div class="btn-group">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                + New Category
            </a>
            <button id="btnDeleteSelected" onclick="deleteSelection()" class="btn btn-dark" disabled>
                Delete Selected
            </button>
            <button id="btnEditSelected" onclick="editCategory()" class="btn btn-blue" disabled>
                Edit Selected
            </button>
        </div>

        {{-- Right: search --}}
        <form method="GET" action="{{ route('admin.categories.index') }}" class="search-form">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search categories…"
                   class="search-input">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}" class="btn btn-muted">Reset</a>
            @endif
        </form>

    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:44px;">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                        </th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            <input type="checkbox" value="{{ $category->id }}" class="category-checkbox">
                        </td>
                        <td class="col-code">{{ $category->code }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge badge-inactive">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="4">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="pagination-wrap">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    const btnEditSelected   = document.getElementById('btnEditSelected');
    const btnDeleteSelected = document.getElementById('btnDeleteSelected');

    function updateActionButtons() {
        const selected = document.querySelectorAll('.category-checkbox:checked');
        btnDeleteSelected.disabled = selected.length === 0;
        btnEditSelected.disabled   = selected.length !== 1;
    }

    function toggleSelectAll(source) {
        document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = source.checked);
        updateActionButtons();
    }

    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.addEventListener('change', updateActionButtons);
    });

    function deleteSelection() {
        const selected = document.querySelectorAll('.category-checkbox:checked');
        if (selected.length === 0) { alert('Select at least one category'); return; }
        if (!confirm('Delete selected categories?')) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.categories.bulk-delete') }}";

        const csrf = document.createElement('input');
        csrf.type  = 'hidden';
        csrf.name  = '_token';
        csrf.value = "{{ csrf_token() }}";
        form.appendChild(csrf);

        selected.forEach(cb => {
            const input  = document.createElement('input');
            input.type   = 'hidden';
            input.name   = 'category_ids[]';
            input.value  = cb.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    function editCategory() {
        const selected = document.querySelectorAll('.category-checkbox:checked');
        if (selected.length === 0)  { alert('Select one category to edit'); return; }
        if (selected.length > 1)    { alert('Please select only one category to edit at a time'); return; }
        window.location.href = `/admin/categories/${selected[0].value}/edit`;
    }
</script>

@endsection