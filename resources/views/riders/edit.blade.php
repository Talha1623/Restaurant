@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        ✏️ Edit Rider
    </h2>

    <form action="{{ route('riders.update', $rider) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name', $rider->name) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email', $rider->email) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $rider->phone) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div>
                <label class="text-sm font-medium">CNIC</label>
                <input type="text" name="cnic" value="{{ old('cnic', $rider->cnic) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">City</label>
                <input type="text" name="city" value="{{ old('city', $rider->city) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">House Number</label>
                <input type="text" name="house_number" value="{{ old('house_number', $rider->house_number) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">Street</label>
                <input type="text" name="street" value="{{ old('street', $rider->street) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">Building</label>
                <input type="text" name="building" value="{{ old('building', $rider->building) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">Vehicle Type</label>
                <select name="vehicle_type" class="w-full px-3 py-2 border rounded-lg" required>
                    @foreach($vehicleTypes as $type)
                        <option value="{{ $type }}" {{ (old('vehicle_type', $rider->vehicle_type) == $type) ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium">Vehicle Number</label>
                <input type="text" name="vehicle_number" value="{{ old('vehicle_number', $rider->vehicle_number) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">License Number</label>
                <input type="text" name="license_number" value="{{ old('license_number', $rider->license_number) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">License Expiry</label>
                <input type="date" name="license_expiry" value="{{ old('license_expiry', $rider->license_expiry) }}" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg">
                    <option value="active" {{ old('status', $rider->status)=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ old('status', $rider->status)=='inactive'?'selected':'' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Features</label>
            <textarea name="features" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ old('features', $rider->features) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium">Photo</label>
                <input type="file" name="photo" class="w-full px-3 py-2 border rounded-lg">
                @if($rider->photo)
                    <p class="text-xs mt-1">Current: <a href="{{ asset('storage/'.$rider->photo) }}" target="_blank" class="text-blue-600 underline">View</a></p>
                @endif
            </div>
            <div>
                <label class="text-sm font-medium">Insurance Document</label>
                <input type="file" name="insurance_doc" class="w-full px-3 py-2 border rounded-lg">
                @if($rider->insurance_doc)
                    <p class="text-xs mt-1">Current: <a href="{{ asset('storage/'.$rider->insurance_doc) }}" target="_blank" class="text-blue-600 underline">View</a></p>
                @endif
            </div>
            <div>
                <label class="text-sm font-medium">MOT Document</label>
                <input type="file" name="mot_doc" class="w-full px-3 py-2 border rounded-lg">
                @if($rider->mot_doc)
                    <p class="text-xs mt-1">Current: <a href="{{ asset('storage/'.$rider->mot_doc) }}" target="_blank" class="text-blue-600 underline">View</a></p>
                @endif
            </div>
            <div>
                <label class="text-sm font-medium">Document License</label>
                <input type="file" name="right_to_work_doc" class="w-full px-3 py-2 border rounded-lg">
                @if($rider->right_to_work_doc)
                    <p class="text-xs mt-1">Current: <a href="{{ asset('storage/'.$rider->right_to_work_doc) }}" target="_blank" class="text-blue-600 underline">View</a></p>
                @endif
            </div>
        </div>

        <div class="flex justify-end">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">
                Update Rider
            </button>
        </div>
    </form>
</div>
@endsection
