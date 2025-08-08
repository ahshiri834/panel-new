# Migration to JWT Auth

To switch legacy endpoints to the new Auth layer, send requests with the header:

```
Authorization: Bearer <token>
```

Existing session-based authentication will continue to work during the transition.
