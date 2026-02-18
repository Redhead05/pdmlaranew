# Quick Setup Guide - GitHub Actions Deployment

## Step-by-Step Setup

### 1. Generate SSH Key Pair

On your local machine:
```bash
ssh-keygen -t rsa -b 4096 -C "github-deploy" -f hestiacp-deploy-key
```

This creates two files:
- `hestiacp-deploy-key` (private key)
- `hestiacp-deploy-key.pub` (public key)

### 2. Add Public Key to HestiaCP Server

```bash
# View the public key
cat hestiacp-deploy-key.pub

# SSH into your HestiaCP server
ssh your-username@your-server-ip

# Add the public key
mkdir -p ~/.ssh
chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
# Paste the public key content and save (Ctrl+O, Enter, Ctrl+X)
chmod 600 ~/.ssh/authorized_keys
exit
```

### 3. Test SSH Connection

```bash
ssh -i hestiacp-deploy-key your-username@your-server-ip
```

If successful, you can connect without a password.

### 4. Add GitHub Secrets

Go to: `https://github.com/YOUR-USERNAME/pdmlaranew/settings/secrets/actions`

Add these secrets by clicking "New repository secret":

**HESTIACP_HOST**
```
your-server-ip-or-domain
```

**HESTIACP_USERNAME**
```
your-username
```

**HESTIACP_SSH_KEY**
```bash
# Copy the entire private key content (including BEGIN and END lines)
cat hestiacp-deploy-key
```
Paste the entire content including:
```
-----BEGIN OPENSSH PRIVATE KEY-----
...
-----END OPENSSH PRIVATE KEY-----
```

**HESTIACP_PORT** (optional, use only if not 22)
```
22
```

**HESTIACP_APP_DIR**
```
/home/your-username/web/your-domain.com/public_html
```

### 5. Configure HestiaCP Web Settings

1. Login to HestiaCP panel
2. Go to **Web** section
3. Select your domain
4. Click **Edit**
5. Change **Document Root** to:
   ```
   /home/your-username/web/your-domain.com/public_html/public
   ```
6. Select PHP version (8.1 or higher)
7. Save changes

### 6. Prepare Server Environment

SSH into your server:
```bash
ssh your-username@your-server-ip
cd /home/your-username/web/your-domain.com/public_html

# Create .env file
nano .env
```

Add your environment configuration:
```env
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# ... other configurations
```

Generate application key:
```bash
php artisan key:generate
```

Run migrations:
```bash
php artisan migrate --force
```

Set permissions:
```bash
chmod -R 755 storage bootstrap/cache
```

### 7. Deploy!

Push to main branch:
```bash
git checkout main
git pull origin copilot/set-up-main-branch-for-deploy
# or merge your PR
git push origin main
```

The deployment will automatically start!

### 8. Monitor Deployment

Go to: `https://github.com/YOUR-USERNAME/pdmlaranew/actions`

Click on the latest workflow run to see the deployment progress.

## Troubleshooting

### SSH Permission Denied
- Verify the private key is correct in GitHub Secrets
- Check `~/.ssh/authorized_keys` on the server has the public key
- Ensure `.ssh` directory permissions are 700 and `authorized_keys` is 600

### Deployment Files Not Updating
- Check the `HESTIACP_APP_DIR` path is correct
- Verify file permissions on the server
- Check workflow logs for errors

### Application Not Loading
- Verify `.env` file exists and is configured
- Check HestiaCP document root points to `/public`
- Clear cache: `php artisan config:clear && php artisan cache:clear`

### Database Errors
- Verify database credentials in `.env`
- Ensure database exists in HestiaCP
- Run migrations: `php artisan migrate --force`

## Security Reminders

- ✅ Never commit `.env` file
- ✅ Keep SSH private key secure
- ✅ Use strong database passwords
- ✅ Enable HTTPS/SSL in HestiaCP
- ✅ Regularly update dependencies
