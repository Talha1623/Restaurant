@extends('layouts.app')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center gap-3 border-b pb-2">
        <a href="{{ route('customers.index') }}" 
           class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Add Customer</h1>
    </div>

    <form action="{{ route('customers.store') }}" method="POST" class="bg-white p-4 shadow rounded-lg space-y-4">
        @csrf

        {{-- Basic Info --}}
        <div class="space-y-3">
            <h2 class="text-md font-semibold text-gray-700 border-b pb-1">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-gray-600 text-sm mb-1">First Name</label>
                    <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" class="border rounded px-2 py-1.5 w-full text-sm" required>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Last Name</label>
                    <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" class="border rounded px-2 py-1.5 w-full text-sm" required>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Email</label>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="border rounded px-2 py-1.5 w-full text-sm" required>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Phone</label>
                    <input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}" class="border rounded px-2 py-1.5 w-full text-sm">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Date of Birth</label>
                    <input type="date" name="dob" placeholder="Date of Birth" value="{{ old('dob') }}" class="border rounded px-2 py-1.5 w-full text-sm">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Gender</label>
                    <select name="gender" class="border rounded px-2 py-1.5 w-full text-sm">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                        <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                        <option value="other" {{ old('gender')=='other'?'selected':'' }}>Other</option>
                        <option value="prefer_not_to_say" {{ old('gender')=='prefer_not_to_say'?'selected':'' }}>Prefer not to say</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="space-y-3">
            <h2 class="text-md font-semibold text-gray-700 border-b pb-1">Address</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Address Line 1</label>
                    <input type="text" name="address_line1" placeholder="Address Line 1" value="{{ old('address_line1') }}" class="border rounded px-2 py-1.5 w-full text-sm">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">City *</label>
                    <input type="text" name="city" placeholder="City" value="{{ old('city') }}" class="border rounded px-2 py-1.5 w-full text-sm" required>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Postcode</label>
                    <input type="text" name="postcode" placeholder="Postcode" value="{{ old('postcode') }}" class="border rounded px-2 py-1.5 w-full text-sm">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Country</label>
                    <input type="text" name="country" placeholder="Country" value="{{ old('country') }}" class="border rounded px-2 py-1.5 w-full text-sm">
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="space-y-3">
            <h2 class="text-md font-semibold text-gray-700 border-b pb-1">Account Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Username</label>
                    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" class="border rounded px-2 py-1.5 w-full text-sm">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Password</label>
                    <input type="password" name="password" placeholder="Password" class="border rounded px-2 py-1.5 w-full text-sm">
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Status</label>
                    <select name="status" class="border rounded px-2 py-1.5 w-full text-sm">
                        <option value="active" {{ old('status')=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
                        <option value="blocked" {{ old('status')=='blocked'?'selected':'' }}>Blocked</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm mb-1">Registration Date</label>
                    <input type="date" name="registration_date" value="{{ old('registration_date') }}" class="border rounded px-2 py-1.5 w-full text-sm">
                </div>
            </div>
        </div>

        {{-- Continue same pattern for other sections --}}

        <div class="flex gap-2 mt-3">
            <button type="submit" class="bg-green-500 text-white px-3 py-1.5 rounded text-sm hover:bg-green-600">
                <i class="fas fa-save mr-1"></i> Save
            </button>
            <a href="{{ route('customers.index') }}" class="bg-gray-500 text-white px-3 py-1.5 rounded text-sm hover:bg-gray-600">
                <i class="fas fa-times mr-1"></i> Cancel
            </a>
        </div>

    </form>
</div>
@endsection
