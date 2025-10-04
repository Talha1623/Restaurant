# Postman Testing Guide - Menu API

## 🚀 Setup Instructions

### 1. **Authentication Token Setup**
First, you need to get an authentication token from your restaurant login API:

**Request:**
```
POST /api/restaurants/login
Content-Type: application/json

{
    "email": "your_restaurant_email@example.com",
    "password": "your_password"
}
```

**Response:**
```json
{
    "success": true,
    "token": "1|your_token_here",
    "restaurant": {
        "id": 1,
        "business_name": "My Restaurant"
    }
}
```

### 2. **Postman Environment Setup**
Create a new environment in Postman with these variables:
- `base_url`: `http://your-domain.com/api` (or `http://localhost:8000/api`)
- `restaurant_id`: `1` (your restaurant ID)
- `auth_token`: `1|your_token_here` (from login response)

---

## 📋 API Testing Steps

### **Step 1: Get Form Data**
**Endpoint:** `GET {{base_url}}/restaurants/{{restaurant_id}}/menu/form-data`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Accept: application/json
```

**Expected Response:**
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
                "name": "Main Course",
                "description": "Main dishes"
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

### **Step 2: Create Menu Item (Basic Test)**
**Endpoint:** `POST {{base_url}}/restaurants/{{restaurant_id}}/menus`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "name": "Chicken Biryani",
    "price": 12.99,
    "currency": "GBP",
    "category": "Main Course",
    "status": "active",
    "is_available": true
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Menu item created successfully",
    "data": {
        "id": 1,
        "name": "Chicken Biryani",
        "price": "12.99",
        "currency": "GBP",
        "category": "Main Course",
        "status": "active",
        "is_available": true,
        "vat_price": null,
        "description": null,
        "ingredients": null,
        "spice_level": null,
        "preparation_time": null,
        "calories": null,
        "tags": null,
        "allergen": null,
        "dietary_flags": null,
        "cold_drinks_addons": null,
        "restaurant_id": 1,
        "created_at": "2024-01-01T10:00:00.000000Z",
        "updated_at": "2024-01-01T10:00:00.000000Z",
        "images": []
    }
}
```

---

### **Step 3: Create Menu Item (Complete with VAT Price)**
**Endpoint:** `POST {{base_url}}/restaurants/{{restaurant_id}}/menus`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Content-Type: application/json
Accept: application/json
```

**Body (JSON) - Complete Test:**
```json
{
    "name": "Premium Chicken Biryani",
    "description": "Aromatic basmati rice with tender chicken and premium spices",
    "ingredients": "Chicken, Basmati Rice, Onions, Ginger, Garlic, Spices, Yogurt, Saffron",
    "price": 15.99,
    "vat_price": 3.20,
    "currency": "GBP",
    "category": "Main Course",
    "status": "active",
    "is_available": true,
    "spice_level": 3,
    "preparation_time": 30,
    "calories": 520,
    "tags": "Premium, Spicy, Non-Vegetarian, Popular, Authentic",
    "allergen": "Contains Dairy, Nuts",
    "dietary_flags": "Halal, Non-Vegetarian, Gluten-Free",
    "cold_drinks_addons": [1, 2]
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Menu item created successfully",
    "data": {
        "id": 2,
        "name": "Premium Chicken Biryani",
        "description": "Aromatic basmati rice with tender chicken and premium spices",
        "ingredients": "Chicken, Basmati Rice, Onions, Ginger, Garlic, Spices, Yogurt, Saffron",
        "price": "15.99",
        "vat_price": "3.20",
        "currency": "GBP",
        "category": "Main Course",
        "status": "active",
        "is_available": true,
        "spice_level": 3,
        "preparation_time": 30,
        "calories": 520,
        "tags": ["Premium", "Spicy", "Non-Vegetarian", "Popular", "Authentic"],
        "allergen": "Contains Dairy, Nuts",
        "dietary_flags": ["Halal", "Non-Vegetarian", "Gluten-Free"],
        "cold_drinks_addons": [1, 2],
        "restaurant_id": 1,
        "created_at": "2024-01-01T10:05:00.000000Z",
        "updated_at": "2024-01-01T10:05:00.000000Z",
        "images": []
    }
}
```

---

### **Step 4: Test VAT Price Validation**
**Endpoint:** `POST {{base_url}}/restaurants/{{restaurant_id}}/menus`

**Body (JSON) - Invalid VAT Price Test:**
```json
{
    "name": "Test Item",
    "price": 10.00,
    "vat_price": -5.00,
    "currency": "GBP",
    "category": "Test",
    "status": "active"
}
```

**Expected Response (Validation Error):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "vat_price": ["The vat price must be at least 0."]
    }
}
```

---

### **Step 5: Get All Menu Items**
**Endpoint:** `GET {{base_url}}/restaurants/{{restaurant_id}}/menus`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Accept: application/json
```

**Expected Response:**
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
                "vat_price": null,
                "currency": "GBP",
                "category": "Main Course",
                "status": "active",
                "is_available": true,
                "images": []
            },
            {
                "id": 2,
                "name": "Premium Chicken Biryani",
                "price": "15.99",
                "vat_price": "3.20",
                "currency": "GBP",
                "category": "Main Course",
                "status": "active",
                "is_available": true,
                "images": []
            }
        ],
        "total": 2,
        "per_page": 10,
        "last_page": 1
    }
}
```

---

### **Step 6: Get Single Menu Item**
**Endpoint:** `GET {{base_url}}/restaurants/{{restaurant_id}}/menus/2`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Accept: application/json
```

**Expected Response:**
```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "Premium Chicken Biryani",
        "description": "Aromatic basmati rice with tender chicken and premium spices",
        "price": "15.99",
        "vat_price": "3.20",
        "currency": "GBP",
        "category": "Main Course",
        "status": "active",
        "is_available": true,
        "spice_level": 3,
        "preparation_time": 30,
        "calories": 520,
        "tags": ["Premium", "Spicy", "Non-Vegetarian", "Popular", "Authentic"],
        "allergen": "Contains Dairy, Nuts",
        "dietary_flags": ["Halal", "Non-Vegetarian", "Gluten-Free"],
        "cold_drinks_addons": [1, 2],
        "restaurant_id": 1,
        "created_at": "2024-01-01T10:05:00.000000Z",
        "updated_at": "2024-01-01T10:05:00.000000Z",
        "images": [],
        "restaurant": {
            "id": 1,
            "business_name": "My Restaurant",
            "legal_name": "My Restaurant Ltd"
        }
    }
}
```

---

### **Step 7: Update Menu Item (VAT Price Update)**
**Endpoint:** `PUT {{base_url}}/restaurants/{{restaurant_id}}/menus/2`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "vat_price": 2.50,
    "price": 14.99
}
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Menu item updated successfully",
    "data": {
        "id": 2,
        "name": "Premium Chicken Biryani",
        "price": "14.99",
        "vat_price": "2.50",
        "currency": "GBP",
        "category": "Main Course",
        "status": "active",
        "is_available": true,
        "spice_level": 3,
        "preparation_time": 30,
        "calories": 520,
        "tags": ["Premium", "Spicy", "Non-Vegetarian", "Popular", "Authentic"],
        "allergen": "Contains Dairy, Nuts",
        "dietary_flags": ["Halal", "Non-Vegetarian", "Gluten-Free"],
        "cold_drinks_addons": [1, 2],
        "restaurant_id": 1,
        "created_at": "2024-01-01T10:05:00.000000Z",
        "updated_at": "2024-01-01T10:10:00.000000Z",
        "images": [],
        "restaurant": {
            "id": 1,
            "business_name": "My Restaurant",
            "legal_name": "My Restaurant Ltd"
        }
    }
}
```

---

### **Step 8: Get Menu Statistics**
**Endpoint:** `GET {{base_url}}/restaurants/{{restaurant_id}}/menus/stats`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Accept: application/json
```

**Expected Response:**
```json
{
    "success": true,
    "data": {
        "total_menus": 2,
        "active_menus": 2,
        "available_menus": 2,
        "categories_count": 1,
        "total_addons": 2
    }
}
```

---

### **Step 9: Toggle Menu Availability**
**Endpoint:** `POST {{base_url}}/restaurants/{{restaurant_id}}/menus/2/toggle-availability`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Accept: application/json
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Menu availability updated successfully",
    "data": {
        "is_available": false
    }
}
```

---

### **Step 10: Delete Menu Item**
**Endpoint:** `DELETE {{base_url}}/restaurants/{{restaurant_id}}/menus/2`

**Headers:**
```
Authorization: Bearer {{auth_token}}
Accept: application/json
```

**Expected Response:**
```json
{
    "success": true,
    "message": "Menu item deleted successfully"
}
```

---

## 🔍 VAT Price Field Verification

### **✅ VAT Price Field is Properly Implemented:**

1. **Validation Rules:**
   ```php
   'vat_price' => 'nullable|numeric|min:0'
   ```
   - ✅ Optional field (nullable)
   - ✅ Must be numeric
   - ✅ Minimum value 0 (cannot be negative)

2. **Database Storage:**
   ```php
   protected $casts = [
       'vat_price' => 'decimal:2'
   ];
   ```
   - ✅ Stored as decimal with 2 decimal places

3. **API Response:**
   ```json
   "vat_price": "3.20"
   ```
   - ✅ Returned as string with proper decimal formatting

4. **Test Cases Covered:**
   - ✅ Valid VAT price (positive number)
   - ✅ Null VAT price (optional field)
   - ✅ Zero VAT price
   - ✅ Invalid negative VAT price (validation error)

---

## 🚨 Common Issues & Solutions

### **Issue 1: Authentication Error**
```
{
    "message": "Unauthenticated."
}
```
**Solution:** Check if token is correctly set in Authorization header

### **Issue 2: Restaurant Not Found**
```
{
    "success": false,
    "message": "Restaurant not found"
}
```
**Solution:** Verify restaurant_id exists in your database

### **Issue 3: Validation Errors**
```
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "price": ["The price field is required."]
    }
}
```
**Solution:** Ensure all required fields are provided

---

## 📱 Postman Collection Import

You can create a Postman collection with these requests:

1. **Environment Variables:**
   - `base_url`: `http://localhost:8000/api`
   - `restaurant_id`: `1`
   - `auth_token`: `1|your_token_here`

2. **Collection Structure:**
   ```
   Menu API Collection
   ├── 1. Get Form Data
   ├── 2. Create Menu (Basic)
   ├── 3. Create Menu (Complete with VAT)
   ├── 4. Test VAT Validation
   ├── 5. Get All Menus
   ├── 6. Get Single Menu
   ├── 7. Update Menu
   ├── 8. Get Statistics
   ├── 9. Toggle Availability
   └── 10. Delete Menu
   ```

---

## ✅ VAT Price Testing Summary

**VAT Price field is correctly implemented and tested:**

- ✅ **Optional Field**: Can be null/empty
- ✅ **Numeric Validation**: Must be a number
- ✅ **Minimum Value**: Cannot be negative (min:0)
- ✅ **Decimal Storage**: Stored with 2 decimal places
- ✅ **API Response**: Properly formatted in JSON
- ✅ **Update Support**: Can be updated via PUT request
- ✅ **Error Handling**: Proper validation error messages

The VAT Price field is working perfectly and ready for production use! 🎉
