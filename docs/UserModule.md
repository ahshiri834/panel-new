# User Module

Endpoints for basic user management.

## Create User
POST `/api/users`
```json
{
  "name": "John",
  "email": "john@example.com",
  "password": "secret",
  "role_id": 1
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

## Assign Role
POST `/api/users/{id}/role`
```json
{
  "role_id": 2
}
```
Response
```json
{
  "success": true,
  "message": "updated",
  "data": null
}
```
