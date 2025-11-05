# Attendance System Architecture

## System Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                         Attendance System                            │
└─────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                        User Interactions                              │
└──────────────────────────────────────────────────────────────────────┘

Public Users                    Authenticated Asesor           Admin Users
     │                                  │                            │
     │ GET /attendance/{slug}          │                            │
     ├──────────────────────────────────┼────────────────────────────┤
     │                                  │                            │
     ▼                                  ▼                            ▼
┌─────────────┐              ┌──────────────────┐      ┌────────────────────┐
│ umum form   │              │  asesor form     │      │ Admin Dashboard    │
│ (extended)  │              │  (authenticated) │      │                    │
│             │              │                  │      │ - Create           │
│ - name      │              │ Shows user info  │      │ - Edit             │
│ - email     │              │ - signature      │      │ - Delete           │
│ - phone     │              │ - notes          │      │ - View Responses   │
│ - unsur     │              │                  │      └────────────────────┘
│ - instansi  │              └──────────────────┘                │
│ - domisili  │                       │                           │
│ - signature │                       │                           │
└─────────────┘                       │                           │
     │                                │                           │
     │ internal form                 │                           │
     │ (basic)                       │                           │
     │ - name                        │                           │
     │ - email                       │                           │
     │ - signature                   │                           │
     │                               │                           │
     └────────────┬──────────────────┘                           │
                  │                                               │
                  │ POST /attendance/{slug}/submit               │
                  │                                               │
                  ▼                                               ▼
         ┌──────────────────────────────────────────────────────────────┐
         │            AttendanceFormController                          │
         │                                                               │
         │  - Validate date range                                       │
         │  - Check authentication (asesor type)                        │
         │  - Validate input (type-specific)                            │
         │  - Build payload                                             │
         │  - Store response                                            │
         └──────────────────────────────────────────────────────────────┘
                  │                                               │
                  │                                               │
                  ▼                                               ▼
┌──────────────────────────────────────────────────────────────────────┐
│                        Database Layer                                 │
│                                                                       │
│  ┌─────────────────┐              ┌──────────────────────────────┐  │
│  │  attendances    │              │  attendance_responses        │  │
│  │                 │              │                              │  │
│  │ - id            │◄─────────────│ - id                         │  │
│  │ - title         │              │ - attendance_id (FK)         │  │
│  │ - description   │              │ - user_id (nullable FK)      │  │
│  │ - type          │              │ - name                       │  │
│  │ - created_by    │              │ - email                      │  │
│  │ - slug          │              │ - payload (JSON)             │  │
│  │ - start_date    │              │ - ip                         │  │
│  │ - end_date      │              │ - timestamps                 │  │
│  │ - timestamps    │              └──────────────────────────────┘  │
│  └─────────────────┘                                                │
│           │                                                          │
│           │                                                          │
│  ┌─────────────────┐       (Legacy - Still supported)               │
│  │attendance_details│                                                │
│  │                 │                                                │
│  │ - id            │                                                │
│  │ - attendance_id │                                                │
│  │ - user_id       │                                                │
│  │ - signature     │                                                │
│  │ - signed_at     │                                                │
│  └─────────────────┘                                                │
└──────────────────────────────────────────────────────────────────────┘
                  │
                  ▼
         ┌──────────────────────┐
         │  Thank You Page      │
         │                      │
         │  Success confirmation│
         └──────────────────────┘
```

## Request Flow

### Public Form Submission (umum/internal)
```
1. User visits: /attendance/{attendance-slug}
2. AttendanceFormController::show()
   - Load attendance by slug
   - Validate date range
   - Return view based on type
3. User fills form and submits
4. POST /attendance/{attendance-slug}/submit
5. AttendanceFormController::submit()
   - Validate date range
   - Validate form data (type-specific)
   - Create AttendanceResponse record
   - Store payload as JSON
6. Redirect to thank you page
7. GET /attendance/{attendance-slug}/thankyou
```

### Asesor Form Submission
```
1. User visits: /attendance/{attendance-slug}
2. AttendanceFormController::show()
   - Load attendance by slug
   - Check if type is 'asesor'
   - Verify Auth::check() → redirect to login if not authenticated
   - Return asesor view
3. Form displays authenticated user info
4. User adds signature and optional notes
5. POST /attendance/{attendance-slug}/submit
   - Same flow as public, but:
   - user_id set from Auth::id()
   - name/email from Auth::user()
6. Thank you page
```

### Admin Response Viewing
```
1. Admin logs in
2. Navigate to /admin/attendance
3. Click "View Responses" icon
4. GET /admin/attendance/{slug}/responses
5. Admin\AttendanceController::showResponses()
   - Load attendance with responses
   - Return responses view
6. DataTables displays responses
7. Click "Details" button
8. Modal shows full response data including signature
```

## Data Models

### Attendance Model
```php
Relationships:
- hasMany: AttendanceDetail (legacy)
- hasMany: AttendanceResponse (new)
- belongsTo: User (creator via created_by)

Casts:
- start_date: datetime
- end_date: datetime
```

### AttendanceResponse Model
```php
Relationships:
- belongsTo: Attendance
- belongsTo: User (nullable)

Casts:
- payload: array (JSON)

Payload Structure (type-specific):
{
  "umum": {
    "phone": "0812345678",
    "unsur": "...",
    "instansi": "...",
    "domisili": "...",
    "signature": "data:image/png;base64,..."
  },
  "internal": {
    "signature": "data:image/png;base64,..."
  },
  "asesor": {
    "signature": "data:image/png;base64,...",
    "notes": "Optional notes..."
  }
}
```

## Authorization Matrix

| Action                    | umum | internal | asesor | admin |
|---------------------------|------|----------|--------|-------|
| View Form                 | ✓    | ✓        | Auth*  | ✓     |
| Submit Response           | ✓    | ✓        | Auth*  | ✓     |
| View Responses            | ✗    | ✗        | ✗      | ✓     |
| Create/Edit/Delete Attend | ✗    | ✗        | ✗      | ✓     |

*Auth = Requires authentication (Auth::check())
*For production: Add hasRole('asesor') check

## Key Components

### Controllers
1. **AttendanceFormController** (Public)
   - show() - Display form
   - submit() - Process submission
   - thankyou() - Confirmation page

2. **Admin\AttendanceController** (Admin)
   - index() - List attendances
   - create/store/edit/update/destroy() - CRUD
   - detail() - View legacy attendance_details
   - showResponses() - View new attendance_responses

### Middleware Stack
```
Public Routes:
  /attendance/* → No middleware (public access)
                  Controller enforces auth for asesor type

Admin Routes:
  /admin/attendance/* → auth, verified, role:admin
```

### Validation Rules

**umum type:**
- name: required, string, max:255
- email: nullable, email, max:255
- phone: required, string, max:20
- unsur: required, string, max:255
- instansi: required, string, max:255
- domisili: required, string, max:255
- signature: required, string

**internal type:**
- name: required, string, max:255
- email: nullable, email, max:255
- signature: required, string

**asesor type:**
- signature: required, string
- notes: nullable, string, max:1000
- (name/email from authenticated user)

## Security Features

1. **CSRF Protection**: All POST forms include @csrf token
2. **Date Validation**: Submissions only allowed within start/end dates
3. **IP Logging**: Client IP stored with each response
4. **Authentication**: Enforced for asesor type forms
5. **Role-Based Access**: Admin routes protected by role:admin middleware
6. **Foreign Key Constraints**: Cascade deletes, set null appropriately

## Performance Considerations

1. **Database Indexes**: On attendance_id and user_id in attendance_responses
2. **Eager Loading**: Use with('responses.user') when loading for admin
3. **DataTables**: Client-side pagination for response list
4. **JSON Storage**: Flexible schema without additional columns

## Extensibility

To add a new attendance type:

1. Add enum value to attendances migration
2. Update validation in AttendanceFormController::getValidationRules()
3. Update payload building in AttendanceFormController::buildPayload()
4. Create new Blade view in resources/views/attendance/forms/{type}.blade.php
5. Update documentation

## Testing Points

- [ ] Migration runs successfully
- [ ] Can create all three attendance types
- [ ] Public can access umum/internal forms
- [ ] Asesor form requires authentication
- [ ] Date validation works (before/after range)
- [ ] Signatures are captured and stored
- [ ] Admin can view responses
- [ ] Response details modal shows all data
- [ ] Signature images display correctly in admin
- [ ] IP addresses are logged
- [ ] JSON payload stores correctly
