# vCard Template Editor - Testing Guide

## 🎯 New Features to Test

### 1. **Field Configuration System** (`_field_config`)

Templates with enhanced config:

- ✅ `doctor-clinic-template` (Pilot - Full implementation)
- ✅ `mens-salon-template` (Services, Barbers, Products)
- ✅ `restaurant-cafe-template` (Menu items)

---

## 📋 Test Cases

### Test 1: Doctor Template - Professional Fields

**Path:** `/admin/vcards/{vcard_id}/data/doctor`

**What to Look For:**

- ✅ **Custom Labels** - "Doctor Name" instead of "Name"
- ✅ **Placeholder Text** - Gray hint text in empty fields (e.g., "Dr. John Doe")
- ✅ **Help Tooltips** - `?` icon next to labels, hover to see help text
- ✅ **Character Counters** - Shows "X / 250 characters" for Qualifications field
- ✅ **Field Types**:
    - Text inputs with validation
    - Textareas with counter (Qualifications, Address)
    - Email field (proper validation)
    - Phone field (tel input)
    - URL field (Website, Maps)

**Expected Behavior:**

1. Edit "Doctor Name" → See placeholder "Dr. John Doe"
2. Hover over `?` icon → Tooltip shows "Full name with title (Dr., Prof., etc.)"
3. Type in "Qualifications" textarea → Counter updates live: "150 / 250 characters"
4. Fill all fields → Labels are clear and professional

---

### Test 2: Salon Template - Services Section

**Path:** `/admin/vcards/{vcard_id}/data/services` (Mens Salon vCard)

**What to Look For:**

- ✅ **Service-Specific Labels** - "Service Name", "Duration", etc.
- ✅ **Help Text** - Each field has contextual help
- ✅ **Character Counter** - Description field shows "X / 200 characters"
- ✅ **Placeholder Examples** - "Haircut, Fade, Beard Trim, etc."
- ✅ **Image Upload** - Help text: "Upload an image for this service (500x500px recommended)"

**Steps:**

1. Click "Add Service" button
2. Fill "Service Name" → Placeholder shows "Haircut, Fade, Beard Trim, etc."
3. Fill "Description" → Character counter appears
4. Type 150 characters → Counter shows "150 / 200 characters"
5. Upload image → See help text about recommended size

---

### Test 3: Restaurant Template - Menu Items

**Path:** `/admin/vcards/{vcard_id}/data/MENU/Starters` (Restaurant vCard)

**What to Look For:**

- ✅ **Toggle Switch** - "Vegetarian" field shows as switch (not text input)
- ✅ **Number Inputs** - Price fields accept only numbers
- ✅ **Character Counter** - Description limited to 150 chars with live counter
- ✅ **Optional Fields** - "Tag" and "Original Price" marked as optional

**Steps:**

1. Edit existing menu item or add new
2. Fill "Dish Name" → See placeholder
3. Enter description → Watch character counter: "X / 150 characters"
4. Toggle "Vegetarian" switch → Should flip on/off smoothly
5. Enter prices → Number-only inputs

---

### Test 4: Drag & Drop Reordering

**Path:** Any list/table section (Services, Menu, Barbers, etc.)

**What to Look For:**

- ✅ **Drag Handle** - Vertical grip icon (⋮⋮) in first column
- ✅ **Cursor Change** - Changes to grab/grabbing cursor
- ✅ **Ghost Effect** - Row becomes semi-transparent while dragging
- ✅ **Smooth Animation** - Rows shift smoothly
- ✅ **Auto-Save** - New order saves automatically

**Steps:**

1. Go to any section with list table (e.g., Services)
2. Click and hold the drag handle (⋮⋮ icon)
3. Drag row up or down
4. Release → Other rows shift to make space
5. Check order numbers update (#1, #2, #3...)

**Known Note:** Full drag-drop implementation requires Livewire method. Tables still have up/down arrow buttons that work.

---

### Test 5: Date/Time Picker (Future Use)

**Path:** Booking sections or appointment fields

**What to Look For:**

- ✅ **Calendar Widget** - Clicking date field opens Flatpickr calendar
- ✅ **Time Selector** - 24-hour format time picker
- ✅ **Clean UI** - Modern date picker with month/year navigation
- ✅ **Keyboard Support** - Can type dates manually

**To Test:**

1. Add a field with `"type": "date"` in config
2. Click the field → Calendar popup appears
3. Select date → Field populates in format "Feb 28, 2026"
4. Try time field → 24-hour time selector with hour/minute

**Note:** Date/time fields require explicit `"type": "date"` in `_field_config`. Not auto-detected yet.

---

### Test 6: Tooltips & Help Text

**Path:** Any section with `_field_config` (Doctor, Services, Menu)

**What to Look For:**

- ✅ **Help Icon** - Small `?` icon appears next to labels
- ✅ **Hover Tooltip** - Bootstrap tooltip shows help text
- ✅ **Positioning** - Tooltip doesn't get cut off by screen edges
- ✅ **Readable** - Dark background, white text, good contrast

**Steps:**

1. Find field with help text (e.g., "Doctor Name")
2. Hover mouse over `?` icon
3. Tooltip appears: "Full name with title (Dr., Prof., etc.)"
4. Move to different field → New tooltip shows
5. Check multiple fields have unique, helpful tooltips

---

### Test 7: Character Counter Live Update

**Path:** Any textarea with `"showCounter": true` (Doctor Qualifications, Service Description)

**What to Look For:**

- ✅ **Initial Count** - Shows "0 / 250 characters" on empty field
- ✅ **Live Update** - Updates as you type WITHOUT page refresh
- ✅ **Accurate** - Count matches actual characters typed
- ✅ **Visual Position** - Appears below textarea, small gray text

**Steps:**

1. Click into "Qualifications" field (Doctor section)
2. Type "MBBS" → Counter shows "4 / 250 characters"
3. Keep typing → Counter updates live
4. Reach limit (250 chars) → Can't type more
5. Delete text → Counter decreases

---

### Test 8: Image Upload with Help Text

**Path:** Any image field (Profile Image, Service Image, Product Image)

**What to Look For:**

- ✅ **Clear Label** - E.g., "Service Image" instead of "Product Image"
- ✅ **Help Text** - Shows recommended dimensions (500x500px)
- ✅ **Preview** - Existing image shows as thumbnail
- ✅ **Loading Indicator** - Spinner appears during upload
- ✅ **Success** - New image replaces old preview

**Steps:**

1. Find image upload field
2. Read help text → Should say dimensions/format
3. Click "Choose File" → Select image
4. Watch spinner → "Uploading image..."
5. Preview updates → New image appears

---

## 🔍 Visual Inspection Checklist

### Overall UI Quality

- [ ] All labels are clear and professional (not auto-generated gibberish)
- [ ] Placeholders provide helpful examples
- [ ] Help tooltips are informative, not redundant
- [ ] Character counters are visible and update smoothly
- [ ] Form feels organized and not overwhelming
- [ ] Color scheme is consistent (Bootstrap default)

### Field Types Working

- [ ] Text inputs accept text
- [ ] Number inputs reject letters
- [ ] Email validation works (invalid format shows error)
- [ ] URL fields validate URLs
- [ ] Textareas expand properly
- [ ] Toggle switches flip smoothly
- [ ] Image uploads show progress

### Responsiveness

- [ ] Forms work on mobile view (Bootstrap responsive)
- [ ] Tooltips don't go off-screen
- [ ] Long labels wrap properly
- [ ] Tables scroll horizontally if needed

---

## 🐛 Known Issues / Limitations

### Drag-Drop Implementation

- ⚠️ **Partial Implementation** - Visual drag handle added, SortableJS loaded
- ⚠️ **Missing Backend** - Livewire `reorderRows` method not yet implemented
- ✅ **Workaround** - Up/Down arrow buttons still work for reordering

### Date/Time Pickers

- ⚠️ **Manual Config Required** - Fields need explicit `"type": "date"` in `_field_config`
- ⚠️ **Not Auto-Detected** - Won't appear on fields named "date" unless configured
- ✅ **Works When Configured** - Flatpickr library loaded and functional

### Field Config Scope

- ⚠️ **Template-Specific** - Each template needs its own `_field_config` setup
- ⚠️ **Not All Templates Updated** - Only 3 templates have config (can add more as needed)
- ✅ **Backward Compatible** - Templates without config still work (auto-detection fallback)

---

## ✅ Success Criteria

**Feature works if:**

1. **Labels & Placeholders** - All fields show custom labels and helpful placeholders (not "Name", "Desc")
2. **Help Tooltips** - Hover over `?` icons shows contextual help text
3. **Character Counters** - Live counters appear on configured textareas and update as you type
4. **Field Types** - Toggle switches, number inputs, email/url validation all work correctly
5. **Professional UX** - Forms feel intuitive, organized, and easy to fill out

**Not blockers:**

- Drag-drop visual works but doesn't save (backend method missing)
- Date pickers only on explicitly configured fields
- Only 3 templates have full config (others have fallback auto-detection)

---

## 📝 Testing Notes Template

Use this to document your testing:

```
## Test Date: ___________
## Tester: ___________

### Doctor Template (doctor-clinic)
- [ ] Custom labels visible (Doctor Name, Qualifications, etc.)
- [ ] Help tooltips appear on hover
- [ ] Character counter on Qualifications field: YES / NO
- [ ] Character counter updates live: YES / NO
- [ ] Email/URL validation works: YES / NO
- [ ] Overall professional feel: 1-5 stars

### Salon Template (mens-salon)
- [ ] Service section has custom config
- [ ] Description field shows counter (X / 200)
- [ ] Counter updates as typing: YES / NO
- [ ] Image uploads work properly: YES / NO

### Restaurant Template (restaurant-cafe)
- [ ] Vegetarian toggle switch appears: YES / NO
- [ ] Toggle works smoothly: YES / NO
- [ ] Price fields accept only numbers: YES / NO
- [ ] Description counter (X / 150): YES / NO

### Drag & Drop
- [ ] Drag handles visible (⋮⋮ icon): YES / NO
- [ ] Cursor changes to grab: YES / NO
- [ ] Row becomes transparent when dragging: YES / NO
- [ ] Animation smooth: YES / NO
- [ ] Order saves after drag: YES / NO / PARTIAL

### Issues Found:
1.
2.
3.

### Suggestions:
1.
2.
3.
```

---

## 🚀 Next Steps After Testing

Based on test results:

1. **If All Features Work:**
    - Add `_field_config` to remaining 6 templates
    - Implement backend drag-drop save method
    - Add date/time fields where needed (bookings, appointments)

2. **If Issues Found:**
    - Document specific fields that don't work
    - Check browser console for JavaScript errors
    - Verify Flatpickr and SortableJS libraries loaded

3. **Enhancement Ideas:**
    - WYSIWYG editor for long descriptions
    - Icon picker for service icons
    - Tags input for skills/keywords
    - Multi-select for categories

---

## 💡 Pro Tips

1. **Clear Browser Cache** - Press Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows) to force refresh
2. **Check Console** - Press F12 → Console tab to see JavaScript errors
3. **Test in Different Browsers** - Try Chrome, Safari, Firefox
4. **Mobile Testing** - Use browser dev tools to test responsive view
5. **Compare Before/After** - Edit a template without `_field_config` vs one with config

---

**Templates with Full Config:**

- ✅ doctor-clinic-template
- ✅ mens-salon-template
- ✅ restaurant-cafe-template

**Templates with Auto-Detection Only (Still Work):**

- bookshop-template
- coaching-template
- electronics-shop-template
- jewelry-shop-template
- minimart-template
- sweetshop-template

Happy Testing! 🎉
