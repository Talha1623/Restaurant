# Menu Management API Documentation

## Base URL
```
/api/restaurants/{restaurant_id}
```

## Authentication
All endpoints require Bearer token authentication:
```
Authorization: Bearer {your_token}
```

---

## 1. Get Form Data
Get categories, addons, and static options for menu creation.

**Endpoint:** `GET /api/restaurants/{restaurant_id}/menu/form-data`

**Response:**
```json
{
    "success": true,
    "data": {
        "restaurant": {
            "id": 1,
            "name": "My Restaurant",
            "legal_name": "My Restaurant Ltd"
        },
        "categories": [
            {
                "id": 1,
                "name": "Appetizers",
                "description": "Starters and appetizers"
            }
        ],
        "addons": [
            {
                "id": 1,
                "name": "Extra Cheese",
                "image": "storage/restaurant-addons/cheese.jpg"
            }
        ],
        "form_options": {
            "currencies": [
                {"value": "GBP", "label": "GBP (£)"},
                {"value": "USD", "label": "USD ($)"}
            ],
            "status_options": [
                {"value": "active", "label": "Active"},
                {"value": "inactive", "label": "Inactive"}
            ],
            "spice_levels": [
                {"value": 0, "label": "No Spice"},
                {"value": 1, "label": "Mild (1⭐)"}
            ]
        }
    }
}
```

---

## 2. Create Menu Item
Create a new menu item.

**Endpoint:** `POST /api/restaurants/{restaurant_id}/menus`

**Request Body:**
```json
{
    "name": "Chicken Biryani",
    "description": "Aromatic basmati rice with tender chicken",
    "ingredients": "Chicken, Rice, Onions, Spices, Yogurt",
    "price": 12.99,
    "vat_price": 2.60,
    "currency": "GBP",
    "category": "Main Course",
    "status": "active",
    "is_available": true,
    "spice_level": 3,
    "preparation_time": 25,
    "calories": 450,
    "tags": "Spicy, Non-Vegetarian, Popular",
    "allergen": "Contains Dairy",
    "dietary_flags": "Halal, Non-Vegetarian",
    "cold_drinks_addons": [1, 2, 3]
}
```

**Required Fields:**
- `name` (string, max:255)
- `price` (numeric, min:0)
- `currency` (string, in:GBP,USD,EUR,PKR)
- `category` (string, max:255)
- `status` (string, in:active,inactive)

**Optional Fields:**
- `description` (string)
- `ingredients` (string)
- `vat_price` (numeric, min:0)
- `is_available` (boolean)
- `spice_level` (integer, 0-5)
- `preparation_time` (integer, 0-300)
- `calories` (integer, 0-5000)
- `tags` (string, comma-separated)
- `allergen` (string, max:255)
- `dietary_flags` (string, comma-separated)
- `cold_drinks_addons` (array of addon IDs)
- `images` (array of image files, max:5, 2MB each)

**Response:**
```json
{
    "success": true,
    "message": "Menu item created successfully",
    "data": {
        "id": 1,
        "name": "Chicken Biryani",
        "description": "Aromatic basmati rice with tender chicken",
        "price": "12.99",
        "currency": "GBP",
        "category": "Main Course",
        "status": "active",
        "is_available": true,
        "spice_level": 3,
        "preparation_time": 25,
        "calories": 450,
        "tags": ["Spicy", "Non-Vegetarian", "Popular"],
        "allergen": "Contains Dairy",
        "dietary_flags": ["Halal", "Non-Vegetarian"],
        "cold_drinks_addons": [1, 2, 3],
        "restaurant_id": 1,
        "created_at": "2024-01-01T10:00:00.000000Z",
        "updated_at": "2024-01-01T10:00:00.000000Z",
        "images": []
    }
}
```

---

## 3. Get All Menu Items
Get paginated list of menu items for a restaurant.

**Endpoint:** `GET /api/restaurants/{restaurant_id}/menus`

**Query Parameters:**
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 10)

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Chicken Biryani",
                "price": "12.99",
                "currency": "GBP",
                "category": "Main Course",
                "status": "active",
                "is_available": true,
                "images": []
            }
        ],
        "total": 1,
        "per_page": 10,
        "last_page": 1
    }
}
```

---

## 4. Get Single Menu Item
Get details of a specific menu item.

**Endpoint:** `GET /api/restaurants/{restaurant_id}/menus/{menu_id}`

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Chicken Biryani",
        "description": "Aromatic basmati rice with tender chicken",
        "ingredients": "Chicken, Rice, Onions, Spices, Yogurt",
        "price": "12.99",
        "vat_price": "2.60",
        "currency": "GBP",
        "category": "Main Course",
        "status": "active",
        "is_available": true,
        "spice_level": 3,
        "preparation_time": 25,
        "calories": 450,
        "tags": ["Spicy", "Non-Vegetarian", "Popular"],
        "allergen": "Contains Dairy",
        "dietary_flags": ["Halal", "Non-Vegetarian"],
        "cold_drinks_addons": [1, 2, 3],
        "restaurant_id": 1,
        "created_at": "2024-01-01T10:00:00.000000Z",
        "updated_at": "2024-01-01T10:00:00.000000Z",
        "images": [
            {
                "id": 1,
                "image_url": "menu-images/image1.jpg",
                "is_primary": true,
                "sort_order": 1
            }
        ],
        "restaurant": {
            "id": 1,
            "business_name": "My Restaurant",
            "legal_name": "My Restaurant Ltd"
        }
    }
}
```

---

## 5. Update Menu Item
Update an existing menu item.

**Endpoint:** `PUT /api/restaurants/{restaurant_id}/menus/{menu_id}`

**Request Body:** (Same as create, all fields optional except validation rules)

**Response:** (Same as get single menu item)

---

## 6. Delete Menu Item
Delete a menu item.

**Endpoint:** `DELETE /api/restaurants/{restaurant_id}/menus/{menu_id}`

**Response:**
```json
{
    "success": true,
    "message": "Menu item deleted successfully"
}
```

---

## 7. Toggle Menu Availability
Toggle the availability of a menu item.

**Endpoint:** `POST /api/restaurants/{restaurant_id}/menus/{menu_id}/toggle-availability`

**Response:**
```json
{
    "success": true,
    "message": "Menu availability updated successfully",
    "data": {
        "is_available": true
    }
}
```

---

## 8. Get Menu Statistics
Get menu statistics for a restaurant.

**Endpoint:** `GET /api/restaurants/{restaurant_id}/menus/stats`

**Response:**
```json
{
    "success": true,
    "data": {
        "total_menus": 25,
        "active_menus": 20,
        "available_menus": 18,
        "categories_count": 8,
        "total_addons": 12
    }
}
```

---

## Error Responses

**Validation Error (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["The name field is required."],
        "price": ["The price must be a number."]
    }
}
```

**Not Found (404):**
```json
{
    "success": false,
    "message": "Menu item not found",
    "error": "No query results for model [App\\Models\\Menu] 123"
}
```

**Server Error (500):**
```json
{
    "success": false,
    "message": "Failed to create menu item",
    "error": "Database connection failed"
}
```

---

## Example Usage

### JavaScript/Frontend Example:
```javascript
// Get form data
const formData = await fetch('/api/restaurants/1/menu/form-data', {
    headers: {
        'Authorization': 'Bearer your_token',
        'Accept': 'application/json'
    }
}).then(res => res.json());

// Create menu item
const newMenu = await fetch('/api/restaurants/1/menus', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer your_token',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        name: 'Chicken Biryani',
        price: 12.99,
        currency: 'GBP',
        category: 'Main Course',
        status: 'active',
        is_available: true,
        spice_level: 3
    })
}).then(res => res.json());
```

### PHP Example:
```php
// Using Laravel HTTP Client
use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'Authorization' => 'Bearer your_token',
    'Accept' => 'application/json'
])->post('/api/restaurants/1/menus', [
    'name' => 'Chicken Biryani',
    'price' => 12.99,
    'currency' => 'GBP',
    'category' => 'Main Course',
    'status' => 'active',
    'is_available' => true,
    'spice_level' => 3
]);

$menuData = $response->json();
```
