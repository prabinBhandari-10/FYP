# Project Edit Guide

Quick reference for where to make common changes.

## Core Files

- Routes: routes/web.php
- Main layout: resources/views/layouts/app.blade.php
- Footer: resources/views/partials/site-footer.blade.php
- Main report logic: app/Http/Controllers/ItemReportController.php
- Admin actions: app/Http/Controllers/AdminController.php

## Page Locations

- Home: resources/views/pages/home.blade.php
- About: resources/views/pages/about.blade.php
- Contact: resources/views/pages/contact.blade.php
- Login/Register: resources/views/auth/
- User profile: resources/views/user/profile.blade.php
- Notifications: resources/views/user/notifications.blade.php
- Report pages: resources/views/reports/
- Admin pages: resources/views/admin/

## Common Edits

- Navbar and global styles: resources/views/layouts/app.blade.php
- Report form fields and map behavior: resources/views/reports/create.blade.php
- Report details, claim, and sightings UI: resources/views/reports/show.blade.php
- Track by UID page: resources/views/reports/track.blade.php
- Home feed sections: resources/views/pages/home.blade.php

## Database Notes

- Reporting and moderation: reports table
- UID tracking: reports.report_uid
- Anonymous reporting: reports.is_anonymous
- Notification data: notifications table
- Extra report images: report_images table

## Useful Commands

- php artisan optimize:clear
- php artisan migrate
- php artisan route:list
- php artisan view:clear
