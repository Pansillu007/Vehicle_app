# 🔌 API TESTING GUIDE

## Quick API Testing with cURL/Postman

---

## 1️⃣ REGISTER NEW USER

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {...},
    "token": "1|xyz..."
  }
}
```

---

## 2️⃣ LOGIN

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "demo@vehicleapp.com",
    "password": "password123"
  }'
```

**Save the token from response for next requests!**

---

## 3️⃣ GET AUTHENTICATED USER

```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 4️⃣ GET ALL VEHICLES

```bash
curl -X GET http://localhost:8000/api/vehicles \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 5️⃣ CREATE VEHICLE

```bash
curl -X POST http://localhost:8000/api/vehicles \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "My New Car",
    "make": "Toyota",
    "model": "Camry",
    "year": 2024,
    "number_plate": "NEW-1234",
    "color": "Blue",
    "mileage": 5000
  }'
```

---

## 6️⃣ GET SINGLE VEHICLE

```bash
curl -X GET http://localhost:8000/api/vehicles/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 7️⃣ UPDATE VEHICLE

```bash
curl -X PUT http://localhost:8000/api/vehicles/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "Updated Car Name",
    "make": "Honda",
    "model": "Civic",
    "year": 2023,
    "number_plate": "UPD-5678",
    "color": "Red",
    "mileage": 10000
  }'
```

---

## 8️⃣ DELETE VEHICLE

```bash
curl -X DELETE http://localhost:8000/api/vehicles/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 9️⃣ CREATE SERVICE RECORD

```bash
curl -X POST http://localhost:8000/api/vehicles/1/services \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "service_type": "Oil Change",
    "description": "Regular maintenance",
    "cost": 85.50,
    "service_date": "2024-05-15",
    "service_provider": "Petronas Service Center"
  }'
```

---

## 🔟 GET SERVICE RECORDS

```bash
curl -X GET http://localhost:8000/api/vehicles/1/services \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 1️⃣1️⃣ UPDATE SERVICE RECORD

```bash
curl -X PUT http://localhost:8000/api/services/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "service_type": "Full Service",
    "description": "Complete vehicle service",
    "cost": 250.00,
    "service_date": "2024-05-15",
    "service_provider": "Honda Service Center"
  }'
```

---

## 1️⃣2️⃣ DELETE SERVICE RECORD

```bash
curl -X DELETE http://localhost:8000/api/services/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 1️⃣3️⃣ LOGOUT

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📋 TESTING WITH POSTMAN

### Setup:
1. Create new Collection: "Vehicle Management API"
2. Add all endpoints above
3. Use **Tests** tab to save token:
```javascript
pm.environment.set("api_token", pm.response.json().data.token);
```
4. Use `{{api_token}}` in Authorization header

### Authorization:
- Type: **Bearer Token**
- Token: `{{api_token}}`

---

## ✅ EXPECTED RESPONSES

### Success Response:
```json
{
  "success": true,
  "message": "...",
  "data": {...}
}
```

### Error Response:
```json
{
  "success": false,
  "message": "...",
  "errors": {...}
}
```

### Unauthorized (401):
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403):
```json
{
  "success": false,
  "message": "Unauthorized access"
}
```

---

## 🎯 DEMO ACCOUNT

**Credentials:**
- Email: `demo@vehicleapp.com`
- Password: `password123`

**Pre-loaded Data:**
- 3 vehicles
- 5 service records
- Ready for testing!

---

## 🔍 API DOCUMENTATION

Visit: `http://localhost:8000/api/docs` (when logged in)

---

## 💡 TIPS

1. **Always include Bearer token** in protected routes
2. **Check response status codes**: 200 (OK), 201 (Created), 401 (Unauthorized), 403 (Forbidden), 404 (Not Found)
3. **Validate errors**: Check `errors` object in response
4. **Use Postman Collections**: Organize endpoints for easy testing
5. **Test with demo account first** before creating new users

---

**Ready for mobile app integration!** 📱
