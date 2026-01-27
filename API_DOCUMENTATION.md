# API Documentation

This document describes the RESTful API endpoints for the RightClick system.

## Base URL
All API endpoints are prefixed with `/api`

## Authentication
Currently, the API routes are public. To add authentication, wrap the routes in `auth:sanctum` middleware.

## Endpoints

### Records API

#### List Records
```
GET /api/records
```

**Query Parameters:**
- `seat` (string, optional) - Filter by seat number
- `member_ID` (string, optional) - Filter by member ID (partial match)
- `paid` (boolean, optional) - Filter by paid status
- `online` (boolean, optional) - Filter by online status
- `date_from` (date, optional) - Filter records from this date (format: Y-m-d)
- `date_to` (date, optional) - Filter records to this date (format: Y-m-d)
- `sort_by` (string, optional) - Field to sort by (default: created_date)
- `sort_order` (string, optional) - Sort order: asc or desc (default: desc)
- `per_page` (integer, optional) - Number of records per page (default: 15)

**Example:**
```
GET /api/records?seat=A1&paid=true&per_page=10
```

#### Get Single Record
```
GET /api/records/{id}
```

#### Create Record
```
POST /api/records
```

**Request Body:**
```json
{
  "seat": "A1",
  "member_ID": "Time",
  "member_amount": 1500.00,
  "order": "Pepsi Cola",
  "order_amount": 2000.00,
  "total": 3500.00,
  "paid": true,
  "online": false,
  "debt": 0.00
}
```

#### Update Record
```
PUT /api/records/{id}
PATCH /api/records/{id}
```

**Request Body:** (same as create, all fields optional)

#### Delete Record
```
DELETE /api/records/{id}
```

---

### Inventory API

#### List Inventory Items
```
GET /api/inventories
```

**Query Parameters:**
- `type` (string, optional) - Filter by type: Drink or Food
- `item_name` (string, optional) - Filter by item name (partial match)
- `low_stock` (boolean, optional) - Filter items with low stock
- `low_stock_threshold` (integer, optional) - Threshold for low stock (default: 10)
- `sort_by` (string, optional) - Field to sort by (default: id)
- `sort_order` (string, optional) - Sort order: asc or desc (default: asc)
- `per_page` (integer, optional) - Number of items per page (default: 15)

**Example:**
```
GET /api/inventories?type=Drink&low_stock=true&low_stock_threshold=5
```

#### Get Single Inventory Item
```
GET /api/inventories/{id}
```

#### Create Inventory Item
```
POST /api/inventories
```

**Request Body:**
```json
{
  "item_name": "Redbull",
  "qty": 12.00,
  "price": 1400.00,
  "type": "Drink"
}
```

#### Update Inventory Item
```
PUT /api/inventories/{id}
PATCH /api/inventories/{id}
```

**Request Body:** (same as create, all fields optional)

#### Delete Inventory Item
```
DELETE /api/inventories/{id}
```

#### Update Inventory Quantity
```
POST /api/inventories/{id}/update-quantity
```

**Request Body:**
```json
{
  "qty": 5,
  "operation": "subtract"  // Options: "add", "subtract", "set" (default: "set")
}
```

**Operations:**
- `add` - Adds the specified quantity to current quantity
- `subtract` - Subtracts the specified quantity from current quantity (minimum 0)
- `set` - Sets the quantity to the specified value (default)

---

### Outcomes API

#### List Outcomes
```
GET /api/outcomes
```

**Query Parameters:**
- `description` (string, optional) - Filter by description (partial match)
- `price_min` (decimal, optional) - Minimum price filter
- `price_max` (decimal, optional) - Maximum price filter
- `date_from` (date, optional) - Filter outcomes from this date (format: Y-m-d)
- `date_to` (date, optional) - Filter outcomes to this date (format: Y-m-d)
- `sort_by` (string, optional) - Field to sort by (default: created_at)
- `sort_order` (string, optional) - Sort order: asc or desc (default: desc)
- `per_page` (integer, optional) - Number of outcomes per page (default: 15)

**Example:**
```
GET /api/outcomes?date_from=2023-07-01&date_to=2023-07-31
```

#### Get Total Outcomes
```
GET /api/outcomes/total
```

**Query Parameters:**
- `date_from` (date, optional) - Calculate total from this date
- `date_to` (date, optional) - Calculate total to this date

**Response:**
```json
{
  "total": 1000.00,
  "count": 5,
  "date_from": "2023-07-01",
  "date_to": "2023-07-31"
}
```

#### Get Single Outcome
```
GET /api/outcomes/{id}
```

#### Create Outcome
```
POST /api/outcomes
```

**Request Body:**
```json
{
  "description": "buy something that you don't like",
  "price": 1000.00
}
```

#### Update Outcome
```
PUT /api/outcomes/{id}
PATCH /api/outcomes/{id}
```

**Request Body:** (same as create, all fields optional)

#### Delete Outcome
```
DELETE /api/outcomes/{id}
```

---

## Response Format

All successful responses follow this format:

### Single Resource
```json
{
  "data": {
    "id": 1,
    "field1": "value1",
    "field2": "value2",
    ...
  }
}
```

### Collection (Paginated)
```json
{
  "data": [
    {
      "id": 1,
      "field1": "value1",
      ...
    },
    {
      "id": 2,
      "field1": "value2",
      ...
    }
  ],
  "links": {
    "first": "http://example.com/api/records?page=1",
    "last": "http://example.com/api/records?page=10",
    "prev": null,
    "next": "http://example.com/api/records?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "path": "http://example.com/api/records",
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```

### Success Response (Create/Update)
```json
{
  "message": "Record created successfully",
  "data": {
    "id": 1,
    ...
  }
}
```

### Error Response
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "The field name field is required."
    ]
  }
}
```

## HTTP Status Codes

- `200 OK` - Successful GET, PUT, PATCH, DELETE
- `201 Created` - Successful POST (resource created)
- `400 Bad Request` - Invalid request
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation error
- `500 Internal Server Error` - Server error

## Example Usage

### Using cURL

**Create a Record:**
```bash
curl -X POST http://localhost/api/records \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "seat": "A1",
    "member_ID": "Time",
    "member_amount": 1500.00,
    "order": "Pepsi",
    "order_amount": 1000.00,
    "total": 2500.00,
    "paid": true,
    "online": false,
    "debt": 0.00
  }'
```

**Get Records with Filters:**
```bash
curl -X GET "http://localhost/api/records?seat=A1&paid=true&per_page=10" \
  -H "Accept: application/json"
```

**Update Inventory Quantity:**
```bash
curl -X POST http://localhost/api/inventories/1/update-quantity \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "qty": 1,
    "operation": "subtract"
  }'
```

### Using JavaScript (Fetch API)

```javascript
// Get all records
fetch('http://localhost/api/records')
  .then(response => response.json())
  .then(data => console.log(data));

// Create a record
fetch('http://localhost/api/records', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    seat: 'A1',
    member_ID: 'Time',
    member_amount: 1500.00,
    order: 'Pepsi',
    order_amount: 1000.00,
    total: 2500.00,
    paid: true,
    online: false,
    debt: 0.00
  })
})
  .then(response => response.json())
  .then(data => console.log(data));
```

## Notes

- All decimal values are returned as floats in JSON
- Boolean values are returned as true/false
- Date fields are formatted as 'Y-m-d H:i:s'
- The API uses Laravel's pagination by default (15 items per page)
- All routes support both PUT and PATCH methods for updates
- Validation errors will return a 422 status code with detailed error messages
