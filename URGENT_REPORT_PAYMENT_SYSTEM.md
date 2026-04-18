# Urgent Report Payment System - Implementation Guide

## Overview
The **Urgent Report Payment System** is fully implemented in the Lost & Found application. Users can mark their reports as "urgent" during creation, and after paying NPR 50, their reports are automatically published to the browse items page without requiring admin approval.

---

## How It Works

### Step 1: User Creates an Urgent Report
- User navigates to "Report Lost" or "Report Found"
- Fills all required fields (title, description, category, location, date, images)
- **Selects "Urgent" from the urgency dropdown**
- Submits the form

### Step 2: Report Is Created with Pending Status
**File**: `app/Http/Controllers/ItemReportController.php` → `storeReport()` method

When creating an urgent report:
```php
'status' => $request->user()->role === 'admin' ? 'open' : 'pending',
'urgency' => $validated['urgency'],
'payment_status' => $validated['urgency'] === 'urgent' ? 'pending' : 'completed',
```

- **For regular users**: Status = `pending` (needs payment)
- **For admins**: Status = `open` (no payment needed)
- **Payment Status**: = `pending` (for urgent reports)

### Step 3: User Redirected to Payment Page
```php
if ($validated['urgency'] === 'urgent') {
    return redirect()
        ->route('payments.urgent-report', $report)
        ->with('info', 'Your report has been created successfully. Please complete the payment of NPR 50 to feature it at the top.');
}
```

User is redirected to: `/reports/{report}/payment`

**File**: `resources/views/payments/urgent-report-checkout.blade.php`

Payment page shows:
- Report title and type
- NPR 50 payment amount
- Benefits of urgent reports:
  - Featured at top of search results
  - Increased visibility
  - Urgent tag displayed
  - Valid for 7 days
- Khalti payment button

### Step 4: User Completes Payment via Khalti

**File**: `app/Http/Controllers/PaymentController.php` → `initiateUrgentReportPayment()` method

```javascript
// Client-side JavaScript initiates payment
const config = {
    publicKey: 'khalti_public_key',
    amount: 5000, // NPR 50 in paisa
    purchase_order_id: 'report-' + reportId,
    eventHandler: {
        onSuccess(payload) {
            // Redirects to verification URL with pidx
            window.location.href = verifyUrl + '?pidx=' + payload.pidx;
        }
    }
};
```

### Step 5: Payment Verification & Auto-Approval

**File**: `app/Http/Controllers/PaymentController.php` → `verifyUrgentReportPayment()` method

After Khalti redirects user back with `pidx` parameter:

```php
public function verifyUrgentReportPayment(Request $request, Report $report): RedirectResponse
{
    // Verify payment with Khalti
    $paymentDetails = $this->khaltiService->lookupPayment($pidx);
    $status = strtoupper($paymentDetails['status'] ?? '');

    if ($status === 'COMPLETED') {
        // ✅ AUTO-APPROVE: Set status to 'open'
        $report->update([
            'payment_pidx' => $pidx,
            'payment_status' => 'completed',
            'status' => 'open', // 🔥 KEY LINE: Report now visible to all users!
        ]);

        Log::info('Urgent report auto-approved after payment', [
            'report_id' => $report->id,
            'pidx' => $pidx,
        ]);

        return redirect()
            ->route('items.show', $report)
            ->with('success', 'Payment successful! Your urgent report is now featured and visible to all users.');
    } else {
        // Payment failed
        $report->update([
            'payment_pidx' => $pidx,
            'payment_status' => 'failed',
        ]);
        // Redirect back to payment page
    }
}
```

### Step 6: Report Appears in Browse Items

**File**: `app/Http/Controllers/ItemReportController.php` → `index()` method

Reports are displayed on the browse page (`/items`) only if their status is `'open'`:

```php
public function index(Request $request)
{
    $query = Report::query()->where('status', 'open'); // Only 'open' reports shown
    
    // Apply filters (category, type, urgency, search)
    // ...
    
    return view('reports.index', [
        'reports' => $query->paginate(12),
    ]);
}
```

Once report status changes to `'open'`, it immediately appears in:
- `/items` (Browse items page)
- Search results
- Category filters
- Type filters

**No admin approval needed!** ⚡

---

## Current Implementation Status

| Component | Status | Location |
|-----------|--------|----------|
| ✅ Report creation with urgency option | Implemented | `ItemReportController::storeReport()` |
| ✅ Urgent report detection | Implemented | `ItemReportController::storeReport()` lines 308-318 |
| ✅ Redirect to payment page | Implemented | `ItemReportController::storeReport()` lines 348-351 |
| ✅ Payment page UI | Implemented | `resources/views/payments/urgent-report-checkout.blade.php` |
| ✅ Khalti payment integration | Implemented | `PaymentController::initiateUrgentReportPayment()` |
| ✅ Payment verification | Implemented | `PaymentController::verifyUrgentReportPayment()` |
| ✅ Auto-approval after payment | Implemented | `PaymentController::verifyUrgentReportPayment()` line 269 |
| ✅ Browse filtering (open status only) | Implemented | `ItemReportController::index()` line 59 |
| ✅ Database schema | Implemented | Migrations with `urgency`, `payment_status`, `payment_pidx` fields |
| ✅ Routes | Implemented | `routes/web.php` lines 136-138 |

---

## Database Schema

**Table**: `reports`

```sql
CREATE TABLE reports (
    id INTEGER PRIMARY KEY,
    user_id INTEGER,
    urgency VARCHAR (normal|urgent),
    payment_status VARCHAR (pending|completed|failed),
    payment_pidx VARCHAR,
    status VARCHAR (pending|open|closed),
    ...
);
```

### Key Fields:
- `urgency`: `'normal'` or `'urgent'`
- `payment_status`: `'pending'`, `'completed'`, or `'failed'`
- `payment_pidx`: Khalti payment reference ID
- `status`: `'pending'` (awaiting approval), `'open'` (visible), `'closed'`

---

## Routes

**File**: `routes/web.php`

```php
// Urgent Report Payment Routes - Both users and admins can access
Route::get('/reports/{report}/payment', [PaymentController::class, 'showUrgentReportPayment'])
    ->name('payments.urgent-report');

Route::post('/reports/{report}/payment/initiate', [PaymentController::class, 'initiateUrgentReportPayment'])
    ->name('payments.urgent-report.initiate');

Route::get('/reports/{report}/payment/verify', [PaymentController::class, 'verifyUrgentReportPayment'])
    ->name('payments.urgent-report.verify');
```

---

## User Flow Diagram

```
User Creates Report
    ↓
Selects "Urgent" option
    ↓
Submits Form
    ↓
Report Created with status='pending' (for regular users)
    ↓
Redirected to Payment Page (/reports/{id}/payment)
    ↓
User Clicks "Pay NPR 50"
    ↓
Khalti Payment Gateway Opens
    ↓
User Completes Payment
    ↓
Khalti Redirects back with pidx
    ↓
Payment Verification
    ↓
IS Payment Successful?
    ├─ YES → Report status changed to 'open' ✅
    │         Report immediately visible in Browse Items
    │         User sees success message
    └─ NO → Payment status set to 'failed'
            User redirected back to payment page
            Can retry payment
```

---

## Admin Approval Bypass

| Scenario | Status After Creation | Status After Payment | Admin Approval Needed |
|----------|----------------------|----------------------|----------------------|
| **Normal Report** | `pending` | N/A | YES ✓ |
| **Urgent Report (Paid)** | `pending` | `open` | NO ✗ |
| **Admin Creates Report** | `open` | N/A | NO ✗ |

---

## Testing the Feature

### Test Case 1: Normal User - Urgent Report with Payment
1. Login as regular user
2. Click "Report Lost" or "Report Found"
3. Fill all fields
4. Select "Urgent" priority
5. Submit form
6. Verify: Redirected to payment page
7. Click payment button
8. Complete Khalti payment (use test card if in test mode)
9. Verify: Payment successful
10. Verify: Report immediately appears in `/items` (browse page)
11. Verify: No admin action needed

### Test Case 2: Normal Report - No Payment
1. Login as regular user
2. Create report with "Normal" urgency
3. Submit form
4. Verify: Redirected to report show page
5. Verify: Report appears in dashboard but NOT in browse (status='pending')
6. Verify: Admin needs to approve before report is visible

### Test Case 3: Admin Creates Urgent Report
1. Login as admin
2. Create report with "Urgent" urgency
3. Submit form
4. Verify: No payment page (admins bypass payment)
5. Verify: Report immediately visible in browse (`status='open'`)

---

## Payment System Details

### Amount: NPR 50 (5000 paisa)
Location: `PaymentController::initiateUrgentReportPayment()` line 213
```php
'amount' => 5000, // Amount in paisa (50 NPR = 5000 paisa)
```

### Duration: 7 days
Location: `resources/views/payments/urgent-report-checkout.blade.php` line 35
```
Valid for 7 days from payment
```

### Payment Gateway: Khalti
- Service: `app/Services/KhaltiService.php`
- Public Key: From `.env` configuration
- Integration: Uses Khalti SDK 2.0.0

---

## Files Involved

### Controllers
- `app/Http/Controllers/ItemReportController.php` → Report creation & listing
- `app/Http/Controllers/PaymentController.php` → Payment handling

### Views
- `resources/views/reports/create.blade.php` → Report creation form (urgency option)
- `resources/views/payments/urgent-report-checkout.blade.php` → Payment page
- `resources/views/reports/index.blade.php` → Browse items (filters)

### Models
- `app/Models/Report.php` → Report model with urgency/payment fields
- `app/Services/KhaltiService.php` → Khalti integration

### Routes
- `routes/web.php` → Payment routes (lines 136-138)

### Database
- Migrations with urgency and payment fields

---

## Success Indicators

After a successful urgent report payment, verify:

✅ Report status changed from `pending` to `open`  
✅ Payment status is `completed`  
✅ Payment PIDX is recorded  
✅ Report visible in `/items` (browse page)  
✅ Report visible in search results  
✅ Report visible in category filters  
✅ User receives success notification  
✅ "FEATURED (Urgent)" badge displays on report  
✅ Entry logged in audit logs

---

## Troubleshooting

### Issue: Payment page not loading
**Solution**: Check Khalti public key in `.env`
```
KHALTI_PUBLIC_KEY=your_public_key_here
```

### Issue: Report not appearing after successful payment
**Solution**: Check that report status was updated to 'open'
```php
SELECT * FROM reports WHERE id = ? AND status = 'open';
```

### Issue: Payment verification failing
**Solution**: Verify Khalti service connection and PIDX lookup
```
Check KhaltiService::lookupPayment() method
```

### Issue: Admin reports being marked as pending
**Solution**: Verify user role check in storeReport()
```php
'status' => $request->user()->role === 'admin' ? 'open' : 'pending',
```

---

## Summary

✅ **The Urgent Report Payment System is fully implemented and active!**

Users can now:
1. Create urgent reports for their items
2. Pay NPR 50 to feature them immediately
3. Bypass admin approval through payment
4. Have reports directly visible to all users
5. Enjoy increased visibility for 7 days

This feature enables:
- Faster report visibility
- Monetization opportunity
- User control over visibility
- Reduced admin workload
- Better matching chances for urgent items
