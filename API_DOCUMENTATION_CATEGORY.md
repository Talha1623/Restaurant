# Restaurant Category Management API Documentation

## Overview
This API provides endpoints for managing restaurant categories with image support.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
- **Protected Endpoints**: All category management requires authentication
- **Headers Required**: `Authorization: Bearer {token}`

---

## Category Management

### Create Category with Image
**Endpoint:** `POST /api/restaurants/{restaurant_id}/categories`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Description:** Create a new category for a restaurant with optional image.

**Request Body (Form Data):**
```
name: "Appetizers"
description: "Delicious appetizers to start your meal"
image: [FILE] (optional - jpeg, png, jpg, gif, max 2MB)
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Category created successfully",
    "data": {
        "id": 1,
        "restaurant_id": 1,
        "name": "Appetizers",
        "description": "Delicious appetizers to start your meal",
        "image": "restaurant-categories/1699123456_appetizers.jpg",
        "is_active": true,
        "created_at": "2025-10-01T19:30:00.000000Z",
        "updated_at": "2025-10-01T19:30:00.000000Z"
    }
}
```

### Get All Categories
**Endpoint:** `GET /api/restaurants/{restaurant_id}/categories`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Get all categories for a restaurant.

**Success Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Appetizers",
            "description": "Delicious appetizers to start your meal",
            "image": "restaurant-categories/1699123456_appetizers.jpg",
            "is_active": true,
            "created_at": "2025-10-01T19:30:00.000000Z"
        },
        {
            "id": 2,
            "name": "Main Course",
            "description": "Hearty main dishes",
            "image": "restaurant-categories/1699123457_main_course.jpg",
            "is_active": true,
            "created_at": "2025-10-01T19:31:00.000000Z"
        }
    ]
}
```

### Get Specific Category
**Endpoint:** `GET /api/restaurants/{restaurant_id}/categories/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Get details of a specific category.

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "restaurant_id": 1,
        "name": "Appetizers",
        "description": "Delicious appetizers to start your meal",
        "image": "restaurant-categories/1699123456_appetizers.jpg",
        "is_active": true,
        "created_at": "2025-10-01T19:30:00.000000Z",
        "updated_at": "2025-10-01T19:30:00.000000Z"
    }
}
```

### Update Category with Image
**Endpoint:** `PUT /api/restaurants/{restaurant_id}/categories/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Description:** Update category information and optionally change image.

**Request Body (Form Data):**
```
name: "Updated Appetizers"
description: "Updated description"
image: [FILE] (optional - new image file)
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Category updated successfully",
    "data": {
        "id": 1,
        "restaurant_id": 1,
        "name": "Updated Appetizers",
        "description": "Updated description",
        "image": "restaurant-categories/1699123458_updated_appetizers.jpg",
        "is_active": true,
        "created_at": "2025-10-01T19:30:00.000000Z",
        "updated_at": "2025-10-01T19:35:00.000000Z"
    }
}
```

### Delete Category
**Endpoint:** `DELETE /api/restaurants/{restaurant_id}/categories/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Delete a category (also deletes associated image).

**Success Response (200):**
```json
{
    "success": true,
    "message": "Category deleted successfully"
}
```

### Toggle Category Status
**Endpoint:** `POST /api/restaurants/{restaurant_id}/categories/{id}/toggle`

**Headers:**
```
Authorization: Bearer {token}
```

**Description:** Toggle category active/inactive status.

**Success Response (200):**
```json
{
    "success": true,
    "message": "Category status updated successfully",
    "data": {
        "id": 1,
        "name": "Appetizers",
        "description": "Delicious appetizers to start your meal",
        "image": "restaurant-categories/1699123456_appetizers.jpg",
        "is_active": false,
        "created_at": "2025-10-01T19:30:00.000000Z",
        "updated_at": "2025-10-01T19:40:00.000000Z"
    }
}
```

---

## Image Handling

### Image Storage
- **Path:** `storage/app/public/restaurant-categories/`
- **URL Access:** `http://127.0.0.1:8000/storage/restaurant-categories/{filename}`
- **Supported Formats:** JPEG, PNG, JPG, GIF
- **Max Size:** 2MB

### Image URL Generation
```php
// In your application
$imageUrl = Storage::url($category->image);
// Result: http://127.0.0.1:8000/storage/restaurant-categories/filename.jpg
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
    "message": "Category not found"
}
```

### 422 Validation Error
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["The name field is required."],
        "image": ["The image must be an image."]
    }
}
```

### 500 Internal Server Error
```json
{
    "success": false,
    "message": "Failed to create category",
    "error": "Error details"
}
```

---

## Testing Examples

### Postman Setup

#### 1. Create Category with Image
- **Method:** POST
- **URL:** `http://127.0.0.1:8000/api/restaurants/1/categories`
- **Headers:**
  ```
  Authorization: Bearer {your-token}
  ```
- **Body:** Form-data
  - `name`: "Appetizers"
  - `description`: "Delicious appetizers"
  - `image`: [Select File] (choose image file)

#### 2. Create Category without Image
- **Method:** POST
- **URL:** `http://127.0.0.1:8000/api/restaurants/1/categories`
- **Headers:**
  ```
  Authorization: Bearer {your-token}
  ```
- **Body:** Form-data
  - `name`: "Main Course"
  - `description`: "Hearty main dishes"

#### 3. Update Category with New Image
- **Method:** PUT
- **URL:** `http://127.0.0.1:8000/api/restaurants/1/categories/1`
- **Headers:**
  ```
  Authorization: Bearer {your-token}
  ```
- **Body:** Form-data
  - `name`: "Updated Appetizers"
  - `description`: "Updated description"
  - `image`: [Select File] (choose new image file)

### cURL Examples

#### Create Category with Image
```bash
curl -X POST http://127.0.0.1:8000/api/restaurants/1/categories \
  -H "Authorization: Bearer {your-token}" \
  -F "name=Appetizers" \
  -F "description=Delicious appetizers" \
  -F "image=@/path/to/image.jpg"
```

#### Create Category without Image
```bash
curl -X POST http://127.0.0.1:8000/api/restaurants/1/categories \
  -H "Authorization: Bearer {your-token}" \
  -F "name=Main Course" \
  -F "description=Hearty main dishes"
```

#### Get All Categories
```bash
curl -X GET http://127.0.0.1:8000/api/restaurants/1/categories \
  -H "Authorization: Bearer {your-token}"
```

#### Update Category
```bash
curl -X PUT http://127.0.0.1:8000/api/restaurants/1/categories/1 \
  -H "Authorization: Bearer {your-token}" \
  -F "name=Updated Appetizers" \
  -F "description=Updated description" \
  -F "image=@/path/to/new-image.jpg"
```

#### Delete Category
```bash
curl -X DELETE http://127.0.0.1:8000/api/restaurants/1/categories/1 \
  -H "Authorization: Bearer {your-token}"
```

#### Toggle Category Status
```bash
curl -X POST http://127.0.0.1:8000/api/restaurants/1/categories/1/toggle \
  -H "Authorization: Bearer {your-token}"
```

---

## Notes

1. **Image Upload**: Images are stored in `storage/app/public/restaurant-categories/`
2. **Image Validation**: Only JPEG, PNG, JPG, GIF formats allowed, max 2MB
3. **Image Replacement**: When updating with new image, old image is automatically deleted
4. **Authentication**: All endpoints require valid Bearer token
5. **Unique Names**: Category names must be unique per restaurant
6. **File Storage**: Make sure to run `php artisan storage:link` for public access to images

---

## Storage Link Command
```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for accessing uploaded files via web.
