# Menu Category Management API Documentation

## Overview
This API provides endpoints for managing menu categories with image support.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
- **Public Endpoints**: Category listing and viewing
- **Protected Endpoints**: Category creation, updates, deletion (requires Sanctum token)

---

## Menu Category Management

### Create Menu Category with Image
**Endpoint:** `POST /api/menu-categories`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Description:** Create a new menu category with optional image.

**Request Body (Form Data):**
```
name: "Appetizers"
description: "Delicious appetizers to start your meal"
image: [FILE] (optional - jpeg, png, jpg, gif, max 2MB)
is_active: true (optional, default: true)
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Menu category created successfully",
    "data": {
        "category": {
            "id": 1,
            "name": "Appetizers",
            "description": "Delicious appetizers to start your meal",
            "image": "menu-categories/1699123456_appetizers.jpg",
            "image_url": "http://127.0.0.1:8000/storage/menu-categories/1699123456_appetizers.jpg",
            "is_active": true,
            "created_at": "2025-10-01T19:30:00.000000Z",
            "updated_at": "2025-10-01T19:30:00.000000Z"
        }
    }
}
```

### Get All Menu Categories
**Endpoint:** `GET /api/menu-categories`

**Description:** Retrieve all menu categories with optional filtering and pagination.

**Query Parameters:**
- `search` (optional): Search in name or description
- `status` (optional): Filter by status (active/inactive)
- `per_page` (optional): Number of items per page (default: 15)

**Example Request:**
```
GET /api/menu-categories?search=appetizers&status=active&per_page=10
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Menu categories retrieved successfully",
    "data": {
        "categories": [
            {
                "id": 1,
                "name": "Appetizers",
                "description": "Delicious appetizers to start your meal",
                "image": "menu-categories/1699123456_appetizers.jpg",
                "image_url": "http://127.0.0.1:8000/storage/menu-categories/1699123456_appetizers.jpg",
                "is_active": true,
                "created_at": "2025-10-01T19:30:00.000000Z",
                "updated_at": "2025-10-01T19:30:00.000000Z"
            },
            {
                "id": 2,
                "name": "Main Course",
                "description": "Hearty main dishes",
                "image": "menu-categories/1699123457_main_course.jpg",
                "image_url": "http://127.0.0.1:8000/storage/menu-categories/1699123457_main_course.jpg",
                "is_active": true,
                "created_at": "2025-10-01T19:31:00.000000Z",
                "updated_at": "2025-10-01T19:31:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 2,
            "from": 1,
            "to": 2
        }
    }
}
```

### Get Specific Menu Category
**Endpoint:** `GET /api/menu-categories/{id}`

**Description:** Retrieve details of a specific menu category.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Menu category retrieved successfully",
    "data": {
        "category": {
            "id": 1,
            "name": "Appetizers",
            "description": "Delicious appetizers to start your meal",
            "image": "menu-categories/1699123456_appetizers.jpg",
            "image_url": "http://127.0.0.1:8000/storage/menu-categories/1699123456_appetizers.jpg",
            "is_active": true,
            "created_at": "2025-10-01T19:30:00.000000Z",
            "updated_at": "2025-10-01T19:30:00.000000Z"
        }
    }
}
```

### Update Menu Category with Image
**Endpoint:** `PUT /api/menu-categories/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Description:** Update menu category information and optionally change image.

**Request Body (Form Data):**
```
name: "Updated Appetizers"
description: "Updated description"
image: [FILE] (optional - new image file)
is_active: true (optional)
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Menu category updated successfully",
    "data": {
        "category": {
            "id": 1,
            "name": "Updated Appetizers",
            "description": "Updated description",
            "image": "menu-categories/1699123458_updated_appetizers.jpg",
            "image_url": "http://127.0.0.1:8000/storage/menu-categories/1699123458_updated_appetizers.jpg",
            "is_active": true,
            "created_at": "2025-10-01T19:30:00.000000Z",
            "updated_at": "2025-10-01T19:35:00.000000Z"
        }
    }
}
```

### Delete Menu Category
**Endpoint:** `DELETE /api/menu-categories/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Delete a menu category (also deletes associated image).

**Success Response (200):**
```json
{
    "success": true,
    "message": "Menu category deleted successfully"
}
```

### Toggle Menu Category Status
**Endpoint:** `POST /api/menu-categories/{id}/toggle`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Toggle menu category active/inactive status.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Menu category status updated successfully",
    "data": {
        "category": {
            "id": 1,
            "name": "Appetizers",
            "description": "Delicious appetizers to start your meal",
            "image": "menu-categories/1699123456_appetizers.jpg",
            "image_url": "http://127.0.0.1:8000/storage/menu-categories/1699123456_appetizers.jpg",
            "is_active": false,
            "created_at": "2025-10-01T19:30:00.000000Z",
            "updated_at": "2025-10-01T19:40:00.000000Z"
        }
    }
}
```

---

## Image Handling

### Image Storage
- **Path:** `storage/app/public/menu-categories/`
- **URL Access:** `http://127.0.0.1:8000/storage/menu-categories/{filename}`
- **Supported Formats:** JPEG, PNG, JPG, GIF
- **Max Size:** 2MB

### Image URL Generation
```php
// In your application
$imageUrl = Storage::url($category->image);
// Result: http://127.0.0.1:8000/storage/menu-categories/filename.jpg
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
    "message": "Menu category not found"
}
```

### 422 Validation Error
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["The name field is required."],
        "name": ["The name has already been taken."],
        "image": ["The image must be an image."]
    }
}
```

### 500 Internal Server Error
```json
{
    "success": false,
    "message": "Failed to create menu category",
    "error": "Error details"
}
```

---

## Testing Examples

### Postman Setup

#### 1. Create Menu Category with Image
- **Method:** POST
- **URL:** `http://127.0.0.1:8000/api/menu-categories`
- **Headers:**
  ```
  Authorization: Bearer {your-token}
  ```
- **Body:** Form-data
  - `name`: "Appetizers"
  - `description`: "Delicious appetizers"
  - `image`: [Select File] (choose image file)

#### 2. Create Menu Category without Image
- **Method:** POST
- **URL:** `http://127.0.0.1:8000/api/menu-categories`
- **Headers:**
  ```
  Authorization: Bearer {your-token}
  ```
- **Body:** Form-data
  - `name`: "Main Course"
  - `description`: "Hearty main dishes"

#### 3. Update Menu Category with New Image
- **Method:** PUT
- **URL:** `http://127.0.0.1:8000/api/menu-categories/1`
- **Headers:**
  ```
  Authorization: Bearer {your-token}
  ```
- **Body:** Form-data
  - `name`: "Updated Appetizers"
  - `description`: "Updated description"
  - `image`: [Select File] (choose new image file)

#### 4. Get All Menu Categories
- **Method:** GET
- **URL:** `http://127.0.0.1:8000/api/menu-categories`

#### 5. Get Specific Menu Category
- **Method:** GET
- **URL:** `http://127.0.0.1:8000/api/menu-categories/1`

#### 6. Toggle Menu Category Status
- **Method:** POST
- **URL:** `http://127.0.0.1:8000/api/menu-categories/1/toggle`
- **Headers:**
  ```
  Authorization: Bearer {your-token}
  ```

#### 7. Delete Menu Category
- **Method:** DELETE
- **URL:** `http://127.0.0.1:8000/api/menu-categories/1`
- **Headers:**
  ```
  Authorization: Bearer {your-token}
  ```

### cURL Examples

#### Create Menu Category with Image
```bash
curl -X POST http://127.0.0.1:8000/api/menu-categories \
  -H "Authorization: Bearer {your-token}" \
  -F "name=Appetizers" \
  -F "description=Delicious appetizers" \
  -F "image=@/path/to/image.jpg"
```

#### Create Menu Category without Image
```bash
curl -X POST http://127.0.0.1:8000/api/menu-categories \
  -H "Authorization: Bearer {your-token}" \
  -F "name=Main Course" \
  -F "description=Hearty main dishes"
```

#### Get All Menu Categories
```bash
curl -X GET "http://127.0.0.1:8000/api/menu-categories?search=appetizers&status=active"
```

#### Get Specific Menu Category
```bash
curl -X GET http://127.0.0.1:8000/api/menu-categories/1
```

#### Update Menu Category
```bash
curl -X PUT http://127.0.0.1:8000/api/menu-categories/1 \
  -H "Authorization: Bearer {your-token}" \
  -F "name=Updated Appetizers" \
  -F "description=Updated description" \
  -F "image=@/path/to/new-image.jpg"
```

#### Toggle Menu Category Status
```bash
curl -X POST http://127.0.0.1:8000/api/menu-categories/1/toggle \
  -H "Authorization: Bearer {your-token}"
```

#### Delete Menu Category
```bash
curl -X DELETE http://127.0.0.1:8000/api/menu-categories/1 \
  -H "Authorization: Bearer {your-token}"
```

---

## Notes

1. **Image Upload**: Images are stored in `storage/app/public/menu-categories/`
2. **Image Validation**: Only JPEG, PNG, JPG, GIF formats allowed, max 2MB
3. **Image Replacement**: When updating with new image, old image is automatically deleted
4. **Authentication**: Protected endpoints require valid Bearer token
5. **Unique Names**: Category names must be unique across all categories
6. **File Storage**: Make sure to run `php artisan storage:link` for public access to images

---

## Storage Link Command
```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for accessing uploaded files via web.

---

## Frontend Integration

### Form Fields for "Add New Category":
```
Category Name * (required)
Description (Optional)
Select Image (optional file input)
```

### API Integration:
```javascript
// Example JavaScript for form submission
const formData = new FormData();
formData.append('name', document.getElementById('categoryName').value);
formData.append('description', document.getElementById('description').value);
formData.append('image', document.getElementById('imageInput').files[0]);

fetch('/api/menu-categories', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer ' + token
    },
    body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```
