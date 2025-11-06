# Testing Guide - CCS Payment Monitoring System

## 🧪 COMPLETE TESTING CHECKLIST

### 1. Profile Photo Upload Testing

#### Test 1: Upload Valid Photo
```
Steps:
1. Login as any role
2. Click Profile → Edit
3. Click "Choose File" under Profile Photo
4. Select JPG/PNG image (<2MB)
5. Click "Upload Photo"

Expected:
✓ Success message appears
✓ Photo displays in circular format
✓ Photo shows in sidebar immediately
✓ Photo shows in top-right dropdown
✓ File saved to storage/app/public/profile_photos/
```

#### Test 2: Upload Invalid File
```
Steps:
1. Try uploading PDF or .exe file
Expected: ✗ Validation error "Must be an image"

2. Try uploading 5MB image
Expected: ✗ Validation error "Max 2MB"
```

#### Test 3: Delete Photo
```
Steps:
1. Upload a photo first
2. Click "Remove" button
3. Confirm deletion

Expected:
✓ Photo removed from database
✓ Default avatar (initials) shown instead
✓ File deleted from storage folder
```

---

### 2. Profile Update Testing

#### Test 4: Student Profile Edit
```
Login as Student:

Editable Fields:
✓ Full Name
✓ Contact Number
✓ Guardian Name
✓ Guardian Contact
✓ Address
✓ Password

Read-Only Fields:
✗ Email (grayed out)
✗ Student ID (grayed out)
✗ Block (grayed out)

Test:
1. Update contact number to "09171234567"
2. Update guardian name
3. Click "Save Changes"

Expected: ✓ Success message, data saved
```

#### Test 5: Admin Profile Edit
```
Login as Admin:

All Fields Editable:
✓ Name
✓ Email
✓ Student ID
✓ Block
✓ Contact
✓ Guardian info
✓ Address

Test:
1. Change own email
2. Update block assignment
3. Save

Expected: ✓ All changes saved, email verification reset
```

#### Test 6: Password Change
```
For All Roles:

Steps:
1. Scroll to "Change Password" section
2. Enter current password
3. Enter new password (min 8 chars)
4. Confirm new password
5. Click "Update Password"

Expected: ✓ Password changed, can login with new password

Negative Test:
- Try 6-character password: ✗ Error "Minimum 8 characters"
- Try wrong current password: ✗ Error "Current password incorrect"
- Try mismatched passwords: ✗ Error "Passwords don't match"
```

---

### 3. Treasurer Block Filtering Testing

#### Test 7: Treasurer Dashboard Filtering
```
Setup:
1. Create Block "A" and Block "B"
2. Create Treasurer assigned to Block "A"
3. Create 3 students in Block "A"
4. Create 3 students in Block "B"
5. Record payments for both blocks

Test:
1. Login as Block "A" Treasurer
2. View dashboard

Expected Results:
✓ "Active Students" count = 3 (Block A only)
✓ Recent payments show ONLY Block A students
✓ Total collected shows ONLY Block A payments
✓ Cannot see Block B students anywhere

Verify:
- Dashboard stats filtered correctly
- Recent payments table shows Block A only
- Student dropdowns (if any) show Block A only
```

#### Test 8: Treasurer Without Block
```
Setup:
1. Create treasurer without block assignment

Test:
1. Login as this treasurer
2. View dashboard

Expected:
✓ Shows all students (fallback behavior)
✓ No filtering applied
✓ System doesn't crash
```

---

### 4. Payment Method Restriction Testing

#### Test 9: Payment Recording
```
Steps:
1. Login as Admin or Treasurer
2. Navigate to payment record form
3. Look at payment method dropdown

Expected Options ONLY:
✓ Cash
✓ GCash
✓ Maya
✓ PayPal

Should NOT see:
✗ Check
✗ Bank Transfer
✗ Online (generic)
```

#### Test 10: Payment Edit Validation
```
Manual Test (if API exposed):

Try sending:
POST /admin/payments/{id}
{
  "payment_method": "bitcoin"
}

Expected: ✗ 422 Validation Error
```

---

### 5. Logo Display Testing

#### Test 11: Logo Circular Display
```
Check these locations:

1. Login Page:
   ✓ Logo is circular (rounded-full)
   ✓ Logo has white background
   
2. Registration Page:
   ✓ Logo is circular
   ✓ Background watermark visible (500px, 20% opacity, right side)
   
3. Sidebar:
   ✓ Logo is circular with border
   ✓ Logo width/height = 40px
   
4. Dropdown Menu:
   ✓ Profile photo (if uploaded) is circular
   ✓ Default avatar (if no photo) is circular
```

#### Test 12: Logo File Support
```
Test:
1. Delete ccs-logo.jpg
2. Upload ccs-logo.png
3. Refresh pages

Expected: ✓ PNG logo displays correctly

Test:
1. Delete ccs-logo.png
2. Keep only ccs-logo.jpg
3. Refresh pages

Expected: ✓ JPG logo displays correctly
```

---

### 6. Role-Based Access Control

#### Test 13: Student Access
```
Login as Student:

Accessible:
✓ Student dashboard
✓ Profile edit
✓ Payment history (own)

NOT Accessible:
✗ Admin dashboard (403 Forbidden)
✗ User management (403 Forbidden)
✗ Other students' data (403 Forbidden)
```

#### Test 14: Treasurer Access
```
Login as Treasurer:

Accessible:
✓ Treasurer dashboard
✓ Record payments (block students only)
✓ View payments (block students only)
✓ Profile edit

NOT Accessible:
✗ Admin settings (403 Forbidden)
✗ User management (403 Forbidden)
✗ Other blocks' students (filtered out)
```

#### Test 15: Admin Access
```
Login as Admin:

Accessible:
✓ Everything
✓ All dashboards
✓ User management
✓ All students
✓ All payments
✓ Reports
✓ Settings
```

---

### 7. Mobile Responsiveness Testing

#### Test 16: Mobile Layout
```
Devices to Test:
- iPhone SE (375px width)
- iPhone 12 Pro (390px width)
- iPad (768px width)
- Android phones (360px width)

Check:
✓ Sidebar collapses to hamburger menu
✓ Logo stays visible
✓ Forms are usable
✓ Tables scroll horizontally
✓ Buttons are tap-friendly (44px min)
✓ Text is readable (16px min)
✓ Profile photo upload works
```

---

### 8. Security Testing

#### Test 17: Authentication
```
Test:
1. Logout
2. Try accessing: /admin/dashboard directly

Expected: ✗ Redirect to login

3. Login as Student
4. Try accessing: /admin/users

Expected: ✗ 403 Forbidden
```

#### Test 18: CSRF Protection
```
Test:
Try submitting form without CSRF token

Expected: ✗ 419 Page Expired error
```

#### Test 19: File Upload Security
```
Test:
1. Try uploading .php file as profile photo
Expected: ✗ Validation error

2. Try uploading file >2MB
Expected: ✗ Validation error

3. Check uploaded file:
Expected: ✓ Stored in profile_photos/ folder only
```

---

### 9. Database Testing

#### Test 20: Data Integrity
```
Check:
1. Students without users: Should not exist
2. Payments without students: Should not exist
3. Orphaned profile photos: Should be cleaned up
4. Duplicate student IDs: Should not exist

SQL Checks:
SELECT * FROM students WHERE user_id NOT IN (SELECT id FROM users);
-- Expected: 0 rows

SELECT * FROM payments WHERE student_id NOT IN (SELECT id FROM students);
-- Expected: 0 rows
```

---

### 10. Performance Testing

#### Test 21: Load Testing
```
Test:
1. Create 1000 students
2. Create 5000 payments
3. Test dashboard load time

Expected:
✓ Dashboard loads < 2 seconds
✓ Tables paginated properly
✓ Queries use indexes
✓ No N+1 query problems

Use Laravel Debugbar to check:
- Query count (should be < 20)
- Memory usage (should be < 50MB)
```

---

## 🔧 QUICK TEST COMMANDS

### Run PHPUnit Tests
```bash
php artisan test
```

### Check for Errors
```bash
# View logs
tail -f storage/logs/laravel.log

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Database Checks
```bash
# Check migrations
php artisan migrate:status

# Seed test data
php artisan db:seed

# Reset and seed
php artisan migrate:fresh --seed
```

---

## 📊 TEST RESULTS TEMPLATE

Use this to document your testing:

```
Test Date: _______________
Tested By: _______________

| Test # | Feature | Status | Notes |
|--------|---------|--------|-------|
| 1 | Profile Photo Upload | ✅ | Works perfectly |
| 2 | Invalid File Upload | ✅ | Shows error |
| 3 | Delete Photo | ✅ | - |
| 4 | Student Profile Edit | ✅ | - |
| 5 | Admin Profile Edit | ✅ | - |
| 6 | Password Change | ✅ | - |
| 7 | Block Filtering | ✅ | - |
| 8 | Treasurer No Block | ✅ | - |
| 9 | Payment Methods | ✅ | - |
| 10 | Payment Validation | ✅ | - |
| 11 | Logo Display | ✅ | - |
| 12 | Logo File Support | ✅ | - |
| 13 | Student Access | ✅ | - |
| 14 | Treasurer Access | ✅ | - |
| 15 | Admin Access | ✅ | - |
| 16 | Mobile Layout | ⚠️ | Sidebar needs work |
| 17 | Authentication | ✅ | - |
| 18 | CSRF Protection | ✅ | - |
| 19 | File Security | ✅ | - |
| 20 | Data Integrity | ✅ | - |
| 21 | Performance | ⚠️ | Slow with 5k records |

Legend:
✅ Pass
⚠️ Pass with issues
❌ Fail
```

---

## 🎯 CRITICAL PATH TESTING

**Before Production Deploy - Test These First:**

1. ✅ Admin login
2. ✅ Create student user
3. ✅ Assign block to treasurer
4. ✅ Treasurer sees only their block
5. ✅ Record payment (Cash/GCash/Maya/PayPal)
6. ✅ Upload profile photo
7. ✅ Change password
8. ✅ Student login
9. ✅ Student views payments
10. ✅ Logout works

**If all 10 pass → System is ready for production!**

---

## 📞 TROUBLESHOOTING COMMON ISSUES

### Profile Photo Not Showing
```bash
# Solution:
php artisan storage:link
chmod -R 775 storage/
```

### Treasurer Sees All Students (Not Filtered)
```sql
-- Check if treasurer has block assigned:
SELECT u.name, s.block 
FROM users u 
LEFT JOIN students s ON u.id = s.user_id 
WHERE u.role = 'treasurer';

-- If NULL, assign block:
UPDATE students SET block = 'A' WHERE user_id = [treasurer_user_id];
```

### Payment Method Validation Fails
```php
// Check PaymentController.php line:
'payment_method' => ['required', 'in:cash,gcash,maya,paypal']

// Should ONLY have these 4 methods
```

---

**Happy Testing! 🎉**
