# Livewire Navigate Implementation Guide

## Overview

This project now uses Livewire 3.x with the `wire:navigate` feature to provide SPA-like (Single Page Application) navigation without requiring a full JavaScript framework. This enables faster page transitions and better user experience by loading only the content that changes, while keeping the sidebar and header intact.

## What is Livewire Navigate?

Livewire Navigate is a feature that intercepts link clicks and performs partial page updates instead of full page reloads. It's similar to Turbo/Turbolinks but built directly into Livewire 3.

### Benefits:
- **Faster Navigation**: Only the content area reloads, not the entire page
- **Preserved State**: Sidebar, header, and other layout elements remain unchanged
- **Better UX**: Smooth transitions between pages without full page flashes
- **SEO Friendly**: Pages still work with JavaScript disabled (progressive enhancement)
- **No Build Step**: No need for complex JavaScript bundlers or frameworks

## Installation

Livewire 3.6.4 is already installed in this project via Composer:

```bash
composer require livewire/livewire
```

## Configuration

### 1. Layout Setup

The main app layout (`resources/views/app/layout.blade.php`) has been configured with Livewire directives:

```blade
<html>
<head>
    <!-- Other head content -->
    @livewireStyles
</head>
<body>
    <!-- Your content -->
    @livewireScripts
</body>
</html>
```

**Important**: 
- `@livewireStyles` must be placed in the `<head>` section
- `@livewireScripts` must be placed before the closing `</body>` tag

### 2. Navigation Links

To enable partial page loading, add the `wire:navigate` attribute to your links:

**Before:**
```blade
<a href="{{ route('admin.dashboard') }}" class="menu-link">
    Dashboard
</a>
```

**After:**
```blade
<a href="{{ route('admin.dashboard') }}" wire:navigate class="menu-link">
    Dashboard
</a>
```

## Current Implementation

### Files Modified:

1. **resources/views/app/layout.blade.php**
   - Added `@livewireStyles` in head
   - Added `@livewireScripts` before closing body tag

2. **resources/views/partial/sidebar.blade.php**
   - Added `wire:navigate` to all main navigation links
   - Applied to both Admin and Asesor role menus:
     - Dashboard links
     - Attendance links

3. **resources/views/menu/admin/attendance/index.blade.php**
   - Added `wire:navigate` to attendance detail links

4. **resources/views/menu/admin/attendance/detail.blade.php**
   - Added `wire:navigate` to back button

## Usage Guidelines

### When to Use `wire:navigate`

✅ **Use for:**
- Internal application links
- Navigation between dashboard pages
- Links that go to Laravel routes
- Back buttons and navigation buttons
- CRUD operation navigation

❌ **Don't use for:**
- External links (e.g., `https://external-site.com`)
- Links that trigger modals or popups
- Form submissions (use Livewire forms instead)
- Download links
- Links with `target="_blank"`
- JavaScript `void(0)` links

### Examples

#### Dashboard Navigation
```blade
<a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a>
<a href="{{ route('admin.attendance.index') }}" wire:navigate>Attendance</a>
```

#### CRUD Operations
```blade
<!-- List to detail -->
<a href="{{ route('admin.attendance.detail', $item->slug) }}" wire:navigate>
    View Details
</a>

<!-- Back to list -->
<a href="{{ route('admin.attendance.index') }}" wire:navigate>
    Back to List
</a>
```

#### Pagination
```blade
<a href="?page=2" wire:navigate>Next Page</a>
```

## JavaScript Considerations

### Page Scripts

If you have page-specific JavaScript that needs to run on each navigation, wrap it in an event listener:

```javascript
document.addEventListener('livewire:navigated', () => {
    // Your initialization code here
    console.log('Page navigated');
    
    // Re-initialize plugins
    initDataTables();
    initTooltips();
});
```

### Existing Scripts

For already loaded pages on initial visit:
```javascript
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});

// Also listen for Livewire navigation
document.addEventListener('livewire:navigated', () => {
    initializeApp();
});
```

## Advanced Features

### Prefetching Links

Add `wire:navigate.hover` to prefetch pages when hovering over links:

```blade
<a href="{{ route('admin.dashboard') }}" wire:navigate.hover>
    Dashboard
</a>
```

### Scroll to Top

By default, Livewire Navigate scrolls to top on navigation. To preserve scroll position:

```blade
<a href="{{ route('admin.dashboard') }}" wire:navigate data-navigate-no-scroll>
    Dashboard
</a>
```

### Progress Bar

Livewire includes a built-in progress bar for longer requests. You can customize it:

```javascript
// Custom progress bar colors
Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
    // Your custom logic
});
```

## Troubleshooting

### Issue: Scripts not running after navigation

**Solution**: Use the `livewire:navigated` event:
```javascript
document.addEventListener('livewire:navigated', () => {
    // Re-initialize your scripts
});
```

### Issue: Styles not loading

**Solution**: Ensure `@livewireStyles` is in the `<head>` section

### Issue: Navigation not working

**Solution**: 
1. Check that `@livewireScripts` is before closing `</body>`
2. Clear browser cache
3. Make sure the link is an internal route

### Issue: Modal/Dropdown closes after navigation

**Solution**: This is expected behavior with SPA navigation. Consider using Livewire components for modals and dropdowns.

## Testing

To verify the implementation is working:

1. Open browser DevTools (F12)
2. Go to Network tab
3. Navigate between pages using the sidebar
4. You should see:
   - Fewer HTTP requests
   - Smaller response sizes
   - Faster page loads
   - XHR/Fetch requests instead of full page loads

## Performance Tips

1. **Use wire:navigate.hover** for frequently accessed pages to prefetch content
2. **Keep layouts consistent** to maximize the benefits of partial rendering
3. **Minimize JavaScript initialization** by using event listeners properly
4. **Cache static assets** to reduce load times

## Resources

- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Livewire Navigate Guide](https://livewire.laravel.com/docs/navigate)
- [Laravel Documentation](https://laravel.com/docs)

## Version Information

- **Laravel**: 12.x
- **Livewire**: 3.6.4
- **PHP**: 8.2+

## Next Steps

Consider implementing:
1. Livewire components for dynamic content
2. Real-time updates with Livewire Polling
3. Form handling with Livewire forms
4. Modal dialogs with Livewire modal components

---

**Note**: This implementation provides a solid foundation for SPA-like navigation. You can further enhance it by converting more static pages to Livewire components as needed.
