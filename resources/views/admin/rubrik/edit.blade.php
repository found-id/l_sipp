@extends('layouts.app')

@section('title', 'Edit Rubrik Penilaian')

@push('styles')
<style>
.sortable-ghost {
    opacity: 0.4;
    background: #f3f4f6;
}
.sortable-chosen {
    background: #dbeafe;
}
.sortable-drag {
    background: #3b82f6;
    color: white;
    transform: rotate(5deg);
}
</style>
@endpush


@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Rubrik Penilaian</h1>
                <p class="text-gray-600 mt-2">{{ $form->name }}</p>
            </div>
            <a href="{{ route('admin.rubrik.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Info -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Informasi Rubrik</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Rubrik</label>
                    <input type="text" value="{{ $form->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $form->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        <i class="fas {{ $form->is_active ? 'fa-check' : 'fa-times' }} mr-1"></i>
                        {{ $form->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md" rows="3" readonly>{{ $form->description }}</textarea>
            </div>
        </div>
    </div>

    <!-- Form Items -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Item Penilaian</h2>
                <button onclick="openAddItemModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Tambah Item
                </button>
            </div>
        </div>
        <div class="p-6">
                    @if(isset($items) && $items->count() > 0)
                        <div id="sortable-items" class="space-y-4">
                            @foreach($items as $item)
                                <div class="border border-gray-200 rounded-lg p-4 draggable-item" data-id="{{ $item->id }}" data-sort="{{ $item->sort_order }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center space-x-4 flex-1">
                                            <div class="flex flex-col space-y-1">
                                                <button onclick="moveItemUp({{ $item->id }})" class="text-gray-400 hover:text-gray-600 text-xs" title="Pindah ke atas">
                                                    <i class="fas fa-chevron-up"></i>
                                                </button>
                                                <button onclick="moveItemDown({{ $item->id }})" class="text-gray-400 hover:text-gray-600 text-xs" title="Pindah ke bawah">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-4">
                                                    <span class="text-sm text-gray-500">#{{ $item->sort_order }}</span>
                                                    <h3 class="text-md font-medium text-gray-900">{{ $item->label }}</h3>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst($item->type) }}
                                                    </span>
                                                    @if($item->required)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-asterisk mr-1"></i>Wajib
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <span class="text-sm text-gray-600">Bobot: {{ $item->weight }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="editItem({{ $item->id }}, '{{ $item->label }}', '{{ $item->type }}', {{ $item->weight }}, {{ $item->sort_order }}, {{ $item->required ? 'true' : 'false' }})" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteItem({{ $item->id }})" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Item</h3>
                    <p class="text-gray-600">Silakan tambah item penilaian untuk rubrik ini.</p>
                </div>
            @endif
        </div>
        
        <!-- Weight Status -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Status Bobot Total</h4>
                    <p class="text-sm text-gray-600">Total bobot semua item penilaian</p>
                </div>
                <div class="text-right">
                    <div id="totalWeight" class="text-2xl font-bold text-gray-900">{{ isset($items) ? $items->sum('weight') : 0 }}%</div>
                    <div id="weightStatus" class="text-sm font-medium">
                        @php
                            $totalWeight = isset($items) ? $items->sum('weight') : 0;
                        @endphp
                        @if($totalWeight == 100)
                            <span class="text-green-600">✓ Bobot sudah tepat 100%</span>
                        @elseif($totalWeight > 100)
                            <span class="text-red-600">⚠ Bobot melebihi 100% ({{ $totalWeight - 100 }}% kelebihan)</span>
                        @else
                            <span class="text-yellow-600">⚠ Bobot kurang dari 100% ({{ 100 - $totalWeight }}% kurang)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div id="addItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Item Penilaian</h3>
            </div>
            <form action="{{ route('admin.rubrik.add-item', $form->id) }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Label Item</label>
                        <input type="text" name="label" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="numeric">Numeric (0-100)</option>
                            <option value="boolean">Boolean (Ya/Tidak)</option>
                            <option value="text">Text</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bobot (%) <span class="text-gray-500">(Opsional)</span></label>
                        <input type="number" name="weight" min="1" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <input type="number" name="sort_order" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="required" id="required" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="required" class="ml-2 text-sm text-gray-700">Item wajib diisi</label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeAddItemModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Tambah Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Edit Item Penilaian</h3>
            </div>
            <form id="editItemForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Label Item</label>
                        <input type="text" name="label" id="editLabel" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                        <select name="type" id="editType" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="numeric">Numeric (0-100)</option>
                            <option value="boolean">Boolean (Ya/Tidak)</option>
                            <option value="text">Text</option>
                        </select>
                    </div>
                    <div id="weightField">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bobot (%)</label>
                        <input type="number" name="weight" id="editWeight" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="required" id="editRequired" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="editRequired" class="ml-2 text-sm text-gray-700">Item wajib diisi</label>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditItemModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddItemModal() {
    document.getElementById('addItemModal').classList.remove('hidden');
}

function closeAddItemModal() {
    document.getElementById('addItemModal').classList.add('hidden');
}

function editItem(id, label, type, weight, sortOrder, required) {
    document.getElementById('editItemForm').action = `/admin/rubrik/item/${id}`;
    document.getElementById('editLabel').value = label;
    document.getElementById('editType').value = type;
    document.getElementById('editWeight').value = weight;
    document.getElementById('editRequired').checked = required === 'true';
    
    // Show/hide weight field based on type
    const weightField = document.getElementById('weightField');
    if (type === 'text') {
        weightField.style.display = 'none';
    } else {
        weightField.style.display = 'block';
    }
    
    document.getElementById('editItemModal').classList.remove('hidden');
    
    // Add event listener for type change
    const typeSelect = document.getElementById('editType');
    typeSelect.onchange = function() {
        const weightField = document.getElementById('weightField');
        if (this.value === 'text') {
            weightField.style.display = 'none';
        } else {
            weightField.style.display = 'block';
        }
    };
}

function closeEditItemModal() {
    document.getElementById('editItemModal').classList.add('hidden');
}

function deleteItem(itemId) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        // Use fetch with proper headers
        fetch(`{{ url('admin/rubrik/item') }}/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Network response was not ok');
            }
        })
        .then(data => {
            if (data.success) {
                // Remove the item from DOM
                const itemElement = document.querySelector(`[data-id="${itemId}"]`);
                if (itemElement) {
                    itemElement.remove();
                }
                // Reload page to update everything
                location.reload();
            } else {
                alert('Gagal menghapus item: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus item. Silakan coba lagi.');
        });
    }
}

// Move item up
function moveItemUp(itemId) {
    const currentItem = document.querySelector(`[data-id="${itemId}"]`);
    const prevItem = currentItem.previousElementSibling;
    
    if (prevItem && prevItem.classList.contains('draggable-item')) {
        currentItem.parentNode.insertBefore(currentItem, prevItem);
        updateSortOrder();
    }
}

// Move item down
function moveItemDown(itemId) {
    const currentItem = document.querySelector(`[data-id="${itemId}"]`);
    const nextItem = currentItem.nextElementSibling;
    
    if (nextItem && nextItem.classList.contains('draggable-item')) {
        currentItem.parentNode.insertBefore(nextItem, currentItem);
        updateSortOrder();
    }
}

function updateSortOrder() {
    const items = document.querySelectorAll('.draggable-item');
    const updates = [];
    
    items.forEach((item, index) => {
        const itemId = item.dataset.id;
        const newOrder = index + 1;
        
        // Update the display number
        const orderSpan = item.querySelector('span.text-gray-500');
        if (orderSpan) {
            orderSpan.textContent = `#${newOrder}`;
        }
        
        updates.push({
            id: itemId,
            sort_order: newOrder
        });
    });
    
    // Send update to server
    fetch('{{ route("admin.rubrik.update-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ updates: updates })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Sort order updated successfully');
        } else {
            console.error('Failed to update sort order');
        }
    })
    .catch(error => {
        console.error('Error updating sort order:', error);
    });
}
</script>
@endsection
