# Customer Management API Documentation

## Overview
This API provides endpoints for customer registration and management in the restaurant system.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
- **Public Endpoints**: Customer registration, login, listing, and viewing
- **Protected Endpoints**: Customer profile management, updates, deletion, and status management (requires Sanctum token)

---

## Customer Registration

### Register New Customer
**Endpoint:** `POST /api/customers/register`

**Description:** Register a new customer in the system.

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "dob": "1990-01-01",
    "gender": "male",
    "address_line1": "123 Main Street",
    "city": "New York",
    "postcode": "10001",
    "country": "USA",
    "username": "johndoe",
    "password": "password123",
    "status": "active",
    "registration_date": "2025-01-01"
}
```

**Field Requirements:**
- `first_name` (required): Customer's first name
- `last_name` (required): Customer's last name
- `email` (required): Valid email address (must be unique)
- `phone` (optional): Phone number
- `dob` (optional): Date of birth (YYYY-MM-DD format)
- `gender` (optional): One of: male, female, other, prefer_not_to_say
- `address_line1` (optional): Address line 1
- `city` (required): City name
- `postcode` (optional): Postal code
- `country` (optional): Country name
- `username` (optional): Username (must be unique if provided)
- `password` (required): Password (minimum 6 characters)
- `status` (required): One of: active, inactive, blocked
- `registration_date` (optional): Registration date (defaults to current date if not provided)

**Success Response (201):**
```json
{
    "success": true,
    "message": "Customer registered successfully",
    "data": {
        "customer": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "dob": "1990-01-01",
            "gender": "male",
            "address_line1": "123 Main Street",
            "city": "New York",
            "postcode": "10001",
            "country": "USA",
            "username": "johndoe",
            "status": "active",
            "registration_date": "2025-01-01",
            "created_at": "2025-01-01T10:00:00.000000Z",
            "updated_at": "2025-01-01T10:00:00.000000Z"
        }
    }
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

---

## Customer Authentication

### Customer Login
**Endpoint:** `POST /api/customer/login`

**Description:** Authenticate customer and get access token.

**Request Body:**
```json
{
    "email": "john.doe@example.com",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": {
            "id": 3,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe@example.com",
            "phone": "+1234567890",
            "dob": "1990-01-15",
            "gender": "male",
            "address_line1": "123 Main Street",
            "city": "New York",
            "postcode": "10001",
            "country": "USA",
            "username": "johndoe",
            "status": "active",
            "registration_date": "2025-01-01",
            "created_at": "2025-10-01T19:14:25.000000Z",
            "updated_at": "2025-10-01T19:14:25.000000Z"
        },
        "token": "1|abcdef1234567890...",
        "token_type": "Bearer"
    }
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### Customer Logout
**Endpoint:** `POST /api/customer/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Logout customer and revoke token.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Logout successful"
}
```

### Get Customer Profile
**Endpoint:** `GET /api/customer/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Get authenticated customer's profile.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Profile retrieved successfully",
    "data": {
        "customer": {
            "id": 3,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe@example.com",
            "phone": "+1234567890",
            "dob": "1990-01-15",
            "gender": "male",
            "address_line1": "123 Main Street",
            "city": "New York",
            "postcode": "10001",
            "country": "USA",
            "username": "johndoe",
            "status": "active",
            "registration_date": "2025-01-01",
            "created_at": "2025-10-01T19:14:25.000000Z",
            "updated_at": "2025-10-01T19:14:25.000000Z"
        }
    }
}
```

### Update Customer Profile
**Endpoint:** `PUT /api/customer/profile`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Description:** Update authenticated customer's profile.

**Request Body:**
```json
{
    "first_name": "John Updated",
    "last_name": "Doe Updated",
    "phone": "+9876543210",
    "city": "Los Angeles"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "customer": {
            "id": 3,
            "first_name": "John Updated",
            "last_name": "Doe Updated",
            "email": "john.doe@example.com",
            "phone": "+9876543210",
            "city": "Los Angeles",
            "status": "active",
            "updated_at": "2025-10-01T19:30:00.000000Z"
        }
    }
}
```

### Change Password
**Endpoint:** `POST /api/customer/change-password`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Description:** Change customer password.

**Request Body:**
```json
{
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "confirm_password": "newpassword123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Password changed successfully"
}
```

**Error Response (400):**
```json
{
    "success": false,
    "message": "Current password is incorrect"
}
```

---

## Customer Management

### Get All Customers
**Endpoint:** `GET /api/customers`

**Description:** Retrieve a list of all customers with optional filtering and pagination.

**Query Parameters:**
- `search` (optional): Search in first name, last name, email, or phone
- `status` (optional): Filter by status (active, inactive, blocked)
- `per_page` (optional): Number of items per page (default: 15)

**Example Request:**
```
GET /api/customers?search=john&status=active&per_page=10
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Customers retrieved successfully",
    "data": {
        "customers": [
            {
                "id": 1,
                "first_name": "John",
                "last_name": "Doe",
                "email": "john@example.com",
                "phone": "+1234567890",
                "dob": "1990-01-01",
                "gender": "male",
                "address_line1": "123 Main Street",
                "city": "New York",
                "postcode": "10001",
                "country": "USA",
                "username": "johndoe",
                "status": "active",
                "registration_date": "2025-01-01",
                "created_at": "2025-01-01T10:00:00.000000Z",
                "updated_at": "2025-01-01T10:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 1,
            "from": 1,
            "to": 1
        }
    }
}
```

### Get Specific Customer
**Endpoint:** `GET /api/customers/{id}`

**Description:** Retrieve details of a specific customer.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Customer retrieved successfully",
    "data": {
        "customer": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "dob": "1990-01-01",
            "gender": "male",
            "address_line1": "123 Main Street",
            "city": "New York",
            "postcode": "10001",
            "country": "USA",
            "username": "johndoe",
            "status": "active",
            "registration_date": "2025-01-01",
            "created_at": "2025-01-01T10:00:00.000000Z",
            "updated_at": "2025-01-01T10:00:00.000000Z"
        }
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "Customer not found"
}
```

---

## Protected Endpoints (Require Authentication)

### Update Customer
**Endpoint:** `PUT /api/customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Description:** Update customer information.

**Request Body:** Same as registration, but all fields are optional.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Customer updated successfully",
    "data": {
        "customer": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "dob": "1990-01-01",
            "gender": "male",
            "address_line1": "123 Main Street",
            "city": "New York",
            "postcode": "10001",
            "country": "USA",
            "username": "johndoe",
            "status": "active",
            "registration_date": "2025-01-01",
            "created_at": "2025-01-01T10:00:00.000000Z",
            "updated_at": "2025-01-01T10:00:00.000000Z"
        }
    }
}
```

### Delete Customer
**Endpoint:** `DELETE /api/customers/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Delete a customer from the system.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Customer deleted successfully"
}
```

### Toggle Customer Status
**Endpoint:** `POST /api/customers/{id}/toggle-status`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Toggle customer status between active and inactive.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Customer status updated successfully",
    "data": {
        "customer": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "status": "inactive",
            "created_at": "2025-01-01T10:00:00.000000Z",
            "updated_at": "2025-01-01T10:00:00.000000Z"
        }
    }
}
```

### Toggle Customer Block
**Endpoint:** `POST /api/customers/{id}/toggle-block`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Toggle customer block status between active and blocked.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Customer block status updated successfully",
    "data": {
        "customer": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "status": "blocked",
            "created_at": "2025-01-01T10:00:00.000000Z",
            "updated_at": "2025-01-01T10:00:00.000000Z"
        }
    }
}
```

---

## Error Responses

### 400 Bad Request
```json
{
    "success": false,
    "message": "Bad request"
}
```

### 401 Unauthorized
```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

### 404 Not Found
```json
{
    "success": false,
    "message": "Customer not found"
}
```

### 422 Validation Error
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

### 500 Internal Server Error
```json
{
    "success": false,
    "message": "Registration failed",
    "error": "Error details"
}
```

---

## Testing Examples

### cURL Examples

**Register Customer:**
```bash
curl -X POST http://127.0.0.1:8000/api/customers/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "city": "New York",
    "password": "password123",
    "status": "active"
  }'
```

**Get All Customers:**
```bash
curl -X GET "http://127.0.0.1:8000/api/customers?search=john&status=active"
```

**Get Specific Customer:**
```bash
curl -X GET http://127.0.0.1:8000/api/customers/1
```

**Update Customer (with auth):**
```bash
curl -X PUT http://127.0.0.1:8000/api/customers/1 \
  -H "Authorization: Bearer {your-token}" \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Jane",
    "last_name": "Smith"
  }'
```

---

## Notes

1. **Password Security**: Passwords are automatically hashed using Laravel's Hash facade
2. **Email Uniqueness**: Email addresses must be unique across all customers
3. **Username Uniqueness**: Usernames must be unique if provided
4. **Auto-generated Fields**: Registration date defaults to current date if not provided
5. **Status Values**: Status can be 'active', 'inactive', or 'blocked'
6. **Gender Values**: Gender can be 'male', 'female', 'other', or 'prefer_not_to_say'
7. **Pagination**: Default page size is 15 items, can be customized with `per_page` parameter
