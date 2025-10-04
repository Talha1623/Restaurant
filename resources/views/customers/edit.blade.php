@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">✏️ Edit Customer</h1>

    <form action="{{ route('customers.update',$customer->id) }}" method="POST" class="bg-white p-6 shadow-xl rounded-xl space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-1">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-600 mb-1">First Name</label>
                    <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name',$customer->first_name) }}" class="border rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Last Name</label>
                    <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name',$customer->last_name) }}" class="border rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email',$customer->email) }}" class="border rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Phone</label>
                    <input type="text" name="phone" placeholder="Phone" value="{{ old('phone',$customer->phone) }}" class="border rounded px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Gender</label>
                    <select name="gender" class="border rounded px-3 py-2 w-full">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender',$customer->gender)=='male'?'selected':'' }}>Male</option>
                        <option value="female" {{ old('gender',$customer->gender)=='female'?'selected':'' }}>Female</option>
                        <option value="other" {{ old('gender',$customer->gender)=='other'?'selected':'' }}>Other</option>
                        <option value="prefer_not_to_say" {{ old('gender',$customer->gender)=='prefer_not_to_say'?'selected':'' }}>Prefer not to say</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Date of Birth</label>
                    <input type="date" name="dob" placeholder="Date of Birth" value="{{ old('dob',$customer->dob) }}" class="border rounded px-3 py-2 w-full">
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-1">Address</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-600 mb-1">Address Line 1</label>
                    <input type="text" name="address_line1" placeholder="Address Line 1" value="{{ old('address_line1',$customer->address_line1) }}" class="border rounded px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">City *</label>
                    <input type="text" name="city" placeholder="City" value="{{ old('city',$customer->city) }}" class="border rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Postcode</label>
                    <input type="text" name="postcode" placeholder="Postcode" value="{{ old('postcode',$customer->postcode) }}" class="border rounded px-3 py-2 w-full">
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-1">Account Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-600 mb-1">Username</label>
                    <input type="text" name="username" placeholder="Username" value="{{ old('username',$customer->username) }}" class="border rounded px-3 py-2 w-full">
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Status</label>
                    <select name="status" class="border rounded px-3 py-2 w-full">
                        <option value="active" {{ old('status',$customer->status)=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status',$customer->status)=='inactive'?'selected':'' }}>Inactive</option>
                        <option value="blocked" {{ old('status',$customer->status)=='blocked'?'selected':'' }}>Blocked</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 mb-1">Registration Date</label>
                    <input type="date" name="registration_date" value="{{ old('registration_date',$customer->registration_date) }}" class="border rounded px-3 py-2 w-full">
                </div>
            </div>
        </div>
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('customers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
