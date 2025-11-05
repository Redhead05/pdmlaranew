# Attendance System Documentation

## Overview
This attendance system supports three types of attendance forms:
- **umum**: Public form with extended fields (name, email, phone, unsur, instansi, domisili, signature)
- **internal**: Public form with basic fields (name, email, signature)
- **asesor**: Authenticated form requiring asesor role (uses authenticated user's info, includes signature and optional notes)

## Routes

### Public Routes
These routes are accessible to anyone:
```
GET  /attendance/{attendance}           - Show attendance form based on type
POST /attendance/{attendance}/submit    - Submit attendance response
GET  /attendance/{attendance}/thankyou  - Thank you page after submission
```

**Note:** While routes are public, the controller enforces authentication for 'asesor' type attendances.

### Admin Routes
These routes require authentication and admin role:
```
GET    /admin/attendance                      - List all attendances
POST   /admin/attendance                      - Create new attendance
GET    /admin/attendance/{id}/edit            - Edit attendance form
PUT    /admin/attendance/{id}                 - Update attendance
DELETE /admin/attendance/{id}                 - Delete attendance
GET    /admin/attendance/{slug}/detail        - View old attendance details (attendance_details table)
GET    /admin/attendance/{slug}/responses     - View new attendance responses (attendance_responses table)
```

## Access Control

### Type-Based Access
- **umum**: Public access, no authentication required
- **internal**: Public access, no authentication required
- **asesor**: Requires authentication (checked via `Auth::check()`)

### Role-Based Access
The system uses Spatie Laravel Permission package for role management:
- **Admin users**: Can create, edit, delete attendances and view all responses
- **Asesor users**: Can submit 'asesor' type attendances

### TODO: Enhanced Role Checks
For production use, enhance the authentication checks in `AttendanceFormController`:

```php
// In show() and submit() methods, replace:
if ($attendance->type === 'asesor' && !Auth::check()) {
    // ...
}

// With:
if ($attendance->type === 'asesor' && (!Auth::check() || !Auth::user()->hasRole('asesor'))) {
    return redirect()->route('login')->with('error', 'You must be logged in as an asesor to access this attendance.');
}
```

## Database Schema

### attendances Table
- `id`: Primary key
- `title`: Attendance title
- `description`: Attendance description
- `type`: Enum ('umum', 'internal', 'asesor')
- `created_by`: Foreign key to users table
- `slug`: Unique slug for URL
- `start_date`: Attendance start datetime
- `end_date`: Attendance end datetime
- `timestamps`: Created/updated timestamps

### attendance_responses Table
- `id`: Primary key
- `attendance_id`: Foreign key to attendances (cascade on delete)
- `user_id`: Nullable foreign key to users (set null on delete)
- `name`: Respondent name
- `email`: Respondent email (nullable)
- `payload`: JSON field for additional data (signature, phone, etc.)
- `ip`: IP address of submission
- `timestamps`: Created/updated timestamps
- **Indexes**: On `attendance_id` and `user_id`

## Models

### Attendance Model
Location: `app/Models/Attendance.php`

Relationships:
- `attendanceDetail()`: hasMany to AttendanceDetail (old system)
- `responses()`: hasMany to AttendanceResponse (new system)
- `creator()`: belongsTo User (created_by)

### AttendanceResponse Model
Location: `app/Models/AttendanceResponse.php`

Relationships:
- `attendance()`: belongsTo Attendance
- `user()`: belongsTo User (nullable)

Casts:
- `payload`: array (JSON)

## Controllers

### AttendanceFormController
Location: `app/Http/Controllers/AttendanceFormController.php`

Public controller handling:
- Form display based on attendance type
- Form validation and submission
- Thank you page display
- Date range validation (start_date to end_date)
- Authentication enforcement for 'asesor' type

### Admin\Attendance\AttendanceController
Location: `app/Http/Controllers/Admin/Attendance/AttendanceController.php`

Admin controller handling:
- CRUD operations for attendances
- `detail($slug)`: Shows old attendance details (attendance_details)
- `showResponses($slug)`: Shows new attendance responses (attendance_responses)

## Views

### Public Forms
- `resources/views/attendance/forms/umum.blade.php`
- `resources/views/attendance/forms/internal.blade.php`
- `resources/views/attendance/forms/asesor.blade.php`
- `resources/views/attendance/thankyou.blade.php`

### Admin Views
- `resources/views/menu/admin/attendance/index.blade.php` (list attendances)
- `resources/views/menu/admin/attendance/responses.blade.php` (view responses)

## Usage

### Creating an Attendance
1. Login as admin user
2. Navigate to `/admin/attendance`
3. Click "Create" button
4. Fill in title, description, type, start_date, end_date
5. Submit form

### Sharing Attendance Form
Share the public URL with participants:
```
https://yoursite.com/attendance/{attendance-slug}
```

### Viewing Responses
1. Login as admin
2. Navigate to `/admin/attendance`
3. Click the "View Responses" icon (list_alt) next to the attendance
4. View submitted responses with details

### Submitting Attendance (Users)
1. Open the attendance URL
2. Fill in required fields based on type
3. Sign in the signature pad
4. Submit form
5. See thank you confirmation

## Development Notes

### Adding New Fields
To add new fields to a specific attendance type:

1. Update validation rules in `AttendanceFormController::getValidationRules()`
2. Update payload building in `AttendanceFormController::buildPayload()`
3. Add form fields to the appropriate Blade view
4. Update the responses view to display new fields

### Security Considerations
- IP addresses are logged for all submissions
- CSRF protection is enabled on all POST routes
- Date range validation prevents submissions outside the active period
- Authentication required for 'asesor' type (enforce role check for production)

## Testing Workflow

1. Create an attendance as admin
2. Test public access to umum/internal forms
3. Test authenticated access to asesor form
4. Submit responses and verify data storage
5. View responses in admin panel
6. Verify signatures display correctly
7. Test date range restrictions

## Migration

Run migrations:
```bash
php artisan migrate
```

The new migration creates `attendance_responses` table, which works alongside the existing `attendance_details` table without conflicts.
