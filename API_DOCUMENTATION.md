# Restaurant Management API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
Most endpoints require authentication using Laravel Sanctum. Include the token in the Authorization header:
```
Authorization: Bearer {your-token}
```

---

## Restaurant Authentication Endpoints

### 1. Restaurant Registration
**POST** `/api/restaurants/register`

**Request Body:**
```json
{
    "legal_name": "Pizza Palace Ltd",
    "business_name": "Pizza Palace",
    "email": "info@pizzapalace.com",
    "restaurant_password": "password123",
    "phone": "+1234567890",
    "contact_person": "John Doe",
    "address_line1": "123 Main Street",
    "city": "London",
    "postcode": "SW1A 1AA",
    "opening_time": "09:00",
    "closing_time": "22:00",
    "min_order": 15.00,
    "status": "active",
    "cuisine_tags": "Italian, Pizza",
    "delivery_zone": "5",
    "delivery_postcode": "SW1A 1AA"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Restaurant registered successfully",
    "data": {
        "restaurant": {
            "id": 1,
            "name": "Restaurant Name",
            "email": "restaurant@example.com",
            "phone": "+1234567890",
            "address": "123 Main St",
            "city": "London",
            "postcode": "SW1A 1AA",
            "cuisine_type": "Italian",
            "status": "active",
            "created_at": "2025-01-20T10:00:00.000000Z"
        },
        "token": "1|abc123..."
    }
}
```

### 2. Restaurant Login
**POST** `/api/restaurants/login`

**Request Body:**
```json
{
    "email": "restaurant@example.com",
    "restaurant_password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "restaurant": {
            "id": 1,
            "name": "Restaurant Name",
            "email": "restaurant@example.com",
            "phone": "+1234567890",
            "address": "123 Main St",
            "city": "London",
            "postcode": "SW1A 1AA",
            "cuisine_type": "Italian",
            "status": "active",
            "opening_hours": "09:00",
            "closing_hours": "22:00",
            "delivery_available": true,
            "minimum_order": 15.00
        },
        "token": "1|abc123..."
    }
}
```

### 3. Restaurant Logout
**POST** `/api/restaurants/logout`
*Requires Authentication*

**Response:**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### 4. Get Restaurant Profile
**GET** `/api/restaurants/profile`
*Requires Authentication*

**Response:**
```json
{
    "success": true,
    "data": {
        "restaurant": {
            "id": 1,
            "name": "Restaurant Name",
            "email": "restaurant@example.com",
            "phone": "+1234567890",
            "address": "123 Main St",
            "city": "London",
            "postcode": "SW1A 1AA",
            "cuisine_type": "Italian",
            "status": "active",
            "opening_hours": "09:00",
            "closing_hours": "22:00",
            "delivery_available": true,
            "minimum_order": 15.00,
            "logo": "http://localhost:8000/storage/restaurant-logos/logo.jpg"
        }
    }
}
```

---

## Certificate Management Endpoints

### 1. Get Certificate Types
**GET** `/api/certificates/types`

**Response:**
```json
{
    "success": true,
    "data": {
        "certificate_types": [
            {
                "id": 1,
                "name": "Food Safety Certificate",
                "description": "Food safety and hygiene certificate",
                "is_active": true
            }
        ]
    }
}
```

### 2. Get Issuing Authorities
**GET** `/api/certificates/authorities`

**Response:**
```json
{
    "success": true,
    "data": {
        "issuing_authorities": [
            {
                "id": 1,
                "name": "Food Standards Agency",
                "description": "UK Food Standards Agency",
                "is_active": true
            }
        ]
    }
}
```

### 3. Get All Certificates
**GET** `/api/certificates`

**Query Parameters:**
- `restaurant_id` (optional) - Filter by restaurant ID
- `search` (optional) - Search in name, type, certificate number, issuing authority
- `status` (optional) - Filter by status (active, inactive, expired, pending)
- `type` (optional) - Filter by certificate type
- `per_page` (optional) - Number of results per page (default: 15)

**Headers:**
```
Authorization: Bearer {your-token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "restaurant_id": 1,
                "name": "Food Safety Certificate",
                "type": "Food Safety",
                "issue_date": "2024-01-15",
                "expiry_date": "2025-01-15",
                "issuing_authority": "Food Standards Agency",
                "certificate_number": "FSA-2024-001",
                "description": "Annual food safety certificate",
                "certificate_file": "http://localhost:8000/storage/certificates/abc123.pdf",
                "status": "active",
                "created_at": "2024-01-15T10:00:00.000000Z",
                "updated_at": "2024-01-15T10:00:00.000000Z",
                "restaurant": {
                    "id": 1,
                    "business_name": "Pizza Palace",
                    "legal_name": "Pizza Palace Ltd"
                }
            }
        ],
        "total": 1,
        "per_page": 15
    }
}
```

### 4. Create Certificate
**POST** `/api/certificates`

**Headers:**
```
Authorization: Bearer {your-token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
restaurant_id: 1
name: Food Safety Certificate
type: Food Safety
issue_date: 2024-01-15
expiry_date: 2025-01-15
issuing_authority: Food Standards Agency
certificate_number: FSA-2024-001
description: Annual food safety certificate
certificate_file: [FILE] (PDF, JPG, JPEG, PNG - Max: 5MB)
status: active
```

**Response:**
```json
{
    "success": true,
    "message": "Certificate created successfully",
    "data": {
        "certificate": {
            "id": 1,
            "restaurant_id": 1,
            "name": "Food Safety Certificate",
            "type": "Food Safety",
            "issue_date": "2024-01-15",
            "expiry_date": "2025-01-15",
            "issuing_authority": "Food Standards Agency",
            "certificate_number": "FSA-2024-001",
            "description": "Annual food safety certificate",
            "certificate_file": "http://localhost:8000/storage/certificates/abc123.pdf",
            "status": "active",
            "created_at": "2024-01-15T10:00:00.000000Z",
            "updated_at": "2024-01-15T10:00:00.000000Z",
            "restaurant": {
                "id": 1,
                "business_name": "Pizza Palace",
                "legal_name": "Pizza Palace Ltd"
            }
        }
    }
}
```

### 5. Get Single Certificate
**GET** `/api/certificates/{id}`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "certificate": {
            "id": 1,
            "restaurant_id": 1,
            "name": "Food Safety Certificate",
            "type": "Food Safety",
            "issue_date": "2024-01-15",
            "expiry_date": "2025-01-15",
            "issuing_authority": "Food Standards Agency",
            "certificate_number": "FSA-2024-001",
            "description": "Annual food safety certificate",
            "certificate_file": "http://localhost:8000/storage/certificates/abc123.pdf",
            "status": "active",
            "created_at": "2024-01-15T10:00:00.000000Z",
            "updated_at": "2024-01-15T10:00:00.000000Z",
            "restaurant": {
                "id": 1,
                "business_name": "Pizza Palace",
                "legal_name": "Pizza Palace Ltd"
            }
        }
    }
}
```

### 6. Update Certificate
**PUT** `/api/certificates/{id}`

**Headers:**
```
Authorization: Bearer {your-token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
name: Updated Food Safety Certificate
type: Food Safety
issue_date: 2024-01-15
expiry_date: 2025-01-15
issuing_authority: Food Standards Agency
certificate_number: FSA-2024-001-UPDATED
description: Updated annual food safety certificate
certificate_file: [FILE] (Optional - PDF, JPG, JPEG, PNG - Max: 5MB)
status: active
```

**Response:**
```json
{
    "success": true,
    "message": "Certificate updated successfully",
    "data": {
        "certificate": {
            "id": 1,
            "restaurant_id": 1,
            "name": "Updated Food Safety Certificate",
            "type": "Food Safety",
            "issue_date": "2024-01-15",
            "expiry_date": "2025-01-15",
            "issuing_authority": "Food Standards Agency",
            "certificate_number": "FSA-2024-001-UPDATED",
            "description": "Updated annual food safety certificate",
            "certificate_file": "http://localhost:8000/storage/certificates/abc123.pdf",
            "status": "active",
            "created_at": "2024-01-15T10:00:00.000000Z",
            "updated_at": "2024-01-15T11:00:00.000000Z",
            "restaurant": {
                "id": 1,
                "business_name": "Pizza Palace",
                "legal_name": "Pizza Palace Ltd"
            }
        }
    }
}
```

### 7. Delete Certificate
**DELETE** `/api/certificates/{id}`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Response:**
```json
{
    "success": true,
    "message": "Certificate deleted successfully"
}
```

---

## Restaurant Management Endpoints (Admin)

### 1. Get All Restaurants
**GET** `/api/admin/restaurants`

**Query Parameters:**
- `search` (optional): Search in name, email, phone, city
- `status` (optional): Filter by status (active/inactive)
- `city` (optional): Filter by city
- `per_page` (optional): Number of results per page (default: 15)

**Example:**
```
GET /api/admin/restaurants?search=pizza&status=active&city=London&per_page=10
```

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "legal_name": "Pizza Palace Ltd",
                "business_name": "Pizza Palace",
                "address_line1": "123 Main Street",
                "city": "London",
                "postcode": "SW1A 1AA",
                "phone": "+1234567890",
                "contact_person": "John Doe",
                "email": "info@pizzapalace.com",
                "opening_time": "09:00",
                "closing_time": "22:00",
                "min_order": 15.00,
                "status": "active",
                "blocked": false,
                "cuisine_tags": "Italian, Pizza",
                "delivery_zone": "5",
                "delivery_postcode": "SW1A 1AA",
                "logo": "http://localhost:8000/storage/restaurant-logos/logo.jpg",
                "banner": "http://localhost:8000/storage/restaurant-banners/banner.jpg",
                "created_at": "2025-01-20T10:00:00.000000Z",
                "updated_at": "2025-01-20T10:00:00.000000Z"
            }
        ],
        "total": 1,
        "per_page": 15,
        "last_page": 1
    }
}
```

### 2. Create Restaurant
**POST** `/api/admin/restaurants`
*Requires Authentication*

**Request Body:**
```json
{
    "legal_name": "Pizza Palace Ltd",
    "business_name": "Pizza Palace",
    "address_line1": "123 Main Street",
    "city": "London",
    "postcode": "SW1A 1AA",
    "phone": "+1234567890",
    "contact_person": "John Doe",
    "email": "info@pizzapalace.com",
    "password": "password123",
    "opening_time": "09:00",
    "closing_time": "22:00",
    "min_order": 15.00,
    "status": "active",
    "cuisine_tags": "Italian, Pizza",
    "delivery_zone": "5",
    "delivery_postcode": "SW1A 1AA"
}
```

**File Upload (multipart/form-data):**
- `logo`: Restaurant logo image (optional)
- `banner`: Restaurant banner image (optional)

**Response:**
```json
{
    "success": true,
    "message": "Restaurant created successfully",
    "data": {
        "restaurant": {
            "id": 1,
            "legal_name": "Pizza Palace Ltd",
            "business_name": "Pizza Palace",
            "address_line1": "123 Main Street",
            "city": "London",
            "postcode": "SW1A 1AA",
            "phone": "+1234567890",
            "contact_person": "John Doe",
            "email": "info@pizzapalace.com",
            "opening_time": "09:00",
            "closing_time": "22:00",
            "min_order": 15.00,
            "status": "active",
            "blocked": false,
            "cuisine_tags": "Italian, Pizza",
            "delivery_zone": "5",
            "delivery_postcode": "SW1A 1AA",
            "logo": "http://localhost:8000/storage/restaurant-logos/logo.jpg",
            "banner": "http://localhost:8000/storage/restaurant-banners/banner.jpg",
            "created_at": "2025-01-20T10:00:00.000000Z",
            "updated_at": "2025-01-20T10:00:00.000000Z"
        }
    }
}
```

### 3. Get Single Restaurant
**GET** `/api/admin/restaurants/{id}`

**Response:**
```json
{
    "success": true,
    "data": {
        "restaurant": {
            "id": 1,
            "legal_name": "Pizza Palace Ltd",
            "business_name": "Pizza Palace",
            "address_line1": "123 Main Street",
            "city": "London",
            "postcode": "SW1A 1AA",
            "phone": "+1234567890",
            "contact_person": "John Doe",
            "email": "info@pizzapalace.com",
            "opening_time": "09:00",
            "closing_time": "22:00",
            "min_order": 15.00,
            "status": "active",
            "blocked": false,
            "cuisine_tags": "Italian, Pizza",
            "delivery_zone": "5",
            "delivery_postcode": "SW1A 1AA",
            "logo": "http://localhost:8000/storage/restaurant-logos/logo.jpg",
            "banner": "http://localhost:8000/storage/restaurant-banners/banner.jpg",
            "created_at": "2025-01-20T10:00:00.000000Z",
            "updated_at": "2025-01-20T10:00:00.000000Z"
        }
    }
}
```

### 4. Update Restaurant
**PUT** `/api/admin/restaurants/{id}`
*Requires Authentication*

**Request Body:** (All fields optional)
```json
{
    "legal_name": "Updated Legal Name",
    "business_name": "Updated Business Name",
    "address_line1": "456 New Street",
    "city": "Manchester",
    "postcode": "M1 1AA",
    "phone": "+9876543210",
    "contact_person": "Jane Smith",
    "email": "newemail@restaurant.com",
    "password": "newpassword123",
    "opening_time": "08:00",
    "closing_time": "23:00",
    "min_order": 20.00,
    "status": "inactive",
    "cuisine_tags": "Chinese, Thai",
    "delivery_zone": "10",
    "delivery_postcode": "M1 1AA"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Restaurant updated successfully",
    "data": {
        "restaurant": {
            "id": 1,
            "legal_name": "Updated Legal Name",
            "business_name": "Updated Business Name",
            // ... updated fields
        }
    }
}
```

### 5. Delete Restaurant
**DELETE** `/api/admin/restaurants/{id}`
*Requires Authentication*

**Response:**
```json
{
    "success": true,
    "message": "Restaurant deleted successfully"
}
```

### 6. Toggle Restaurant Status
**POST** `/api/admin/restaurants/{id}/toggle-status`
*Requires Authentication*

**Response:**
```json
{
    "success": true,
    "message": "Restaurant status updated successfully",
    "data": {
        "restaurant": {
            "id": 1,
            "status": "inactive",
            // ... other fields
        }
    }
}
```

### 7. Toggle Restaurant Block
**POST** `/api/admin/restaurants/{id}/toggle-block`
*Requires Authentication*

**Response:**
```json
{
    "success": true,
    "message": "Restaurant block status updated successfully",
    "data": {
        "restaurant": {
            "id": 1,
            "blocked": true,
            // ... other fields
        }
    }
}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "Restaurant not found"
}
```

### Authentication Error (401)
```json
{
    "success": false,
    "message": "Invalid email or password"
}
```

---

## Field Descriptions

### Required Fields for Restaurant Creation:
- `legal_name`: Company's legal name
- `business_name`: Restaurant's display name
- `address_line1`: Street address
- `city`: City name
- `postcode`: Postal code
- `phone`: Contact phone number
- `contact_person`: Main contact person
- `email`: Restaurant email (must be unique)
- `password`: Restaurant password (min 6 characters)
- `opening_time`: Opening time (HH:MM format)
- `closing_time`: Closing time (HH:MM format)
- `min_order`: Minimum order amount
- `status`: active or inactive

### Optional Fields:
- `cuisine_tags`: Comma-separated cuisine types
- `delivery_zone`: Delivery area in km
- `delivery_postcode`: Delivery postal code
- `logo`: Restaurant logo image file
- `banner`: Restaurant banner image file

---

## Testing the API

You can test these endpoints using:
- **Postman**
- **Insomnia**
- **cURL**
- **Laravel HTTP Client**

### Example cURL for creating a restaurant:
```bash
curl -X POST http://localhost:8000/api/admin/restaurants \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "legal_name": "Test Restaurant Ltd",
    "business_name": "Test Restaurant",
    "address_line1": "123 Test Street",
    "city": "London",
    "postcode": "SW1A 1AA",
    "phone": "+1234567890",
    "contact_person": "Test Person",
    "email": "test@restaurant.com",
    "password": "password123",
    "opening_time": "09:00",
    "closing_time": "22:00",
    "min_order": 15.00,
    "status": "active"
  }'
```
