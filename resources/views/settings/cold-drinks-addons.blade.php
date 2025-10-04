@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Cold Drinks Addons</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage cold drinks addons for your restaurant menu items</p>
                </div>
                <button onclick="openAddModal()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Add New Addon
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Cold Drinks Addons List -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Cold Drinks Addons</h3>
                
                @if($coldDrinksAddons->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($coldDrinksAddons as $addon)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        @if($addon->image)
                                            <img src="{{ asset('storage/' . $addon->image) }}" 
                                                 alt="{{ $addon->name }}" 
                                                 class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-glass-whiskey text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $addon->name }}</h4>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $addon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $addon->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button onclick="openEditModal({{ $addon->id }}, '{{ $addon->name }}', '{{ $addon->image }}', {{ $addon->is_active ? 'true' : 'false' }})" 
                                                class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50" 
                                                title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        <button onclick="toggleAddon({{ $addon->id }})" 
                                                class="text-yellow-600 hover:text-yellow-800 p-1 rounded hover:bg-yellow-50" 
                                                title="Toggle Status">
                                            <i class="fas fa-toggle-{{ $addon->is_active ? 'on' : 'off' }} text-sm"></i>
                                        </button>
                                        <button onclick="deleteAddon({{ $addon->id }}, '{{ $addon->name }}')" 
                                                class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50" 
                                                title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-glass-whiskey text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Cold Drinks Addons</h3>
                        <p class="text-gray-500 mb-4">Get started by adding your first cold drink addon</p>
                        <button onclick="openAddModal()" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                            Add First Addon
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Add New Cold Drink Addon</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addForm" action="{{ route('settings.cold-drinks-addons.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" accept="image/*" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <p class="text-xs text-gray-500 mt-1">Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked 
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label class="ml-2 text-sm text-gray-700">Active</label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeAddModal()" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Add Addon
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Edit Cold Drink Addon</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" id="editName" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" accept="image/*" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                    <div id="currentImage" class="mt-2"></div>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" id="editIsActive" 
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label class="ml-2 text-sm text-gray-700">Active</label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Update Addon
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
    document.getElementById('addForm').reset();
}

function openEditModal(id, name, image, isActive) {
    document.getElementById('editForm').action = `/settings/cold-drinks-addons/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editIsActive').checked = isActive;
    
    const currentImageDiv = document.getElementById('currentImage');
    if (image) {
        currentImageDiv.innerHTML = `
            <div class="flex items-center gap-2">
                <img src="/storage/${image}" alt="${name}" class="w-8 h-8 rounded object-cover">
                <span class="text-sm text-gray-600">Current image</span>
            </div>
        `;
    } else {
        currentImageDiv.innerHTML = '<span class="text-sm text-gray-500">No current image</span>';
    }
    
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

function toggleAddon(id) {
    if (confirm('Are you sure you want to toggle this addon status?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/settings/cold-drinks-addons/${id}/toggle`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteAddon(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/settings/cold-drinks-addons/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-hide success messages
setTimeout(function() {
    const successMessage = document.querySelector('.bg-green-100');
    if (successMessage) {
        successMessage.style.transition = 'opacity 0.5s';
        successMessage.style.opacity = '0';
        setTimeout(() => successMessage.remove(), 500);
    }
}, 5000);
</script>
@endsection
