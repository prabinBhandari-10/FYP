# Features Guide

This file summarizes the custom features added to the project and where they are implemented.

## Main Features

1. Notifications
- In-app notification list with unread count
- Mark one or all as read
- Optional email notifications

2. User profile and history
- Profile page with report/claim stats
- My Reports and My Claims sections

3. Anonymous reporting
- Reporter can hide identity from normal users
- Admin still has full visibility

4. Report moderation flow
- User reports start as pending
- Admin can approve or reject
- Public listing shows only approved reports

5. Report UID and tracking
- Unique report UID generated per report
- Track report status by UID

6. Multi-image support
- One primary image plus optional additional images

## Important Routes

- /items
- /items/{report}
- /reports/lost/create
- /reports/found/create
- /track-report
- /track-report/{reportUid}
- /notifications
- /profile

## Key Files

Controllers
- app/Http/Controllers/ItemReportController.php
- app/Http/Controllers/ClaimController.php
- app/Http/Controllers/AdminController.php
- app/Http/Controllers/NotificationController.php
- app/Http/Controllers/ProfileController.php

Models
- app/Models/Report.php
- app/Models/Notification.php
- app/Models/ReportImage.php

Views
- resources/views/reports/create.blade.php
- resources/views/reports/show.blade.php
- resources/views/reports/track.blade.php
- resources/views/user/profile.blade.php
- resources/views/user/notifications.blade.php
- resources/views/admin/reports/index.blade.php

## Database Changes

- Added is_anonymous to reports
- Added report_uid to reports
- Added notifications table
- Added report_images table

## Notes

- If email notifications are required, configure mail settings in .env.
- If images do not appear, run: php artisan storage:link
- After major route/view updates, run: php artisan optimize:clear

### Example 1: Get Notified About Similar Item
1. User A reports "Lost: Blue Backpack"
2. User B reports "Found: Blue Backpack"
3. System creates notification for User A: "Similar Item Found! Found a Backpack similar to your search"
4. User A sees badge with "1" on bell icon
5. User A clicks bell → Sees notification → Clicks "View Item" → Sees User B's found item

### Example 2: Anonymous Report
1. User reports "Found: Student ID" anonymously
2. Report appears on platform as "Anonymous - Found Student ID"
3. User submits claim to item
4. System notifies original reporter about claim
5. Admin approves claim
6. Original reporter contacted to coordinate handoff

### Example 3: Profile Activity Tracking
1. User visits profile page
2. Sees: "Total Reports: 5, Total Claims: 3, Approved: 2, Pending: 1"
3. Views "My Reports" showing all 5 items with claim counts
4. Views "My Claims" showing all 3 with status indicators
5. Clicks item to view full details

---

## 📊 Database Schema

### notifications table
```sql
- id (PK)
- user_id (FK → users)
- type (enum: similar_item, claim_received, claim_approved, claim_rejected)
- title (varchar)
- message (text)
- related_report_id (FK → reports, nullable)
- related_claim_id (FK → claims, nullable)
- is_read (boolean, default: false)
- is_email_sent (boolean, default: false)
- timestamps (created_at, updated_at)
- indices: [user_id, is_read], [created_at]
```

### report_images table
```sql
- id (PK)
- report_id (FK → reports)
- image_path (varchar)
- sort_order (int, default: 0)
- timestamps (created_at, updated_at)
```

### reports table
```sql
- ... (existing columns)
- is_anonymous (boolean, default: false) -- NEW
```

---

## 🔧 Configuration Notes

**Mail Settings (.env)**
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
MAIL_FROM_ADDRESS=noreply@lostfound.app
MAIL_FROM_NAME="FYP Lost & Found"
```

**Storage Settings**
- Images stored in: `storage/app/public/reports/`
- Must run: `php artisan storage:link`
- Accessible at: `/storage/reports/filename.jpg`

---

## 🎓 Testing Checklist

- [ ] Create report with multiple images
- [ ] Verify images displayed in report detail
- [ ] Report item anonymously  
- [ ] Verify anonymous name doesn't show publicly
- [ ] Check notification bell shows unread count
- [ ] Click notification bell and view notifications
- [ ] Mark notification as read
- [ ] View profile page with reports and claims
- [ ] Verify pagination works on profile
- [ ] Test email notifications (check logs)
- [ ] Verify admin can see reporter info for anonymous reports

---

## 📝 Future Enhancements

- **Push Notifications**: Real-time browser push notifications
- **Notification Preferences**: Users choose which notifications to receive
- **Notification History Archive**: Keep notifications for 30 days then archive
- **Search/Filter Notifications**: Search by type or date range
- **Batch Image Upload**: Drag & drop multiple images at once
- **Image Galleries**: Lightbox view for all images
- **Notification Subscriptions**: Subscribe/unsubscribe from notification types
- **Email Digest**: Daily/weekly summary email instead of individual emails
- **Auto-matching Improvements**: Machine learning to improve similar item detection

---

## 📞 Support & Troubleshooting

**Issue: Notification bell not showing count**
- Clear browser cache
- Verify `auth()->user()` is accessible
- Check database migrations ran: `php artisan migrate`

**Issue: Multiple images not saving**
- Verify `storage/app/public/reports/` exists
- Run: `php artisan storage:link`
- Check file upload size in php.ini `upload_max_filesize`
- Verify form has `enctype="multipart/form-data"`

**Issue: Emails not sending**
- Check mail configuration in `.env`
- Test with: `php artisan tinker` → `Mail::raw(...)->send(...)`
- Check `storage/logs/laravel.log` for errors
- Verify SMTP credentials are correct

---

## 🎉 Feature Summary

| Feature | Status | Access |
|---------|--------|--------|
| Real-time Notifications | ✅ Complete | Bell icon in navbar |
| Notification List | ✅ Complete | `/notifications` page |
| Email Notifications | ✅ Complete | Auto-sent on events |
| User Profile Page | ✅ Complete | Profile button in navbar |
| My Reports History | ✅ Complete | Profile page |
| My Claims History | ✅ Complete | Profile page |
| Anonymous Reporting | ✅ Complete | Report form checkbox |
| Multiple Image Upload | ✅ Complete | Report form multi-upload |
| Notification Service | ✅ Complete | Backend service class |

---

**All features are now fully implemented and ready to use!** 🚀
