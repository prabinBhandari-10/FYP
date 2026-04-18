# Lost & Found System - Complete Test Cases

## 1. AUTHENTICATION & USER MANAGEMENT

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 1. To verify user can register with valid data | Enter valid email, password, and phone number, click register | User account created and redirected to email verification page | | Pending |
| 2. To verify invalid email is rejected | Enter invalid email format, click register | Error message: "Invalid email format" | | Pending |
| 3. To verify weak password is rejected | Enter password less than 8 characters, click register | Error message: "Password must be at least 8 characters" | | Pending |
| 4. To verify duplicate email is prevented | Register with existing email | Error message: "Email already exists" | | Pending |
| 5. To verify all required fields are validated | Leave required field empty, click register | Error message for missing field | | Pending |
| 6. To verify user receives email verification code | Complete registration | Verification email received with code | | Pending |
| 7. To verify invalid verification code is rejected | Enter incorrect verification code | Error message: "Invalid verification code" | | Pending |
| 8. To verify verification code expires | Use old expired code | Error message: "Verification code has expired" | | Pending |
| 9. To verify user can login with valid credentials | Enter correct email and password, click login | User logged in and redirected to dashboard | | Pending |
| 10. To verify login fails with invalid email | Enter non-existent email, click login | Error message: "Email not found" | | Pending |
| 11. To verify login fails with wrong password | Enter incorrect password, click login | Error message: "Invalid password" | | Pending |
| 12. To verify unverified email blocks login | Try login without email verification | Error message: "Please verify your email first" | | Pending |
| 13. To verify blocked user cannot login | Try login as blocked user | Error message: "Your account has been blocked" | | Pending |
| 14. To verify password reset request works | Click forgot password, enter email | Password reset email sent | | Pending |
| 15. To verify user can change password | Follow reset link, enter new password | Password updated and user redirected to login | | Pending |

---

## 2. REPORT MANAGEMENT - LOST ITEMS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 16. To verify lost report with all fields | Fill all fields (title, description, category, location, date, images) and submit | Report created successfully with status "published" | | Pending |
| 17. To verify multiple images upload | Upload 5 images to lost report | All 5 images saved with report | | Pending |
| 18. To verify category tags can be added | Select multiple categories for report | Report saved with all selected tags | | Pending |
| 19. To verify missing required fields are caught | Leave title empty, submit report | Error message: "Title is required" | | Pending |
| 20. To verify report date validation | Select future date for lost item | Error message: "Report date must be in the past" | | Pending |
| 21. To verify image upload limit | Try upload 11 images | Error message: "Maximum 10 images allowed" | | Pending |
| 22. To verify large image is rejected | Upload 10MB image | Error message: "Image must be less than 5MB" | | Pending |
| 23. To verify user can edit their report | Edit report title and description | Report updated successfully | | Pending |
| 24. To verify user cannot edit others' reports | Try edit another user's report | Error message: "Unauthorized" | | Pending |
| 25. To verify user can delete their report | Click delete on own report | Report deleted and cascaded claims deleted | | Pending |
| 26. To verify report search works | Search for "phone" in reports | Results showing reports with "phone" in title/description | | Pending |
| 27. To verify report filter by category | Filter by "Electronics" category | Only electronics reports displayed | | Pending |
| 28. To verify report sorting by newest | Click sort newest | Reports sorted by creation date descending | | Pending |
| 29. To verify pagination works | Navigate to page 2 | Page 2 of reports displayed | | Pending |
| 30. To verify report status update | Change report status from "active" to "found" | Status updated and notifications sent | | Pending |

---

## 3. REPORT MANAGEMENT - FOUND ITEMS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 31. To verify found report creation | Fill all fields for found item report | Report created successfully | | Pending |
| 32. To verify found report location validation | Enter invalid location | Error message: "Invalid location" | | Pending |
| 33. To verify image format validation | Upload .pdf file instead of image | Error message: "Only JPG, PNG, GIF allowed" | | Pending |
| 34. To verify found report appears in public list | Create found report | Report visible on main found items list | | Pending |
| 35. To verify found report visibility to unauthenticated users | View found reports without login | Can see all published found reports | | Pending |

---

## 4. CLAIM MANAGEMENT

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 36. To verify claim with valid proof document | Upload citizenship document and submit claim | Claim created with status "pending" | | Pending |
| 37. To verify claim with proof photo | Upload proof photo and submit claim | Claim created successfully | | Pending |
| 38. To verify claim with proof text | Enter proof text description and submit | Claim created successfully | | Pending |
| 39. To verify claim with both photo and text | Upload photo AND enter text, submit | Claim created with both proofs | | Pending |
| 40. To verify claim without any proof rejected | Submit claim without photo or text | Error message: "Proof photo or text required" | | Pending |
| 41. To verify citizenship document upload | Upload citizenship document | Document saved to claim | | Pending |
| 42. To verify claim without citizenship initially | Submit claim without citizenship document | Claim created (citizenship can be added later) | | Pending |
| 43. To verify duplicate claim prevented | Submit second claim on same report | Error message: "You already have a claim on this item" | | Pending |
| 44. To verify self-claim prevented | Try claim own lost item report | Error message: "Cannot claim your own report" | | Pending |
| 45. To verify claim status is pending | Create new claim | Claim initial status is "pending" | | Pending |
| 46. To verify admin can move claim to verification | Click "Move to Verification" button | Claim status changes to "under_verification" | | Pending |
| 47. To verify admin can final approve claim | Click "Final Approve" on verified claim | Claim status changes to "approved" | | Pending |
| 48. To verify admin can reject claim | Click "Reject" and confirm | Claim status changes to "rejected" and notification sent | | Pending |
| 49. To verify admin can require payment | Enter amount and reason, click "Require Payment" | Claim status changes to "awaiting_payment" | | Pending |
| 50. To verify admin can hold claim | Click "Hold" button | Claim status changes to "held" | | Pending |
| 51. To verify claim transitions to awaiting payment | Admin sets payment requirement | Claim status shows "awaiting_payment" | | Pending |
| 52. To verify claim completes after payment | Payment successful | Claim status changes to "approved" | | Pending |
| 53. To verify rejection count tracked | User gets 2 claims rejected | Rejection count shows as 2 | | Pending |
| 54. To verify user can view own claims | Navigate to "My Claims" | All user's claims displayed | | Pending |
| 55. To verify user auto-blocks after 3 rejections | User received 3 rejections | User account automatically blocked | | Pending |

---

## 5. PAYMENT SYSTEM

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 56. To verify payment can be initiated | Admin clicks "Require Payment" | Payment requirement set on claim | | Pending |
| 57. To verify payment amount validation | Enter 0 as payment amount | Error message: "Amount must be greater than 0" | | Pending |
| 58. To verify payment amount in paisa | Enter 500 (paisa) | Amount shown as 5.00 NPR | | Pending |
| 59. To verify customer receives payment link | Payment required on claim | User receives payment notification with link | | Pending |
| 60. To verify payment gateway integration | Click payment link | Redirected to payment gateway page | | Pending |
| 61. To verify successful payment confirmation | Complete payment successfully | "Payment received" message and claim status updated | | Pending |
| 62. To verify payment failure handling | Fail payment transaction | Error message: "Payment failed, please try again" | | Pending |
| 63. To verify payment retry allowed | Initiate payment again after failure | New payment link provided | | Pending |
| 64. To verify payment timeout handling | Payment times out (no response) | Claim remains in "awaiting_payment" status | | Pending |
| 65. To verify claim approved after successful payment | Payment completes successfully | Claim automatically marked as "approved" | | Pending |
| 66. To verify claim pending after failed payment | Payment fails | Claim status updated, user can retry | | Pending |
| 67. To verify payment history accessible | View payment records | All payment transactions displayed with details | | Pending |
| 68. To verify admin can view all payments | Navigate to payment reports | All system payments with user info shown | | Pending |

---

## 6. NOTIFICATION SYSTEM

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 69. To verify welcome email sent to new user | Complete registration | Welcome email received | | Pending |
| 70. To verify verification email contains code | Register new account | Verification email with code received | | Pending |
| 71. To verify claim status notification on rejection | Admin rejects claim | User receives email: "Your claim was rejected" | | Pending |
| 72. To verify claim rejection email contains reason | Admin rejects claim | Email includes rejection details | | Pending |
| 73. To verify payment required notification | Admin requires payment | User receives email with payment amount and link | | Pending |
| 74. To verify payment confirmation email | Payment successful | User receives payment confirmation email | | Pending |
| 75. To verify article published notification | Admin publishes article | All users receive notification email | | Pending |
| 76. To verify in-app notification displayed | Claim status changes | In-app notification appears in notification center | | Pending |
| 77. To verify user can mark notification read | Click notification, then mark read | Notification marked as read | | Pending |
| 78. To verify user can delete notification | Click delete on notification | Notification removed from list | | Pending |

---

## 7. ARTICLE MANAGEMENT

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 79. To verify article can be saved as draft | Fill article, select "Draft" status | Article saved with "draft" status | | Pending |
| 80. To verify article can be published immediately | Fill article, select "Published" status | Article saved and visible to users immediately | | Pending |
| 81. To verify featured image can be added | Upload image to article | Image displayed on article card | | Pending |
| 82. To verify article title required | Leave title empty, submit | Error message: "Title is required" | | Pending |
| 83. To verify article can be edited | Change article title, click save | Article updated with new title | | Pending |
| 84. To verify draft article can be published | Change status from draft to published | Article published and users notified | | Pending |
| 85. To verify article can be deleted | Click delete on article | Article removed from system | | Pending |
| 86. To verify published articles visible to users | Create published article | Article appears on Articles page | | Pending |

---

## 8. ADMIN DASHBOARD

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 87. To verify dashboard stats are accurate | View dashboard | Report count, claim count, user count correct | | Pending |
| 88. To verify admin can view user list | Navigate to Users section | All users displayed in table | | Pending |
| 89. To verify admin can filter users | Filter users by role "user" | Only regular users displayed | | Pending |
| 90. To verify admin can block user | Click "Block user" on user row | User status changes to "blocked" | | Pending |
| 91. To verify admin can unblock user | Click "Unblock user" on blocked user | User status changes to "active" | | Pending |
| 92. To verify admin can delete user | Click "Delete user" and confirm | User account deleted with all related data | | Pending |
| 93. To verify audit logs recorded | Perform admin action | Action logged in audit logs | | Pending |
| 94. To verify admin can view contact messages | Navigate to Contact Messages | All submitted contact forms displayed | | Pending |

---

## 9. SIGHTINGS & FOUND RESPONSES

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 95. To verify sighting can be recorded | Click "Report Sighting", enter details | Sighting added to report | | Pending |
| 96. To verify report owner notified of sighting | Record sighting on report | Report owner receives notification | | Pending |
| 97. To verify all sightings visible | Navigate to sightings section | All sightings on report displayed | | Pending |
| 98. To verify found response can be submitted | Respond to found item report | Response sent to report owner | | Pending |
| 99. To verify item reporter gets notification | User submits response to found item | Item reporter receives notification | | Pending |

---

## 10. DIRECT MESSAGING

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 100. To verify conversation can be started | Click "Message" on user profile | New conversation created | | Pending |
| 101. To verify message can be sent | Type message and click send | Message appears in conversation | | Pending |
| 102. To verify chat history displayed | Open conversation | All previous messages shown | | Pending |

---

## 11. HOLD CLAIM FUNCTIONALITY

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 103. To verify admin can hold claim | Selected hold option | Claim should be marked as hold | Hold applied successfully | Test Successful |
| 104. To verify held claim shows status | Navigate to claims list | Held claim displays "On hold" badge | | Pending |
| 105. To verify held claim cannot be approved immediately | Try approve held claim | Error: "Must release hold first" | | Pending |
| 106. To verify held claim can be released | Admin removes hold | Claim returns to "pending" status | | Pending |

---

## 12. SECURITY TESTS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 107. To verify XSS injection is prevented | Submit form with `<script>alert('xss')</script>` | Script not executed, displayed as text | | Pending |
| 108. To verify SQL injection is prevented | Submit email field with `'; DROP TABLE users; --` | Query fails safely, no table deleted | | Pending |
| 109. To verify CSRF protection active | Submit form without CSRF token | Error: "CSRF token mismatch" | | Pending |
| 110. To verify regular user cannot access admin pages | Try access /admin/dashboard without admin role | Redirected to home or error page | | Pending |
| 111. To verify user cannot edit others' reports | Try edit different user's report | Error: "Unauthorized" | | Pending |
| 112. To verify role-based access control | Login as user, try access admin features | Features blocked with "Access Denied" | | Pending |
| 113. To verify authentication token expires | Wait for token expiration | User automatically logged out | | Pending |
| 114. To verify file upload security | Try upload .php or .exe file | File rejected with error message | | Pending |
| 115. To verify file upload only accepts images | Try upload .txt file to image field | Error: "Only image files allowed" | | Pending |
| 116. To verify file size limits enforced | Upload 20MB file | Error: "File exceeds maximum size" | | Pending |

---

## 13. VALIDATION TESTS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 117. To verify email format validation | Submit invalid email format | Error message for invalid format | | Pending |
| 118. To verify phone number validation | Submit invalid phone number | Error: "Invalid phone number format" | | Pending |
| 119. To verify date format validation | Submit invalid date format | Error: "Invalid date format" | | Pending |
| 120. To verify text field max length | Enter 1000 chars in 500-char field | Text truncated or error shown | | Pending |
| 121. To verify numeric field validation | Enter letters in numeric field | Error: "Only numbers allowed" | | Pending |
| 122. To verify required field validation | Submit form without required field | Error message for each missing field | | Pending |
| 123. To verify textarea character count | Show character limit on textarea | "Characters: 150/500" displayed | | Pending |

---

## 14. PERFORMANCE TESTS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 124. To verify home page loads quickly | Load home page | Page loads within 2 seconds | | Pending |
| 125. To verify search returns results quickly | Search 1000+ reports | Results returned within 1 second | | Pending |
| 126. To verify pagination performance | Navigate through 50 pages | Each page loads smoothly | | Pending |
| 127. To verify image loading optimization | Load report with 10 images | All images load efficiently | | Pending |
| 128. To verify database query optimization | Check N+1 queries | No duplicate queries found | | Pending |

---

## 15. MOBILE RESPONSIVENESS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 129. To verify mobile layout | View site on 375px width (iPhone) | All elements properly formatted | | Pending |
| 130. To verify touch interactions | Tap buttons on mobile device | Buttons respond to touch | | Pending |
| 131. To verify mobile navigation | Access menu on mobile | Mobile menu opens and closes smoothly | | Pending |

---

## 16. INTEGRATION TESTS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 132. To verify email service connection | Send test email | Email delivered successfully | | Pending |
| 133. To verify email queue processing | Queue 5 emails | All 5 emails processed and sent | | Pending |
| 134. To verify email template rendering | Reset password email | Email contains correct data and formatting | | Pending |
| 135. To verify payment gateway API connection | Test payment gateway | Connection succeeds and returns status | | Pending |
| 136. To verify payment webhook processing | Trigger payment webhook | Payment recorded and claim updated | | Pending |
| 137. To verify file storage system | Upload report image | File saved to storage location | | Pending |
| 138. To verify file retrieval | Access uploaded file | File retrieved and displayed correctly | | Pending |
| 139. To verify file cleanup | Delete report with images | Old images cleaned up from storage | | Pending |

---

## 17. USER EXPERIENCE TESTS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 140. To verify navigation menu works | Click each menu item | All links navigate to correct pages | | Pending |
| 141. To verify breadcrumb navigation | Navigate through pages | Breadcrumbs show correct path | | Pending |
| 142. To verify back button functionality | Click browser back button | Page navigates correctly | | Pending |
| 143. To verify form error messages | Submit invalid form | Clear error messages displayed | | Pending |
| 144. To verify form data persistence | Submit form with error | Form data preserved after error | | Pending |
| 145. To verify success messages | Complete action | Success notification displays | | Pending |

---

## 18. ERROR HANDLING TESTS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 146. To verify 404 error handling | Navigate to non-existent page | 404 error page displayed | | Pending |
| 147. To verify 500 error handling | Trigger server error | 500 error page displayed gracefully | | Pending |
| 148. To verify database error handling | Simulate database connection error | Graceful error message displayed | | Pending |
| 149. To verify file not found handling | Request deleted image | 404 or placeholder displayed | | Pending |
| 150. To verify timeout handling | Trigger long operation timeout | Timeout message displayed | | Pending |

---

## 19. LOCATION AND CATEGORY TESTS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 151. To verify location selection | Select location for report | Location saved with report | | Pending |
| 152. To verify location accuracy | View report location | Location coordinates correct | | Pending |
| 153. To verify location display on map | View report | Location shown on map | | Pending |
| 154. To verify category selection | Select item category | Category saved with report | | Pending |
| 155. To verify multiple category tags | Select 3 categories | All 3 categories saved | | Pending |
| 156. To verify category filtering | Filter by category | Only selected category reports shown | | Pending |

---

## 20. STATUS TRACKING TESTS

| Objectives | Action | Expected Output | Actual Output | Test Result |
|----------|--------|-----------------|---------------|-------------|
| 157. To verify status history recorded | Change claim status | All status changes recorded | | Pending |
| 158. To verify status change notifications | Admin changes claim status | User notified of status change | | Pending |
| 159. To verify invalid status transition prevented | Try skip verification step | System prevents invalid transition | | Pending |
| 160. To verify status display accuracy | View claim | Current status displayed correctly | | Pending |

---

## PRODUCTION READINESS CHECKLIST

| Item | Status | Notes |
|------|--------|-------|
| All unit tests passing | | |
| All integration tests passing | | |
| All feature tests passing | | |
| No console errors | | |
| No database errors | | |
| Email sending verified | | |
| Payment gateway working | | |
| File uploads working | | |
| Admin features working | | |
| Notifications sending | | |
| Mobile responsive | | |
| No SQL injection vulnerabilities | | |
| No XSS vulnerabilities | | |
| CSRF tokens on all forms | | |
| Password reset working | | |
| Blocked user login prevented | | |
| File storage verified | | |
| Audit logs recording | | |
| Performance acceptable | | |

---

**Total Tests: 160+**
**Format: Objectives, Action, Expected Output, Actual Output, Test Result**
