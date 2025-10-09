# Menus by Category API Documentation

## Overview
This API endpoint allows customers to retrieve all menu items from all restaurants that belong to a specific category. When a customer selects a category (like "Pizza" or "Burger"), this endpoint returns all available menus from all active restaurants in that category.

---

## Endpoint: Get Menus by Category

### URL
```
POST /api/menus/by-category
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
| category_id | integer | Yes | The ID of the menu category to retrieve menus for |

### Request Body Example
```json
{
    "category_id": 2
}
```

---

## Response

### Success Response (200 OK)

```json
{
    "success": true,
    "message": "Menus retrieved successfully",
    "data": {
        "category": {
            "id": 2,
            "name": "Pizza",
            "description": "All types of delicious pizzas",
            "image_url": "http://localhost/storage/categories/pizza.jpg"
        },
        "total_menus": 5,
        "total_restaurants": 2,
        "menus": [
            {
                "id": 1,
                "name": "Margherita Pizza",
                "description": "Classic Italian pizza with fresh tomato sauce, mozzarella cheese, and basil",
                "price": "12.99",
                "vat_price": "15.59",
                "currency": "GBP",
                "image_url": "http://localhost/storage/menus/margherita.jpg",
                "is_available": true,
                "spice_level": 0,
                "preparation_time": 20,
                "calories": 850,
                "tags": ["vegetarian", "classic"],
                "dietary_flags": ["vegetarian"],
                "restaurant": {
                    "id": 1,
                    "business_name": "ABC Restaurant",
                    "legal_name": "ABC Restaurant Ltd",
                    "logo": "http://localhost/storage/restaurant-logos/abc-logo.jpg",
                    "city": "London",
                    "phone": "+44 20 1234 5678",
                    "email": "abc@restaurant.com",
                    "opening_time": "09:00",
                    "closing_time": "22:00",
                    "min_order": "15.00",
                    "delivery_zone": "Zone 1",
                    "cuisine_tags": "Italian, Fast Food"
                },
                "created_at": "2025-01-15T10:30:00.000000Z"
            },
            {
                "id": 2,
                "name": "Pepperoni Pizza",
                "description": "Spicy pepperoni with extra cheese",
                "price": "14.99",
                "vat_price": "17.99",
                "currency": "GBP",
                "image_url": "http://localhost/storage/menus/pepperoni.jpg",
                "is_available": true,
                "spice_level": 2,
                "preparation_time": 25,
                "calories": 950,
                "tags": ["spicy", "popular"],
                "dietary_flags": [],
                "restaurant": {
                    "id": 1,
                    "business_name": "ABC Restaurant",
                    "legal_name": "ABC Restaurant Ltd",
                    "logo": "http://localhost/storage/restaurant-logos/abc-logo.jpg",
                    "city": "London",
                    "phone": "+44 20 1234 5678",
                    "email": "abc@restaurant.com",
                    "opening_time": "09:00",
                    "closing_time": "22:00",
                    "min_order": "15.00",
                    "delivery_zone": "Zone 1",
                    "cuisine_tags": "Italian, Fast Food"
                },
                "created_at": "2025-01-15T10:35:00.000000Z"
            },
            {
                "id": 5,
                "name": "Cheese Burst Pizza",
                "description": "Extra cheese with cheese-filled crust",
                "price": "16.99",
                "vat_price": "20.39",
                "currency": "GBP",
                "image_url": "http://localhost/storage/menus/cheese-burst.jpg",
                "is_available": true,
                "spice_level": 0,
                "preparation_time": 30,
                "calories": 1100,
                "tags": ["vegetarian", "cheese-lover"],
                "dietary_flags": ["vegetarian"],
                "restaurant": {
                    "id": 2,
                    "business_name": "XYZ Fast Food",
                    "legal_name": "XYZ Fast Food Ltd",
                    "logo": "http://localhost/storage/restaurant-logos/xyz-logo.jpg",
                    "city": "Manchester",
                    "phone": "+44 161 234 5678",
                    "email": "xyz@restaurant.com",
                    "opening_time": "10:00",
                    "closing_time": "23:00",
                    "min_order": "10.00",
                    "delivery_zone": "Zone 2",
                    "cuisine_tags": "Italian, Pizza"
                },
                "created_at": "2025-01-15T11:00:00.000000Z"
            }
        ]
    }
}
```

### Response Data Structure

#### Category Object
- `id`: Category ID
- `name`: Category name
- `description`: Category description
- `image_url`: Full URL to category image

#### Menu Object
- `id`: Menu item ID
- `name`: Menu item name
- `description`: Detailed description
- `price`: Base price (without VAT)
- `vat_price`: Price including VAT
- `currency`: Currency code (GBP, USD, EUR, PKR)
- `image_url`: Full URL to menu item image
- `is_available`: Whether the item is currently available
- `spice_level`: Spice level (0-5)
- `preparation_time`: Time to prepare in minutes
- `calories`: Calorie count
- `tags`: Array of tags
- `dietary_flags`: Array of dietary information
- `restaurant`: Restaurant details object
- `created_at`: Creation timestamp

#### Restaurant Object (nested in each menu)
- `id`: Restaurant ID
- `business_name`: Restaurant business name
- `legal_name`: Restaurant legal name
- `logo`: Full URL to restaurant logo
- `city`: Restaurant city
- `phone`: Restaurant phone number
- `email`: Restaurant email
- `opening_time`: Opening time (HH:MM format)
- `closing_time`: Closing time (HH:MM format)
- `min_order`: Minimum order amount
- `delivery_zone`: Delivery zone
- `cuisine_tags`: Cuisine types

---

## Error Responses

### Validation Error (422 Unprocessable Entity)

**Missing category_id:**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "category_id": [
            "The category id field is required."
        ]
    }
}
```

**Invalid category_id:**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "category_id": [
            "The selected category id is invalid."
        ]
    }
}
```

### Category Not Found (404 Not Found)
```json
{
    "success": false,
    "message": "Category not found",
    "error": "Category with ID 999 does not exist"
}
```

### No Menus Found (200 OK - Empty Result)
```json
{
    "success": true,
    "message": "Menus retrieved successfully",
    "data": {
        "category": {
            "id": 2,
            "name": "Pizza",
            "description": "All types of delicious pizzas",
            "image_url": "http://localhost/storage/categories/pizza.jpg"
        },
        "total_menus": 0,
        "total_restaurants": 0,
        "menus": []
    }
}
```

### Server Error (500 Internal Server Error)
```json
{
    "success": false,
    "message": "Failed to retrieve menus",
    "error": "Error message here",
    "debug": {
        "file": "/path/to/file.php",
        "line": 123
    }
}
```

---

## Filtering & Sorting

### Applied Filters
The endpoint automatically applies the following filters:

1. **Active Menus Only**: Only menus with `status = 'active'`
2. **Active Restaurants Only**: Only menus from restaurants with `status = 'active'`
3. **Specific Category**: Only menus with the specified `category_id`

### Sorting
Menus are sorted by **price in ascending order** (lowest price first).

---

## Testing with Postman

### Step 1: Create New Request
1. Open Postman
2. Create a new request
3. Set method to `POST`
4. Enter URL: `http://localhost:8000/api/menus/by-category`

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
    "category_id": 2
}
```

### Step 4: Send Request
Click "Send" button

### Step 5: Verify Response
- Check that status code is 200
- Verify response contains category details
- Verify response contains menus array
- Check that each menu has restaurant details
- Verify total_menus and total_restaurants counts

---

## Testing with cURL

### Basic Test
```bash
curl -X POST http://localhost:8000/api/menus/by-category \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"category_id": 2}'
```

### Pretty Print Response
```bash
curl -X POST http://localhost:8000/api/menus/by-category \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"category_id": 2}' | json_pp
```

### Test with Different Category
```bash
curl -X POST http://localhost:8000/api/menus/by-category \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"category_id": 1}'
```

---

## Use Cases

### Customer Mobile App Flow

1. **Categories Screen**
   - Customer opens the app
   - Sees list of categories (Pizza, Burger, Drinks, etc.)

2. **Select Category**
   - Customer taps on "Pizza" category
   
3. **API Call**
   ```javascript
   POST /api/menus/by-category
   Body: {"category_id": 2}
   ```

4. **Display Results**
   - Show all pizzas from all restaurants
   - Each pizza shows:
     - Name and image
     - Price
     - Restaurant name and logo
     - Availability status
     - Spice level
     - Preparation time

5. **Customer Actions**
   - Tap on a menu item to see details
   - Tap on restaurant to see full restaurant page
   - Add to cart
   - Filter/sort (future feature)

---

## Mobile App Integration Examples

### React Native / JavaScript
```javascript
const getMenusByCategory = async (categoryId) => {
    try {
        const response = await fetch('http://localhost:8000/api/menus/by-category', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                category_id: categoryId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Category:', data.data.category.name);
            console.log('Total Menus:', data.data.total_menus);
            console.log('Total Restaurants:', data.data.total_restaurants);
            
            // Display menus
            data.data.menus.forEach(menu => {
                console.log(`${menu.name} - ${menu.currency} ${menu.price}`);
                console.log(`Restaurant: ${menu.restaurant.business_name}`);
            });
            
            return data.data;
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Network error:', error);
    }
};

// Usage
getMenusByCategory(2); // Get all pizzas
```

### Flutter / Dart
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

Future<Map<String, dynamic>?> getMenusByCategory(int categoryId) async {
  try {
    final response = await http.post(
      Uri.parse('http://localhost:8000/api/menus/by-category'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'category_id': categoryId,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      
      if (data['success']) {
        print('Category: ${data['data']['category']['name']}');
        print('Total Menus: ${data['data']['total_menus']}');
        print('Total Restaurants: ${data['data']['total_restaurants']}');
        
        // Display menus
        for (var menu in data['data']['menus']) {
          print('${menu['name']} - ${menu['currency']} ${menu['price']}');
          print('Restaurant: ${menu['restaurant']['business_name']}');
        }
        
        return data['data'];
      }
    }
  } catch (e) {
    print('Error: $e');
  }
  return null;
}

// Usage
getMenusByCategory(2); // Get all pizzas
```

### React (Web)
```jsx
import React, { useState, useEffect } from 'react';

function MenusByCategory({ categoryId }) {
    const [menus, setMenus] = useState([]);
    const [category, setCategory] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchMenus();
    }, [categoryId]);

    const fetchMenus = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/menus/by-category', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ category_id: categoryId })
            });

            const data = await response.json();

            if (data.success) {
                setCategory(data.data.category);
                setMenus(data.data.menus);
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            setLoading(false);
        }
    };

    if (loading) return <div>Loading...</div>;

    return (
        <div>
            <h1>{category?.name}</h1>
            <p>{category?.description}</p>
            <p>Total Menus: {menus.length}</p>
            
            <div className="menu-grid">
                {menus.map(menu => (
                    <div key={menu.id} className="menu-card">
                        <img src={menu.image_url} alt={menu.name} />
                        <h3>{menu.name}</h3>
                        <p>{menu.description}</p>
                        <p className="price">{menu.currency} {menu.price}</p>
                        
                        <div className="restaurant-info">
                            <img src={menu.restaurant.logo} alt={menu.restaurant.business_name} />
                            <span>{menu.restaurant.business_name}</span>
                        </div>
                        
                        {!menu.is_available && <span className="unavailable">Unavailable</span>}
                    </div>
                ))}
            </div>
        </div>
    );
}
```

---

## Business Logic

### What Menus Are Included?
- ✅ Active menus (`status = 'active'`)
- ✅ From active restaurants (`restaurant.status = 'active'`)
- ✅ Matching the specified category_id
- ✅ Both available and unavailable items (with availability flag)

### What Menus Are Excluded?
- ❌ Inactive menus
- ❌ Menus from inactive/blocked restaurants
- ❌ Menus from other categories

### Sorting Order
- Sorted by price: **lowest to highest**
- This helps customers find affordable options first

---

## Performance Considerations

### Optimizations
1. **Eager Loading**: Restaurant data is loaded with menus using `with(['restaurant'])`
2. **Single Query**: All data fetched in one database query
3. **Filtered at Database Level**: Active status filtering done in SQL
4. **Indexed Columns**: `category_id`, `status`, and `restaurant_id` should be indexed

### Expected Response Times
- Small dataset (< 50 menus): < 100ms
- Medium dataset (50-200 menus): < 200ms
- Large dataset (> 200 menus): < 500ms

---

## Troubleshooting

### Issue: Empty Menus Array
**Possible Causes:**
1. Category has no menus
2. All menus in category are inactive
3. All restaurants are inactive

**Solution:**
- Check menu database: `SELECT * FROM menus WHERE category_id = 2`
- Check restaurant status: `SELECT * FROM restaurants WHERE status = 'active'`

### Issue: Category Not Found
**Solution:**
- Get list of valid categories:
```bash
curl http://localhost:8000/api/menu-categories
```

### Issue: Images Not Loading
**Solution:**
- Ensure storage link is created:
```bash
cd restaurant
php artisan storage:link
```

### Issue: Restaurant Data Missing
**Solution:**
- Check that menus have valid `restaurant_id`
- Check restaurant relationships in Menu model

---

## Related Endpoints

- `GET /api/menu-categories` - Get all menu categories
- `POST /api/restaurants/complete-details` - Get restaurant details with menus
- `POST /api/menus/list` - Get menus for a specific restaurant
- `GET /api/admin/restaurants` - Get all restaurants list

---

## Future Enhancements

Potential features to add in future versions:

1. **Additional Filters**
   - Price range filter
   - City/location filter
   - Spice level filter
   - Dietary filters (vegetarian, vegan, halal, etc.)
   - Availability filter

2. **Sorting Options**
   - Sort by name (A-Z, Z-A)
   - Sort by price (low-high, high-low)
   - Sort by popularity
   - Sort by rating

3. **Pagination**
   - For large result sets
   - Page size configuration

4. **Search**
   - Search within category results
   - Filter by menu name or description

5. **Analytics**
   - Popular menus in category
   - Average price in category
   - Most active restaurants in category

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2025-10-09 | Initial release |

---

## Support

For issues or questions, please contact the development team or create an issue in the project repository.

