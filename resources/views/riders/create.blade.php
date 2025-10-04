@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-xl shadow p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 border-b pb-3">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <a href="{{ route('riders.index') }}" class="transition-colors" style="color: #00d03c;">
                ←
            </a>
            Add New Rider
        </h2>
    </div>

    <form action="{{ route('riders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Grid Layout: 1 row = 3 cols --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Name --}}
            <div>
                <label class="text-sm font-medium">Full Name *</label>
                <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg" required>
            </div>

            {{-- Email --}}
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Phone --}}
            <div>
                <label class="text-sm font-medium">Phone *</label>
                <input type="text" name="phone" class="w-full px-3 py-2 border rounded-lg" required>
            </div>

            {{-- Date of Birth --}}
            <div>
                <label class="text-sm font-medium">Date of Birth *</label>
                <input type="date" name="dob" class="w-full px-3 py-2 border rounded-lg" required>
            </div>

            {{-- NI Number --}}
            <div>
                <label class="text-sm font-medium">NI Number</label>
                <input type="text" name="ni_number" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- City --}}
            <div>
                <label class="text-sm font-medium">City</label>
                <input type="text" name="city" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- House Number --}}
            <div>
                <label class="text-sm font-medium">House Number</label>
                <input type="text" name="house_number" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Street --}}
            <div>
                <label class="text-sm font-medium">Street</label>
                <input type="text" name="street" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Building --}}
            <div>
                <label class="text-sm font-medium">Building</label>
                <input type="text" name="building" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Postcode --}}
            <div>
                <label class="text-sm font-medium">Postcode</label>
                <input type="text" name="postcode" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Vehicle Type --}}
            <div>
                <label class="text-sm font-medium">Vehicle Type *</label>
                <select name="vehicle_type" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="">-- Select --</option>
                    @foreach($vehicleTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Vehicle Number --}}
            <div>
                <label class="text-sm font-medium">Vehicle Registration Number</label>
                <input type="text" name="vehicle_number" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- License Number --}}
            <div>
                <label class="text-sm font-medium">Driving License Number</label>
                <input type="text" name="license_number" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- License Front Image --}}
            <div>
                <label class="text-sm font-medium">License Front Image</label>
                <input type="file" name="license_front_image" accept="image/*" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- License Back Image --}}
            <div>
                <label class="text-sm font-medium">License Back Image</label>
                <input type="file" name="license_back_image" accept="image/*" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Insurance Upload --}}
            <div>
                <label class="text-sm font-medium">Insurance Document</label>
                <input type="file" name="insurance_doc" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- MOT Upload --}}
            <div>
                <label class="text-sm font-medium">MOT Certificate</label>
                <input type="file" name="mot_doc" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Document License --}}
            <div>
                <label class="text-sm font-medium">Document License</label>
                <input type="file" name="right_to_work_doc" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Status --}}
            <div>
                <label class="text-sm font-medium">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            {{-- Joining Date --}}
            <div>
                <label class="text-sm font-medium">Joining Date</label>
                <input type="date" name="joining_date" class="w-full px-3 py-2 border rounded-lg">
            </div>


            {{-- Bank Details --}}
            <div>
                <label class="text-sm font-medium">Bank Sort Code</label>
                <input type="text" name="bank_sort_code" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="text-sm font-medium">Bank Account Number</label>
                <input type="text" name="bank_account_number" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Profile Photo --}}
            <div>
                <label class="text-sm font-medium">Profile Photo</label>
                <input type="file" name="photo" class="w-full px-3 py-2 border rounded-lg">
            </div>

            {{-- Features / Notes (full width) --}}
            <div class="md:col-span-3">
                <label class="text-sm font-medium">Features / Notes</label>
                <textarea name="features" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('riders.index') }}" class="px-4 py-2 border rounded-lg">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
                Save Rider
            </button>
        </div>
    </form>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const postcodeInput = document.getElementById('postcode');
    const locationDisplay = document.getElementById('location-display');
    const addressSuggestions = document.getElementById('address-suggestions');
    
    // Postcode to location mapping
    const postcodeMap = {
        // Specific postcodes
        '90077': 'Westminster, London',
        'SW1A 1AA': 'Westminster, London',
        'E1 6AN': 'Whitechapel, London',
        'N1 9GU': 'Islington, London',
        'SE1 9RT': 'Southwark, London',
        'W1K 6JP': 'Mayfair, London',
        'EC1A 1BB': 'City of London',
        'NW1 6XE': 'Camden, London',
        'SW6 1TT': 'Fulham, London',
        'E14 5AB': 'Canary Wharf, London',
        'N7 8RA': 'Holloway, London',
        'LE1 4SH': 'Leicester, England',
        'M1 1AA': 'Manchester, England',
        'B1 1AA': 'Birmingham, England',
        'LS1 1AA': 'Leeds, England',
        'S1 1AA': 'Sheffield, England',
        'L1 1AA': 'Liverpool, England',
        'NE1 1AA': 'Newcastle, England',
        'B2 1AA': 'Birmingham, England',
        'M2 1AA': 'Manchester, England',
        'LS2 1AA': 'Leeds, England',
        'BD1 1AA': 'Bradford, England',
        'HD1 1AA': 'Huddersfield, England',
        'WF1 1AA': 'Wakefield, England',
        'HU1 1AA': 'Hull, England',
        'YO1 1AA': 'York, England',
        'DL1 1AA': 'Darlington, England',
        'TS1 1AA': 'Middlesbrough, England',
        'SR1 1AA': 'Sunderland, England',
        'DH1 1AA': 'Durham, England',
        'CA1 1AA': 'Carlisle, England',
        'LA1 1AA': 'Lancaster, England',
        'PR1 1AA': 'Preston, England',
        'BB1 1AA': 'Blackburn, England',
        'OL1 1AA': 'Oldham, England',
        'SK1 1AA': 'Stockport, England',
        'WA1 1AA': 'Warrington, England',
        'WN1 1AA': 'Wigan, England',
        'BL1 1AA': 'Bolton, England',
        'FY1 1AA': 'Blackpool, England'
    };
    
    // Postcode patterns for UK areas
    const postcodePatterns = {
        // London areas
        'SW1A': 'Westminster, London',
        'SW1': 'Westminster, London',
        'SW2': 'Brixton, London',
        'SW3': 'Chelsea, London',
        'SW4': 'Clapham, London',
        'SW5': 'Earl\'s Court, London',
        'SW6': 'Fulham, London',
        'SW7': 'South Kensington, London',
        'SW8': 'South Lambeth, London',
        'SW9': 'Stockwell, London',
        'SW10': 'West Brompton, London',
        'SW11': 'Battersea, London',
        'SW12': 'Balham, London',
        'SW13': 'Barnes, London',
        'SW14': 'Mortlake, London',
        'SW15': 'Putney, London',
        'SW16': 'Streatham, London',
        'SW17': 'Tooting, London',
        'SW18': 'Wandsworth, London',
        'SW19': 'Wimbledon, London',
        'SW20': 'Wimbledon, London',
        // Major UK cities
        'LE1': 'Leicester, England',
        'M1': 'Manchester, England',
        'B1': 'Birmingham, England',
        'LS1': 'Leeds, England',
        'S1': 'Sheffield, England',
        'L1': 'Liverpool, England',
        'NE1': 'Newcastle, England',
        'BD1': 'Bradford, England',
        'HD1': 'Huddersfield, England',
        'WF1': 'Wakefield, England',
        'HU1': 'Hull, England',
        'YO1': 'York, England',
        'DL1': 'Darlington, England',
        'TS1': 'Middlesbrough, England',
        'SR1': 'Sunderland, England',
        'DH1': 'Durham, England',
        'CA1': 'Carlisle, England',
        'LA1': 'Lancaster, England',
        'PR1': 'Preston, England',
        'BB1': 'Blackburn, England',
        'OL1': 'Oldham, England',
        'SK1': 'Stockport, England',
        'WA1': 'Warrington, England',
        'WN1': 'Wigan, England',
        'BL1': 'Bolton, England',
        'FY1': 'Blackpool, England'
    };

    // Address suggestions for postcodes
    const addressSuggestionsData = {
        '90077': [
            '10 Downing Street, Westminster, London',
            'Buckingham Palace, Westminster, London',
            'Big Ben, Westminster, London',
            'Westminster Abbey, Westminster, London',
            'Trafalgar Square, Westminster, London',
            'Piccadilly Circus, Westminster, London',
            'Covent Garden, Westminster, London',
            'Leicester Square, Westminster, London'
        ],
        'SW1A': [
            '10 Downing Street, Westminster, London',
            'Buckingham Palace, Westminster, London',
            'Big Ben, Westminster, London',
            'Westminster Abbey, Westminster, London',
            'Trafalgar Square, Westminster, London',
            'Piccadilly Circus, Westminster, London',
            'Covent Garden, Westminster, London',
            'Leicester Square, Westminster, London'
        ],
        'LE1': [
            'Leicester City Centre, Leicester',
            'Leicester University, Leicester',
            'Leicester Market, Leicester',
            'Leicester Cathedral, Leicester',
            'New Walk, Leicester',
            'Highcross Shopping Centre, Leicester',
            'Leicester Railway Station, Leicester',
            'Leicester Royal Infirmary, Leicester'
        ],
        'M1': [
            'Manchester City Centre, Manchester',
            'Manchester University, Manchester',
            'Manchester Piccadilly Station, Manchester',
            'Manchester Arndale, Manchester',
            'Northern Quarter, Manchester',
            'Spinningfields, Manchester',
            'Manchester Cathedral, Manchester',
            'Albert Square, Manchester'
        ],
        'B1': [
            'Birmingham City Centre, Birmingham',
            'Birmingham New Street Station, Birmingham',
            'Bullring Shopping Centre, Birmingham',
            'Birmingham University, Birmingham',
            'Birmingham Cathedral, Birmingham',
            'Broad Street, Birmingham',
            'Jewellery Quarter, Birmingham',
            'Digbeth, Birmingham'
        ]
    };

    // Postcode functionality with address suggestions
    postcodeInput.addEventListener('input', function() {
        const postcode = this.value.trim().toUpperCase();
        
        // Hide address suggestions initially
        addressSuggestions.classList.add('hidden');
        
        if (postcodeMap[postcode]) {
            // Exact match found
            locationDisplay.textContent = `📍 ${postcodeMap[postcode]}`;
            locationDisplay.className = 'text-sm text-green-600 mt-1';
            
            // Show address suggestions
            showAddressSuggestions(postcode);
        } else if (postcode.length > 0) {
            // Try pattern matching
            let found = false;
            for (const pattern in postcodePatterns) {
                if (postcode.startsWith(pattern)) {
                    locationDisplay.textContent = `📍 ${postcodePatterns[pattern]}`;
                    locationDisplay.className = 'text-sm text-green-600 mt-1';
                    found = true;
                    
                    // Show address suggestions for pattern
                    showAddressSuggestions(pattern);
                    break;
                }
            }
            
            if (!found) {
                locationDisplay.textContent = '📍 Location not found';
                locationDisplay.className = 'text-sm text-orange-600 mt-1';
            }
        } else {
            locationDisplay.textContent = '';
        }
    });
    
    // Function to show address suggestions
    function showAddressSuggestions(postcode) {
        let suggestions = [];
        
        // Check for exact match first
        if (addressSuggestionsData[postcode]) {
            suggestions = addressSuggestionsData[postcode];
        } else {
            // Check for pattern match
            for (const pattern in addressSuggestionsData) {
                if (postcode.startsWith(pattern)) {
                    suggestions = addressSuggestionsData[pattern];
                    break;
                }
            }
        }
        
        if (suggestions.length > 0) {
            addressSuggestions.innerHTML = suggestions.map(address => 
                `<div class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0" data-address="${address}">
                    <span class="text-gray-800">${address}</span>
                </div>`
            ).join('');
            addressSuggestions.classList.remove('hidden');
        }
    }
    
    // Handle address suggestion clicks
    addressSuggestions.addEventListener('click', function(e) {
        if (e.target.closest('[data-address]')) {
            const selectedAddress = e.target.closest('[data-address]').dataset.address;
            // You can populate an address field here if needed
            console.log('Selected address:', selectedAddress);
            addressSuggestions.classList.add('hidden');
        }
    });
    
    // Auto-format UK postcodes
    postcodeInput.addEventListener('blur', function() {
        let postcode = this.value.trim().toUpperCase();
        
        // Basic UK postcode formatting
        if (postcode.length >= 5) {
            // Format as AA9A 9AA or A9A 9AA or A9 9AA or AA9 9AA
            if (postcode.length === 5) {
                // A9 9AA format
                postcode = postcode.substring(0, 2) + ' ' + postcode.substring(2);
            } else if (postcode.length === 6) {
                // AA9 9AA format
                postcode = postcode.substring(0, 3) + ' ' + postcode.substring(3);
            } else if (postcode.length === 7) {
                // AA9A 9AA format
                postcode = postcode.substring(0, 4) + ' ' + postcode.substring(4);
            }
            this.value = postcode;
        }
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!postcodeInput.contains(e.target) && !addressSuggestions.contains(e.target)) {
            addressSuggestions.classList.add('hidden');
        }
    });

    // Dynamic Fields Functionality
    let fieldCounter = 0;
    
    // Wait for DOM to be fully loaded
    setTimeout(function() {
        const addFieldBtn = document.getElementById('addFieldBtn');
        const dynamicFields = document.getElementById('dynamicFields');
        
        console.log('Add Field Button:', addFieldBtn);
        console.log('Dynamic Fields Container:', dynamicFields);
        
        if (addFieldBtn && dynamicFields) {
            addFieldBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Add Field button clicked!');
                fieldCounter++;
                addDynamicField(fieldCounter);
            });
        } else {
            console.error('Required elements not found!');
        }
    }, 100);

    function addDynamicField(counter) {
        console.log('Adding dynamic field with counter:', counter);
        const dynamicFields = document.getElementById('dynamicFields');
        
        if (!dynamicFields) {
            console.error('Dynamic fields container not found!');
            return;
        }
        
        const fieldDiv = document.createElement('div');
        fieldDiv.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 items-end border border-gray-200 rounded-lg p-4 bg-gray-50';
        fieldDiv.innerHTML = `
            <div>
                <label class="text-sm font-medium">Field Name</label>
                <input type="text" name="dynamic_fields[${counter}][name]" class="w-full px-3 py-2 border rounded-lg" placeholder="Enter field name" required>
            </div>
            <div>
                <label class="text-sm font-medium">Field Value</label>
                <input type="text" name="dynamic_fields[${counter}][value]" class="w-full px-3 py-2 border rounded-lg" placeholder="Enter field value">
            </div>
            <div class="flex gap-2">
                <button type="button" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition removeFieldBtn">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `;
        
        dynamicFields.appendChild(fieldDiv);
        console.log('Field added successfully!');
        
        // Add remove functionality
        const removeBtn = fieldDiv.querySelector('.removeFieldBtn');
        removeBtn.addEventListener('click', function() {
            fieldDiv.remove();
            console.log('Field removed!');
        });
    }
});
</script>
@endsection
