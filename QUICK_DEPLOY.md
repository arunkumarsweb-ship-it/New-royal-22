# 🚀 Quick Deploy - Get Live in 5 Minutes!

## Easiest Method: Netlify (Recommended)

### Step 1: Prepare Files
Make sure you have these files ready:
- ✅ index.html
- ✅ products.html
- ✅ admin.html
- ✅ css/style.css
- ✅ js/data.js
- ✅ js/main.js

### Step 2: Deploy to Netlify

**Option A: Drag & Drop (Easiest)**
1. Go to https://app.netlify.com/drop
2. Drag your entire project folder
3. Wait 30 seconds
4. **Done!** You'll get a live URL

**Option B: Via Netlify Website**
1. Sign up at https://www.netlify.com (free)
2. Click "Add new site" → "Deploy manually"
3. Drag your folder
4. Get your live URL instantly!

### Step 3: Customize URL (Optional)
- Go to Site settings → Change site name
- Your URL: `https://your-site-name.netlify.app`

---

## Alternative: GitHub Pages

### Step 1: Create GitHub Repository
1. Go to https://github.com/new
2. Name: `saree-availability`
3. Make it **Public**
4. Click "Create repository"

### Step 2: Upload Files
```bash
# Open terminal in your project folder
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/saree-availability.git
git push -u origin main
```

### Step 3: Enable Pages
1. Go to repository → Settings
2. Scroll to "Pages"
3. Source: **main** branch
4. Click Save
5. Your site: `https://YOUR_USERNAME.github.io/saree-availability/`

---

## Test Before Deploying

### Local Test
1. Open `index.html` in browser
2. Test all features:
   - ✅ Navigation works
   - ✅ Products display
   - ✅ Admin panel works
   - ✅ Image upload works

### If Something Doesn't Work
- Open browser console (F12)
- Check for errors
- Make sure all file paths are correct

---

## After Deployment

### Share Your Site
- Copy your live URL
- Share with customers
- Add to social media

### Update WhatsApp Number
1. Open your live site
2. Open browser console (F12)
3. Run:
```javascript
localStorage.setItem('whatsapp_config', JSON.stringify({
    number: 'YOUR_WHATSAPP_NUMBER',
    message: 'Hello! I need help with saree availability.'
}));
```
4. Refresh page

---

## Need Help?

**Common Issues:**
- **404 Errors**: Check file paths (use relative paths)
- **Images not loading**: Check image paths
- **Data not saving**: Check browser localStorage is enabled

**Quick Fix:**
- Clear browser cache
- Check browser console for errors
- Verify all files uploaded correctly

---

## 🎉 You're Live!

Once deployed, your website will be accessible to anyone with the URL!

**Next Steps:**
- Share the URL with customers
- Test on mobile devices
- Add more products via admin panel
- Customize WhatsApp number

