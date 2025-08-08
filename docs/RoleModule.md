# Role Module

Manage roles and their permissions.

## Create Role
POST `/api/roles`
```json
{
  "name": "admin"
}
```

## Assign Permission
POST `/api/roles/{id}/permissions`
```json
{
  "permission_id": 1
}
```

## List Role Permissions
GET `/api/roles/{id}/permissions`
