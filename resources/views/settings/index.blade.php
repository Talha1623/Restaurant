@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg h-screen sticky top-0 overflow-y-auto">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Settings</h2>
                <nav class="space-y-2">
                    <a href="{{ route('settings.index', ['tab' => 'certificate-types']) }}" id="certificate-types-tab" class="flex items-center px-4 py-2 {{ $activeTab === 'certificate-types' ? 'text-gray-700 bg-green-50 border-r-4 rounded-l-md' : 'text-gray-600 hover:bg-gray-50 rounded-md' }} transition nav-link {{ $activeTab === 'certificate-types' ? 'active' : '' }}" style="{{ $activeTab === 'certificate-types' ? 'border-color: #16a34a;' : '' }}">
                        <i class="fas fa-certificate mr-3"></i>
                        Certificate Types
                    </a>
                    <a href="{{ route('settings.index', ['tab' => 'issuing-authorities']) }}" id="issuing-authorities-tab" class="flex items-center px-4 py-2 {{ $activeTab === 'issuing-authorities' ? 'text-gray-700 bg-green-50 border-r-4 rounded-l-md' : 'text-gray-600 hover:bg-gray-50 rounded-md' }} transition nav-link {{ $activeTab === 'issuing-authorities' ? 'active' : '' }}" style="{{ $activeTab === 'issuing-authorities' ? 'border-color: #16a34a;' : '' }}">
                        <i class="fas fa-building mr-3"></i>
                        Issuing Authorities
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-4xl">
                <!-- Header -->
                <div class="mb-8">
                    <h1 id="main-title" class="text-3xl font-bold text-gray-900">
                        @if($activeTab === 'certificate-types')
                            Certificate Types
                        @elseif($activeTab === 'issuing-authorities')
                            Issuing Authorities
                        @else
                            Certificate Types
                        @endif
                    </h1>
                    <p id="main-description" class="text-gray-600 mt-2">
                        @if($activeTab === 'certificate-types')
                            Manage certificate types for your restaurant certificates
                        @elseif($activeTab === 'issuing-authorities')
                            Manage issuing authorities for your restaurant certificates
                        @else
                            Manage certificate types for your restaurant certificates
                        @endif
                    </p>
                </div>

                <!-- Settings Content -->
                <div class="space-y-8">
                    <!-- Certificate Types Section -->
                    <div id="certificate-types-section" style="display: {{ $activeTab === 'certificate-types' ? 'block' : 'none' }};">
                        <!-- Add New Certificate Type Form -->
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-plus-circle text-green-500"></i>
                            Add New Certificate Type
                        </h3>
                        
                        @if(session('success'))
                            <div id="success-message" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        {{ session('success') }}
                                    </div>
                                    <button onclick="hideSuccessMessage()" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('settings.certificate-types.store') }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Certificate Type Name *</label>
                                    <input type="text" name="name" required 
                                           placeholder="e.g., Food Safety, Health Permit"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                                    <input type="text" name="description" 
                                           placeholder="Brief description"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                                    <i class="fas fa-plus"></i> Add Type
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Current Certificate Types -->
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-list text-blue-500"></i>
                            Current Certificate Types
                        </h3>

                        <div class="space-y-4">
                            @forelse($certificateTypes as $type)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-800">{{ $type->name }}</h4>
                                        @if($type->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $type->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-3 py-1 text-xs rounded-full {{ $type->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $type->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="openEditModal({{ $type->id }}, '{{ $type->name }}', '{{ $type->description }}')" 
                                                    class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('settings.certificate-types.toggle', $type->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-lg transition">
                                                    <i class="fas fa-toggle-{{ $type->is_active ? 'on' : 'off' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('settings.certificate-types.delete', $type->id) }}" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this certificate type?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-certificate text-4xl mb-2"></i>
                                    <p>No certificate types found. Add your first type above!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    </div>

                    <!-- Issuing Authorities Section -->
                    <div id="issuing-authorities-section" style="display: {{ $activeTab === 'issuing-authorities' ? 'block' : 'none' }};">
                        <!-- Add New Issuing Authority Form -->
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-plus-circle text-green-500"></i>
                                Add New Issuing Authority
                            </h3>
                            
                            @if(session('success'))
                                <div id="success-message" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            {{ session('success') }}
                                        </div>
                                        <button onclick="hideSuccessMessage()" class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('settings.issuing-authorities.store') }}">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Authority Name *</label>
                                        <input type="text" name="name" required 
                                               placeholder="e.g., Health Department, Food Safety Authority"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                                        <input type="text" name="description" 
                                               placeholder="Brief description"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                                        <i class="fas fa-plus"></i> Add Authority
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Current Issuing Authorities -->
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-list text-blue-500"></i>
                                Current Issuing Authorities
                            </h3>

                            <div class="space-y-4">
                                @forelse($issuingAuthorities as $authority)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-800">{{ $authority->name }}</h4>
                                            @if($authority->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $authority->description }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="px-3 py-1 text-xs rounded-full {{ $authority->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $authority->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <div class="flex items-center space-x-2">
                                                <button onclick="openEditAuthorityModal({{ $authority->id }}, '{{ $authority->name }}', '{{ $authority->description }}')" 
                                                        class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" action="{{ route('settings.issuing-authorities.toggle', $authority->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-lg transition">
                                                        <i class="fas fa-toggle-{{ $authority->is_active ? 'on' : 'off' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('settings.issuing-authorities.delete', $authority->id) }}" class="inline" 
                                                      onsubmit="return confirm('Are you sure you want to delete this issuing authority?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fas fa-building text-4xl mb-2"></i>
                                        <p>No issuing authorities found. Add your first authority above!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Certificate Type Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Add New Certificate Type</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="{{ route('settings.certificate-types.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Add Type
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Certificate Type Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Edit Certificate Type</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" id="editName" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="editDescription" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Type
                </button>
            </div>
        </form>
    </div>
</div>



<!-- Edit Issuing Authority Modal -->
<div id="editAuthorityModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Edit Issuing Authority</h3>
            <button onclick="closeEditAuthorityModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editAuthorityForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Authority Name *</label>
                    <input type="text" name="name" id="editAuthorityName" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="editAuthorityDescription" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditAuthorityModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Update Authority
                </button>
            </div>
        </form>
    </div>
</div>



<!-- Edit Cold Drinks Addon Modal -->
<div id="editColdDrinksAddonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Cold Drinks Addon</h3>
        <form id="editColdDrinksAddonForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Addon Name *</label>
                    <input type="text" name="name" id="editColdDrinksAddonName" required 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" accept="image/*" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-400 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                    <div id="currentColdDrinksAddonImage" class="mt-2"></div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditColdDrinksAddonModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Update Addon
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Menu Category Modal -->
<div id="editMenuCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Menu Category</h3>
        <form id="editMenuCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="edit_menu_category_name" class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                    <input type="text" id="edit_menu_category_name" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label for="edit_menu_category_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="edit_menu_category_description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeEditMenuCategoryModal()"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .nav-link.active {
        background-color: #f0fdf4 !important;
        border-right: 4px solid #16a34a !important;
        color: #166534 !important;
    }
    
    .nav-link {
        transition: all 0.3s ease;
    }
    
    .nav-link:hover {
        background-color: #f9fafb;
    }
</style>

<script>
    // Tab switching is now handled by URL navigation

    // Certificate Types Management
    function addCertificateType() {
        const name = document.getElementById('certTypeName').value;
        const description = document.getElementById('certTypeDesc').value;
        
        if (!name.trim()) {
            alert('Please enter a certificate type name');
            return;
        }
        
        const listContainer = document.getElementById('certificateTypesList');
        const newType = document.createElement('div');
        newType.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        newType.innerHTML = `
            <div>
                <span class="font-medium text-gray-800">${name}</span>
                <p class="text-sm text-gray-600">${description || 'No description provided'}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                <button onclick="removeCertificateType(this)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        listContainer.appendChild(newType);
        
        // Clear form
        document.getElementById('certTypeName').value = '';
        document.getElementById('certTypeDesc').value = '';
        
        // Show success message
        showNotification('Certificate type added successfully!', 'success');
    }
    
    function removeCertificateType(button) {
        if (confirm('Are you sure you want to remove this certificate type?')) {
            button.closest('.flex').remove();
            showNotification('Certificate type removed successfully!', 'success');
        }
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Modal functions

    // Certificate Type Modal Functions
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('addModal').classList.remove('flex');
    }

    function openEditModal(id, name, description) {
        document.getElementById('editForm').action = `/settings/certificate-types/${id}`;
        document.getElementById('editName').value = name;
        document.getElementById('editDescription').value = description || '';
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    // Issuing Authority Modal functions (Add modal removed - now using inline form)

    function openEditAuthorityModal(id, name, description) {
        document.getElementById('editAuthorityForm').action = `/settings/issuing-authorities/${id}`;
        document.getElementById('editAuthorityName').value = name;
        document.getElementById('editAuthorityDescription').value = description || '';
        document.getElementById('editAuthorityModal').classList.remove('hidden');
        document.getElementById('editAuthorityModal').classList.add('flex');
    }

    function closeEditAuthorityModal() {
        document.getElementById('editAuthorityModal').classList.add('hidden');
        document.getElementById('editAuthorityModal').classList.remove('flex');
    }

    // Auto-hide success message after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(function() {
                hideSuccessMessage();
            }, 5000); // 5 seconds
        }
    });

    // Hide success message function
    function hideSuccessMessage() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.transition = 'opacity 0.5s ease';
            successMessage.style.opacity = '0';
            setTimeout(function() {
                successMessage.remove();
            }, 500);
        }
    }

    // Section switching function (now handled by URL navigation)
    function showSection(sectionName) {
        // This function is now handled by URL navigation
        // Redirect to the appropriate tab URL
        window.location.href = "{{ route('settings.index') }}?tab=" + sectionName;
    }

    // Menu Category Modal Functions (Add modal removed - now using inline form)

    function openEditMenuCategoryModal(id, name, description) {
        document.getElementById('editMenuCategoryForm').action = `/settings/menu-categories/${id}`;
        document.getElementById('edit_menu_category_name').value = name;
        document.getElementById('edit_menu_category_description').value = description;
        document.getElementById('editMenuCategoryModal').classList.remove('hidden');
        document.getElementById('editMenuCategoryModal').classList.add('flex');
    }

    function closeEditMenuCategoryModal() {
        document.getElementById('editMenuCategoryModal').classList.add('hidden');
        document.getElementById('editMenuCategoryModal').classList.remove('flex');
    }

    // Cold Drinks Addon Modal Functions
    function openEditColdDrinksAddonModal(id, name, image) {
        document.getElementById('editColdDrinksAddonForm').action = `/settings/cold-drinks-addons/${id}`;
        document.getElementById('editColdDrinksAddonName').value = name;
        
        const currentImageDiv = document.getElementById('currentColdDrinksAddonImage');
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
        
        document.getElementById('editColdDrinksAddonModal').classList.remove('hidden');
        document.getElementById('editColdDrinksAddonModal').classList.add('flex');
    }

    function closeEditColdDrinksAddonModal() {
        document.getElementById('editColdDrinksAddonModal').classList.add('hidden');
        document.getElementById('editColdDrinksAddonModal').classList.remove('flex');
    }

    // Toggle and delete functions removed - now using form-based actions
</script>
@endsection
