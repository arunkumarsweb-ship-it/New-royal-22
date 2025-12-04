# Saree Availability Website - HTML/CSS/JS Version

This is a **pure HTML, CSS, and JavaScript** version of the saree availability website. No PHP or database required!

## Features

- ✅ **No Backend Required** - Works entirely in the browser
- ✅ **LocalStorage** - All data stored in browser's localStorage
- ✅ **Image Storage** - Images stored as base64 in localStorage
- ✅ **Full CRUD Operations** - Create, Read, Update, Delete products
- ✅ **Bulk Upload** - Upload multiple images at once
- ✅ **Responsive Design** - Works on mobile, tablet, and desktop
- ✅ **WhatsApp Integration** - Customer care WhatsApp button
- ✅ **Share Galleries** - Generate shareable links per category/day

## File Structure

```
/
├── index.html              # Home page
├── products.html           # Product listing page
├── share.html              # Shareable gallery view
├── admin.html              # Admin panel
├── css/
│   └── style.css           # All styles
├── js/
│   ├── data.js            # Data management (localStorage)
│   └── main.js            # Utility functions
└── README_HTML_VERSION.md  # This file
```

## How to Use

1. **Open the Website**
   - Simply open `index.html` in any modern web browser
   - No web server required!

2. **View Products**
   - Click on any category from the menu
   - Select a day (1-30)
   - View products with availability status

3. **Admin Panel**
   - Click "Admin Panel" link
   - Upload single or bulk products
   - Edit/Delete existing products

## Data Storage

All data is stored in **browser's localStorage**:
- Categories and subcategories (pre-initialized)
- Products (images stored as base64)
- WhatsApp configuration

### Important Notes:

⚠️ **Data is stored locally in your browser**
- Data will be lost if you clear browser cache
- Data is specific to each browser/device
- For production use, consider exporting/importing data

## Default Categories

The website comes with 7 pre-configured categories:
1. Poonam Sarees Without Blouse
2. Poonam Sarees With Blouse
3. Farak Sarees With Blouse
4. Farak Sarees Without Blouse
5. Designer Sarees
6. Accessories
7. Others

Each category has 30 subcategories (Day 1 to Day 30).

## WhatsApp Configuration

To change the WhatsApp number:
1. Open browser console (F12)
2. Run: `localStorage.setItem('whatsapp_config', JSON.stringify({number: 'YOUR_NUMBER', message: 'YOUR_MESSAGE'}))`
3. Refresh the page

Or edit `js/data.js` and change the default values in `initializeCategories()`.

## Browser Compatibility

- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile browsers

**Note**: localStorage has a size limit (~5-10MB). If you have many high-resolution images, consider compressing them before upload.

## Limitations

1. **Storage Limit**: Browser localStorage has size limits (~5-10MB)
2. **No Server**: Data is only stored locally
3. **No Sharing**: Data cannot be shared between devices/users
4. **Image Size**: Large images may cause performance issues

## Tips

- Compress images before uploading to save space
- Export data regularly (can be added as a feature)
- Use Chrome DevTools to inspect localStorage data
- Clear data: `localStorage.clear()` in console

## Future Enhancements

Possible additions:
- Export/Import data functionality
- Image compression before storage
- Data backup to file
- Multiple user support
- Cloud storage integration

## Support

For issues or questions, check the browser console for errors.

