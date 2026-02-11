@extends('layouts.admin')

@section('title', 'Category Management')

@section('content')

<div class="min-h-screen p-8 bg-gray-50">

    <!-- Header -->
    <div class="mb-6">
        <h2 class="font-bold text-2xl text-gray-900">Category Management</h2>
        <p class="text-sm text-gray-500 mt-1">AFPPGMC Logistics Division</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Actions + Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between gap-4">

            <!-- LEFT ACTIONS -->
            <div class="flex gap-3">
                <a href="{{ route('admin.categories.create') }}"
                   class="px-6 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition font-medium shadow">
                    New Category
                </a>

                <button id="btnDeleteSelected"
                        onclick="deleteSelection()"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition shadow"
                        disabled>
                    Delete Selected
                </button>

                <button id="btnEditSelected"
                        onclick="editCategory()"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow"
                        disabled>
                    Edit Selected
                </button>
            </div>

            <!-- RIGHT SEARCH -->
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex gap-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search categories..."
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">

                <button type="submit"
                        class="px-6 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800">
                    Search
                </button>

                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Reset
                    </a>
                @endif
            </form>

        </div>
    </div>

    <!-- CATEGORY TABLE -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 text-left">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                        </th>
                        <th class="p-4 text-left text-sm font-semibold text-gray-700">Code</th>
                        <th class="p-4 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="p-4 text-right text-sm font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">
                            <input type="checkbox" value="{{ $category->id }}" class="category-checkbox">
                        </td>
                        <td class="p-4 font-semibold text-gray-900">{{ $category->code }}</td>
                        <td class="p-4 text-gray-600">{{ $category->description }}</td>
                        <td class="p-4 text-right">
                            @if($category->is_active)
                                <span class="text-xs font-semibold text-gray-800">ACTIVE</span>
                            @else
                                <span class="text-xs font-semibold text-gray-400">INACTIVE</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-gray-500">
                            No categories found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="p-4 border-t">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

</div>

<script>
// BUTTONS
const btnEditSelected = document.getElementById('btnEditSelected');
const btnDeleteSelected = document.getElementById('btnDeleteSelected');

// FUNCTION TO UPDATE BUTTON STATES
function updateActionButtons() {
    const selected = document.querySelectorAll('.category-checkbox:checked');
    btnDeleteSelected.disabled = selected.length === 0;
    btnEditSelected.disabled = selected.length !== 1; // only enable edit if exactly one is selected
}

// TOGGLE SELECT ALL
function toggleSelectAll(source) {
    document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = source.checked);
    updateActionButtons();
}

// ADD EVENT LISTENERS TO ALL CHECKBOXES
document.querySelectorAll('.category-checkbox').forEach(cb => {
    cb.addEventListener('change', updateActionButtons);
});

// DELETE SELECTED
function deleteSelection() {
    const selected = document.querySelectorAll('.category-checkbox:checked');

    if(selected.length === 0){
        alert('Select at least one category');
        return;
    }

    if(confirm('Delete selected categories?')){
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.categories.bulk-delete') }}";

        let csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = "{{ csrf_token() }}";
        form.appendChild(csrf);

        selected.forEach(cb=>{
            const input = document.createElement('input');
            input.type='hidden';
            input.name='category_ids[]';
            input.value=cb.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
}

// EDIT SELECTED (redirect to edit page)
function editCategory() {
    const selected = document.querySelectorAll('.category-checkbox:checked');

    if(selected.length === 0){
        alert('Select one category to edit');
        return;
    }

    if(selected.length > 1){
        alert('Please select only one category to edit at a time');
        return;
    }

    const categoryId = selected[0].value;
    window.location.href = `/admin/categories/${categoryId}/edit`;
}
</script>

@endsection
