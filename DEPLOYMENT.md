# Deployment Guide - Go Live

This guide will help you deploy your Saree Availability website online.

## Quick Deploy Options

### Option 1: HTML Version (Easiest - Recommended)

The HTML/CSS/JS version can be deployed to free hosting services instantly.

#### A. GitHub Pages (Free)

1. **Create GitHub Account** (if you don't have one)
   - Go to https://github.com

2. **Create New Repository**
   - Click "New repository"
   - Name it: `saree-availability`
   - Make it Public
   - Don't initialize with README

3. **Upload Files**
   ```bash
   # In your project folder, run:
   git init
   git add index.html products.html admin.html css/ js/ README_HTML_VERSION.md
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin https://github.com/YOUR_USERNAME/saree-availability.git
   git push -u origin main
   ```

4. **Enable GitHub Pages**
   - Go to repository Settings
   - Scroll to "Pages" section
   - Source: Select "main" branch
   - Click Save
   - Your site will be live at: `https://YOUR_USERNAME.github.io/saree-availability/`

#### B. Netlify (Free - Easiest)

1. **Go to https://www.netlify.com**
2. **Sign up** (free account)
3. **Drag and Drop** your project folder
4. **Done!** Your site is live instantly
5. You'll get a URL like: `https://random-name-123.netlify.app`

#### C. Vercel (Free)

1. **Go to https://vercel.com**
2. **Sign up** (free account)
3. **Import Project** → Upload folder
4. **Deploy** - Done!

---

### Option 2: PHP Version (Requires Hosting)

For the PHP version, you need a web server with PHP and MySQL.

#### Recommended Hosting Providers:

1. **cPanel Hosting** (Most common)
   - Bluehost, HostGator, SiteGround, etc.
   - Usually $3-10/month
   - Includes PHP, MySQL, cPanel

2. **Free PHP Hosting**
   - 000webhost.com (free)
   - InfinityFree.net (free)
   - Note: Free hosts have limitations

#### Steps for PHP Deployment:

1. **Upload Files via FTP/cPanel**
   - Upload all files to `public_html` folder
   - Keep folder structure intact

2. **Create Database**
   - Login to cPanel
   - Go to "MySQL Databases"
   - Create new database: `saree_availability`
   - Create database user
   - Grant all privileges

3. **Update Database Config**
   - Edit `config/database.php`
   - Update with your database credentials

4. **Import Database**
   - Go to phpMyAdmin in cPanel
   - Select your database
   - Import `database/schema.sql`

5. **Set Permissions**
   - Make `uploads/products/` folder writable (chmod 755)

6. **Test**
   - Visit your domain
   - Should work!

---

## Pre-Deployment Checklist

### For HTML Version:
- [x] All HTML files created
- [x] CSS file included
- [x] JavaScript files included
- [x] Test locally first
- [ ] Compress images if needed (localStorage limit)

### For PHP Version:
- [x] Database schema ready
- [x] Config file ready
- [ ] Update database credentials
- [ ] Test database connection
- [ ] Set uploads folder permissions

---

## Quick Start Commands

### Test Locally (HTML Version)
```bash
# Just open index.html in browser
# Or use Python simple server:
python -m http.server 8000
# Then visit: http://localhost:8000
```

### Test Locally (PHP Version)
```bash
# Using PHP built-in server:
php -S localhost:8000
# Then visit: http://localhost:8000
```

---

## Custom Domain (Optional)

After deploying, you can add a custom domain:

1. **Buy Domain** (Namecheap, GoDaddy, etc.)
2. **Point DNS** to your hosting
3. **Configure** in hosting panel

---

## Need Help?

- **HTML Version Issues**: Check browser console (F12)
- **PHP Version Issues**: Check server error logs
- **Database Issues**: Verify credentials in config file

---

## Recommended: Start with HTML Version

The HTML version is easiest to deploy:
- ✅ No server needed
- ✅ Free hosting available
- ✅ Works instantly
- ✅ No database setup

You can always upgrade to PHP version later if needed!

