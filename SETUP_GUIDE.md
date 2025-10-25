# Quick Setup Guide - Livewire Navigate

## Prerequisites

- PHP 8.2 or higher
- Composer
- Laravel 12.x
- Node.js (for asset compilation, if needed)

## Installation Steps

### 1. Clone the Repository
```bash
git clone <repository-url>
cd pdmlaranew
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment
```bash
cp .env.example .env  # If .env.example exists
php artisan key:generate
```

Configure your database and other settings in `.env`

### 4. Run Migrations
```bash
php artisan migrate --seed
```

### 5. Verify Livewire Installation
```bash
composer show livewire/livewire
```

You should see version 3.6.4 or higher.

## Testing Livewire Navigate

### Quick Test

1. Start the development server:
```bash
php artisan serve
```

2. Open your browser and navigate to `http://localhost:8000`

3. Login with appropriate credentials (admin or asesor role)

4. Test navigation:
   - Click on sidebar links (Dashboard, Attendance)
   - Notice that the page transitions are smooth without full page reloads
   - Check browser DevTools Network tab - you should see XHR/Fetch requests instead of full page loads

### Expected Behavior

✅ **Correct Implementation:**
- Sidebar remains visible during navigation
- Page content updates smoothly
- Browser back/forward buttons work correctly
- URL changes in address bar
- Page transitions are faster than traditional navigation

❌ **Incorrect Setup (if you see these):**
- Full page reload with white flash
- Sidebar disappears and reappears
- Slow page transitions
- Console errors about Livewire

## Troubleshooting Setup

### Issue: "Class 'Livewire' not found"

**Solution:**
```bash
composer dump-autoload
php artisan clear-compiled
php artisan config:clear
```

### Issue: Livewire styles/scripts not loading

**Solution:**
1. Clear browser cache
2. Verify `@livewireStyles` is in `<head>`
3. Verify `@livewireScripts` is before `</body>`
4. Check browser console for errors

### Issue: Navigation not working (full page reload)

**Solution:**
1. Ensure you're using `wire:navigate` on links
2. Check that links are pointing to Laravel routes (not external URLs)
3. Clear Laravel cache: `php artisan cache:clear`

### Issue: 404 errors on navigation

**Solution:**
1. Run `php artisan route:list` to verify routes exist
2. Check that route names match in `wire:navigate` links
3. Verify middleware and authentication

## Verification Checklist

Before considering the setup complete, verify:

- [ ] Livewire package is installed (check `composer.json`)
- [ ] `@livewireStyles` is in layout's `<head>` section
- [ ] `@livewireScripts` is before closing `</body>` tag
- [ ] Sidebar links have `wire:navigate` attribute
- [ ] Navigation works without full page reload
- [ ] Browser DevTools shows XHR requests during navigation
- [ ] Back/forward browser buttons work correctly
- [ ] Active menu items are highlighted correctly

## Performance Check

Use browser DevTools to measure:

1. **Before Livewire Navigate:**
   - Full page load: ~500-1000ms
   - Multiple HTTP requests for all assets
   - White flash between pages

2. **After Livewire Navigate:**
   - Partial update: ~50-200ms
   - Minimal HTTP requests (only content)
   - Smooth transitions

## Development Tips

### Debugging

Enable Livewire debug mode in `.env`:
```env
LIVEWIRE_ASSET_URL=null
APP_DEBUG=true
```

### Cache Commands

When making changes:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Browser Testing

Test in multiple browsers:
- Chrome/Edge (Chromium)
- Firefox
- Safari

## Next Steps

Once setup is complete:

1. Review [LIVEWIRE_NAVIGATE.md](LIVEWIRE_NAVIGATE.md) for detailed usage
2. Consider converting static pages to Livewire components
3. Implement real-time features using Livewire polling
4. Add loading indicators for better UX

## Common Commands

```bash
# Clear all caches
php artisan optimize:clear

# List all routes
php artisan route:list

# Check Livewire components
php artisan livewire:list

# Run development server
php artisan serve

# Run tests
php artisan test
```

## Support

For issues specific to:
- **Livewire**: [Livewire Documentation](https://livewire.laravel.com/docs)
- **Laravel**: [Laravel Documentation](https://laravel.com/docs)
- **This Project**: Check existing documentation or create an issue

---

**Note**: This guide assumes you have a working Laravel development environment. If you encounter PHP or Composer issues, ensure your system meets all Laravel 12.x requirements.
