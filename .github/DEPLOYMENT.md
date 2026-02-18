# GitHub Actions Deployment to HestiaCP

## Overview

This repository is configured with GitHub Actions to automatically deploy the application to a HestiaCP server whenever code is pushed to the `main` branch.

## Workflow Details

The deployment workflow (`.github/workflows/deploy-to-hestiacp.yml`) performs the following steps:

1. **Checkout Code**: Retrieves the latest code from the repository
2. **Setup PHP**: Installs PHP 8.4 with required extensions
3. **Setup Node.js**: Installs Node.js 20 for asset compilation
4. **Install Dependencies**: Installs both Composer and NPM dependencies
5. **Build Assets**: Compiles frontend assets using Vite
6. **Create Archive**: Creates a deployment package excluding unnecessary files
7. **Deploy via SSH**: Transfers files to the HestiaCP server
8. **Setup Application**: Extracts files and runs Laravel optimization commands

## Required GitHub Secrets

To enable automatic deployment, you need to configure the following secrets in your GitHub repository:

### How to Add Secrets:
1. Go to your GitHub repository
2. Click on **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add each of the following secrets:

### Required Secrets:

| Secret Name | Description | Example |
|-------------|-------------|---------|
| `HESTIACP_HOST` | The IP address or hostname of your HestiaCP server | `192.168.1.100` or `server.example.com` |
| `HESTIACP_USERNAME` | SSH username for the server | `myuser` or `admin-myuser` |
| `HESTIACP_SSH_KEY` | Private SSH key for authentication | The content of your private key file |
| `HESTIACP_PORT` | SSH port (optional, defaults to 22) | `22` |
| `HESTIACP_APP_DIR` | Full path to the application directory on the server | `/home/myuser/web/example.com/public_html` |

## Setting up SSH Key Authentication

### 1. Generate SSH Key Pair (if you don't have one):

```bash
ssh-keygen -t rsa -b 4096 -C "github-actions@deploy"
```

This will create two files:
- `id_rsa` (private key) - Add this to GitHub Secrets as `HESTIACP_SSH_KEY`
- `id_rsa.pub` (public key) - Add this to your server

### 2. Add Public Key to HestiaCP Server:

```bash
# Copy the public key content
cat ~/.ssh/id_rsa.pub

# SSH into your server and add it to authorized_keys
ssh user@your-server
mkdir -p ~/.ssh
chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
# Paste the public key content
chmod 600 ~/.ssh/authorized_keys
```

### 3. Add Private Key to GitHub:

Copy the entire content of your private key file:
```bash
cat ~/.ssh/id_rsa
```

Add it to GitHub Secrets as `HESTIACP_SSH_KEY` (include the `-----BEGIN` and `-----END` lines).

## HestiaCP Application Directory

The `HESTIACP_APP_DIR` should point to your web application directory in HestiaCP. Common paths:

- **For public_html**: `/home/username/web/domain.com/public_html`
- **For subdomain**: `/home/username/web/subdomain.domain.com/public_html`

**Important**: Make sure the `public_html` directory is the root, and Laravel's `public` folder should be the document root in HestiaCP settings.

## Server Environment Setup

### Required on HestiaCP Server:

1. **PHP 8.4** (or compatible version)
2. **Composer** installed globally
3. **Required PHP extensions**: mbstring, xml, ctype, iconv, intl, pdo_mysql, dom, filter, gd, json, zip
4. **MySQL/MariaDB** database
5. **.env file** configured on the server

### Initial Server Setup:

After the first deployment, you need to:

1. **Create .env file** on the server:
```bash
cd /home/username/web/domain.com/public_html
cp .env.example .env
nano .env
# Configure your database and application settings
```

2. **Generate application key**:
```bash
php artisan key:generate
```

3. **Run initial migrations**:
```bash
php artisan migrate --force
```

4. **Set proper permissions**:
```bash
chmod -R 755 storage bootstrap/cache
chown -R username:username .
```

## HestiaCP Web Configuration

In your HestiaCP panel:

1. Go to **Web** → Select your domain
2. Click **Edit**
3. Set the **Document Root** to point to the `public` directory:
   - Example: `/home/username/web/domain.com/public_html/public`
4. Enable **PHP** (select PHP 8.4 if available)
5. Save changes

## Deployment Process

Once configured, deployment is automatic:

1. Make changes to your code
2. Commit and push to the `main` branch:
   ```bash
   git add .
   git commit -m "Your commit message"
   git push origin main
   ```
3. GitHub Actions will automatically:
   - Build the application
   - Deploy to your HestiaCP server
   - Run Laravel optimization commands

## Monitoring Deployments

1. Go to your GitHub repository
2. Click on the **Actions** tab
3. View the latest workflow runs
4. Click on a run to see detailed logs

## Troubleshooting

### Deployment fails with SSH connection error:
- Verify `HESTIACP_HOST`, `HESTIACP_USERNAME`, and `HESTIACP_PORT` are correct
- Ensure the SSH key is properly formatted in the secret
- Check if SSH access is allowed in HestiaCP firewall

### Permission errors on server:
```bash
cd /home/username/web/domain.com/public_html
chmod -R 755 storage bootstrap/cache
chown -R username:username .
```

### Application not loading after deployment:
- Check if `.env` file exists on the server
- Verify database credentials in `.env`
- Run: `php artisan config:clear && php artisan cache:clear`

### Migrations fail:
The workflow has migrations commented out by default. Uncomment this line in the workflow if you want automatic migrations:
```yaml
# php artisan migrate --force
```

## Manual Deployment

If you need to deploy manually:

```bash
ssh username@your-server
cd /home/username/web/domain.com/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Security Notes

- Never commit the `.env` file to the repository
- Keep your SSH private key secure and never share it
- Use strong passwords for database and server access
- Regularly update your server and application dependencies
- Enable HTTPS/SSL in HestiaCP for production

## Support

For issues related to:
- **GitHub Actions**: Check the Actions tab in your repository
- **HestiaCP**: Visit [HestiaCP Documentation](https://docs.hestiacp.com/)
- **Laravel**: Visit [Laravel Documentation](https://laravel.com/docs)
