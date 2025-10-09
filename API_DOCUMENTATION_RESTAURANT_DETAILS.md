# Restaurant Complete Details API Documentation

## Overview
This API endpoint allows customers to view complete restaurant information including menus and certificates when they click on a restaurant.

---

## Endpoint: Get Restaurant Complete Details

### URL
```
POST /api/restaurants/complete-details
```

### Authentication
**Not Required** - This is a public endpoint for customers

### Request Method
`POST`

### Request Headers
```
Content-Type: application/json
Accept: application/json
```

### Request Body Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| restaurant_id | integer | Yes | The ID of the restaurant to retrieve |

### Request Body Example
```json
{
    "restaurant_id": 1
}
```

---

## Response

### Success Response (200 OK)

```json
{
    "success": true,
    "message": "Restaurant details retrieved successfully",
    "data": {
        "restaurant": {
            "id": 1,
            "legal_name": "ABC Restaurant Ltd",
            "business_name": "ABC Fast Food",
            "address_line1": "123 Main Street",
            "city": "London",
            "postcode": "SW1A 1AA",
            "phone": "+44 20 1234 5678",
            "contact_person": "John Doe",
            "email": "abc@restaurant.com",
            "opening_time": "09:00",
            "closing_time": "22:00",
            "min_order": "15.00",
            "status": "active",
            "blocked": false,
            "cuisine_tags": "Italian, Fast Food",
            "delivery_zone": "Zone 1",
            "delivery_postcode": "SW1",
            "logo": "http://localhost/storage/restaurant-logos/logo.jpg",
            "banner": "http://localhost/storage/restaurant-banners/banner.jpg",
            "created_at": "2025-01-15T10:30:00.000000Z",
            "updated_at": "2025-01-15T10:30:00.000000Z"
        },
        "menus": [
            {
                "id": 1,
                "name": "Margherita Pizza",
                "description": "Classic Italian pizza with tomato sauce and mozzarella",
                "price": "12.99",
                "vat_price": "15.59",
                "currency": "GBP",
                "status": "active",
                "is_available": true,
                "image_url": "http://localhost/storage/menus/pizza.jpg",
                "created_at": "2025-01-15T11:00:00.000000Z"
            },
            {
                "id": 2,
                "name": "Caesar Salad",
                "description": "Fresh romaine lettuce with Caesar dressing",
                "price": "8.99",
                "vat_price": "10.79",
                "currency": "GBP",
                "status": "active",
                "is_available": true,
                "image_url": "http://localhost/storage/menus/salad.jpg",
                "created_at": "2025-01-15T11:15:00.000000Z"
            }
        ],
        "certificates": [
            {
                "id": 1,
                "name": "Food Safety Certificate",
                "type": "Health & Safety",
                "issue_date": "2025-01-01",
                "expiry_date": "2026-01-01",
                "issuing_authority": "Food Standards Agency",
                "certificate_file_url": "http://localhost/storage/certificates/cert1.pdf",
                "created_at": "2025-01-15T10:45:00.000000Z"
            },
            {
                "id": 2,
                "name": "Halal Certification",
                "type": "Halal",
                "issue_date": "2025-01-01",
                "expiry_date": "2026-01-01",
                "issuing_authority": "Halal Food Authority",
                "certificate_file_url": "http://localhost/storage/certificates/cert2.pdf",
                "created_at": "2025-01-15T10:50:00.000000Z"
            }
        ]
    }
}
```

### Error Responses

#### Validation Error (422 Unprocessable Entity)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "restaurant_id": [
            "The restaurant id field is required."
        ]
    }
}
```

#### Restaurant Not Found (404 Not Found)
```json
{
    "success": false,
    "message": "Restaurant not found",
    "error": "Restaurant with ID 999 does not exist in the database"
}
```

#### Server Error (500 Internal Server Error)
```json
{
    "success": false,
    "message": "Failed to retrieve restaurant details",
    "error": "Error message here",
    "debug": {
        "file": "/path/to/file.php",
        "line": 123
    }
}
```

---

## Testing with Postman

### Step 1: Create New Request
1. Open Postman
2. Create a new request
3. Set method to `POST`
4. Enter URL: `http://localhost:8000/api/restaurants/complete-details`

### Step 2: Set Headers
Add the following headers:
- `Content-Type`: `application/json`
- `Accept`: `application/json`

### Step 3: Set Request Body
1. Select "Body" tab
2. Choose "raw"
3. Select "JSON" from dropdown
4. Enter the following JSON:

```json
{
    "restaurant_id": 1
}
```

### Step 4: Send Request
Click "Send" button

### Step 5: Verify Response
- Check that status code is 200
- Verify response contains restaurant details
- Verify response contains menus array
- Verify response contains certificates array

---

## Testing with cURL

### Basic Test
```bash
curl -X POST http://localhost:8000/api/restaurants/complete-details \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"restaurant_id": 1}'
```

### Pretty Print Response
```bash
curl -X POST http://localhost:8000/api/restaurants/complete-details \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"restaurant_id": 1}' | json_pp
```

---

## Data Filtering

### Menu Filtering
The API automatically filters menus to show only:
- Active menus (`status = 'active'`)
- Ordered by most recent first

### Certificate Filtering
The API automatically filters certificates to show only:
- Active certificates (`status = 'active'`)
- Ordered by most recent first

---

## Use Cases

### Customer App
When a customer:
1. Views list of restaurants
2. Clicks on a restaurant
3. App calls this endpoint with the restaurant_id
4. Customer sees:
   - Restaurant details (name, address, hours, etc.)
   - Available menu items
   - Valid certificates (food safety, halal, etc.)

### Mobile App Integration
```javascript
// Example: React Native / JavaScript
const getRestaurantDetails = async (restaurantId) => {
    try {
        const response = await fetch('http://localhost:8000/api/restaurants/complete-details', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                restaurant_id: restaurantId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Restaurant:', data.data.restaurant);
            console.log('Menus:', data.data.menus);
            console.log('Certificates:', data.data.certificates);
            return data.data;
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Network error:', error);
    }
};

// Usage
getRestaurantDetails(1);
```

### Flutter Integration
```dart
// Example: Flutter / Dart
Future<Map<String, dynamic>?> getRestaurantDetails(int restaurantId) async {
  try {
    final response = await http.post(
      Uri.parse('http://localhost:8000/api/restaurants/complete-details'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'restaurant_id': restaurantId,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      if (data['success']) {
        print('Restaurant: ${data['data']['restaurant']}');
        print('Menus: ${data['data']['menus']}');
        print('Certificates: ${data['data']['certificates']}');
        return data['data'];
      }
    }
  } catch (e) {
    print('Error: $e');
  }
  return null;
}

// Usage
getRestaurantDetails(1);
```

---

## Important Notes

1. **Public Endpoint**: This endpoint does not require authentication, making it accessible to all customers

2. **Image URLs**: All image URLs (logo, banner, menu images, certificate files) are returned as full URLs ready for display

3. **Active Only**: Only active menus and certificates are returned to customers

4. **Performance**: The endpoint is optimized to fetch all data in a single request, reducing network calls from mobile apps

5. **Error Handling**: The endpoint includes comprehensive error handling and logging for debugging

6. **Null Handling**: If a restaurant has no menus or certificates, empty arrays are returned

---

## Troubleshooting

### Issue: Restaurant Not Found
**Solution**: Verify the restaurant_id exists in the database
```bash
# Check available restaurants
curl http://localhost:8000/api/admin/restaurants
```

### Issue: No Menus Returned
**Possible Causes**:
- Restaurant has no menus
- All menus are inactive
**Solution**: Check menu status in database

### Issue: No Certificates Returned
**Possible Causes**:
- Restaurant has no certificates
- All certificates are inactive
**Solution**: Check certificate status in database

### Issue: Images Not Loading
**Possible Causes**:
- Storage link not created
**Solution**: Run Laravel storage link command
```bash
cd restaurant
php artisan storage:link
```

---

## Related Endpoints

- `GET /api/admin/restaurants` - Get all restaurants list
- `POST /api/admin/restaurants/{id}` - Get single restaurant details (without menus/certificates)
- `POST /api/menus/list` - Get all menus for a restaurant
- `POST /api/certificates/restaurant-certificates` - Get all certificates for a restaurant

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2025-10-09 | Initial release |

---

## Support

For issues or questions, please contact the development team or create an issue in the project repository.

