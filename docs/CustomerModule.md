# Customer Module

CRUD endpoints for managing doctors (customers).

## Create Customer
POST `/api/customers`
```json
{
  "doctor_type": "Male",
  "name": "Dr. Strange",
  "mobile": "0912000000"
}
```
Response
```json
{
  "success": true,
  "message": "created",
  "data": {"id": 1}
}
```

## Transfer Ownership
POST `/api/customers/transfer`
```json
{
  "mobile": "0912000000",
  "newOwner": 5
}
```
Response
```json
{
  "success": true,
  "message": "ok",
  "data": {"id": 1}
}
```
