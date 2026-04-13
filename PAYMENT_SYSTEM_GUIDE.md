# Khalti Payment System - Complete Working Guide

## 🎯 Payment System Overview

The system now has a complete payment flow for urgent reports with AUTO-APPROVAL:

### Report Flow Diagram

```
User Creates Report
    ↓
Normal Report ──→ Status: PENDING → Waits for Admin Approval
                    ↓
                Admin Reviews → APPROVE/REJECT

Urgent Report ──→ Status: PENDING → Redirects to Payment
                    ↓
                User Clicks "Pay NPR 50 via Khalti"
                    ↓
                Khalti Payment Modal Opens
                    ↓
                Payment Successful
                    ↓
                AUTO-APPROVED ✓ (Status: OPEN)
                    ↓
                Report immediately visible to all users
                    ↓
                Featured badge on report
```

---

## 💳 How Khalti Payment Works

### Step 1: Report Creation
```
User creates report and selects "Urgent" ✓
- Status set to: "pending" (awaiting payment)
- Payment Status: "pending"
- Redirects to: /reports/{id}/payment
```

### Step 2: Payment Page
```
User views payment checkout page
- Report details displayed
- Payment amount: NPR 50 (fixed)
- Duration: 7 days of featured status
- Benefits listed
- "Pay NPR 50 via Khalti" button
```

### Step 3: Khalti SDK Initialization
```
JavaScript loads Khalti SDK from CDN
- Khalti public key retrieved from config
- Payment config created with:
  * Amount: 5000 paisa (50 NPR)
  * Product identity: report-{id}
  * Return URL: /reports/{id}/payment/verify
- SDK waits for user to click payment button
```

### Step 4: User Payment
```
User clicks "Pay NPR 50 via Khalti"
    ↓
Khalti modal opens with payment methods
    ↓
User completes payment
    ↓
Khalti returns pidx (payment transaction ID)
```

### Step 5: Payment Verification
```
Backend receives pidx parameter
    ↓
Calls Khalti API: lookupPayment(pidx)
    ↓
Khalti API returns payment status
    ↓
If Status = COMPLETED:
  ✓ Update payment_pidx in database
  ✓ Set payment_status = 'completed'
  ✓ [IMPORTANT] Auto-approve: Set status = 'open'
  ✓ Redirect to report view with success message
    ↓
If Status ≠ COMPLETED:
  ✗ Set payment_status = 'failed'
  ✗ Show error message
  ✓ Allow user to retry payment
```

---

## 🔄 Updated Payment Flow Code

### In PaymentController.php (verifyUrgentReportPayment)

```php
if ($status === 'COMPLETED') {
    // Update payment details
    $report->update([
        'payment_pidx' => $pidx,
        'payment_status' => 'completed',
    ]);

    // AUTO-APPROVE urgent reports after successful payment
    if ($report->urgency === 'urgent' && $report->status !== 'open') {
        $report->update([
            'status' => 'open', // Automatically approve!
        ]);
        
        Log::info('Urgent report auto-approved after payment', [
            'report_id' => $report->id,
            'pidx' => $pidx,
        ]);
    }

    return redirect()
        ->route('items.show', $report)
        ->with('success', 'Payment successful! Your urgent report is now featured and visible to all users.');
}
```

---

## 🧪 How to Test Payment System

### Prerequisites:
- Khalti Account (Test Environment)
- Public Key: `c1c8bf387656435689b8475798f0006f`
- Secret Key: `b4f7931cee604423a46cfb5aa601600f`

### Test Flow:

1. **Login / Register User**
   ```
   Email: testuser@example.com
   Password: password123
   ```

2. **Create an Urgent Report**
   - Go to: /reports/lost/create or /reports/found/create
   - Select "Urgent" option
   - Fill in all details
   - Submit → Redirected to payment page

3. **Complete Khalti Payment**
   - Click "Pay NPR 50 via Khalti"
   - Khalti modal opens
   - Choose payment method
   - Complete payment
   - Payment verified automatically

4. **Result: AUTO-APPROVAL ✓**
   ```
   Report Status changes: pending → open
   Payment Status: pending → completed
   Report immediately visible on site
   Featured badge displayed
   No admin approval needed!
   ```

5. **Verify Report**
   - Report visible on browse page
   - Has "Urgent" badge
   - Appears at top of results
   - No "pending approval" message

---

## 🎯 Key Features

### ✅ What Works Now:

1. **Payment Initiation**
   - Khalti SDK properly loads and initializes
   - Error handling for SDK load failures
   - Retry mechanism if SDK doesn't load

2. **Payment Processing**
   - User completes payment through Khalti modal
   - Payment transaction ID (pidx) captured
   - Backend verification with Khalti API

3. **Auto-Approval**
   - Urgent reports auto-approved after payment
   - Report status automatically set to "open"
   - No admin review needed
   - Immediately visible to all users

4. **Normal Reports**
   - Still require admin approval
   - Status remains "pending" until admin reviews
   - No payment required (free to post)

5. **Error Handling**
   - Khalti SDK load failures handled gracefully
   - User-friendly error messages
   - Allows payment retry on failure

---

## 🔐 Security Features

- ✅ Authorization checked (user/admin ownership)
- ✅ Report urgency verified before payment
- ✅ Payment status verified with Khalti API
- ✅ Prevents duplicate payments
- ✅ Secure Khalti integration with secret key
- ✅ Transaction IDs logged for audit trail

---

## 📊 Database Fields Updated

### Reports Table:

```sql
urgency: 'urgent' | 'normal'
payment_status: 'pending' | 'completed' | 'failed'
payment_pidx: string (Khalti transaction ID)
status: 'pending' | 'open' | 'closed'
```

### Payment Flow Logic:

| Report Type | Urgency | Initial Status | Initial Payment | After Payment |
|---|---|---|---|---|
| User - Lost | Normal | pending | completed | pending (needs admin approval) |
| User - Lost | Urgent | pending | pending | open (auto-approved) |
| User - Found | Normal | pending | completed | pending (needs admin approval) |
| User - Found | Urgent | pending | pending | open (auto-approved) |
| Admin - Any | Any | open | completed | open (already approved) |

---

## 🛠️ Troubleshooting

### "Payment System Error - Please Refresh"
- **Cause**: Khalti SDK failed to load
- **Solution**: 
  - Check Khalti public key is set in .env
  - Refresh the page
  - Check browser console for errors

### Payment stuck in "pending"
- **Cause**: Backend verification failed
- **Solution**:
  - Check server logs
  - Verify Khalti secret key is correct
  - Try payment again

### Report not auto-approved after payment
- **Cause**: Report already has status 'open' or urgency verification failed
- **Solution**:
  - Check report status in database
  - Verify report urgency is 'urgent'
  - Check server logs for errors

---

## 📝 Configuration

### .env File:
```
KHALTI_PUBLIC_KEY=c1c8bf387656435689b8475798f0006f
KHALTI_SECRET_KEY=b4f7931cee604423a46cfb5aa601600f
KHALTI_BASE_URL=https://dev.khalti.com/api/v2/
```

### Routes:
```
GET  /reports/{report}/payment              → Show payment page
POST /reports/{report}/payment/initiate     → Initiate payment
GET  /reports/{report}/payment/verify       → Verify payment & auto-approve
```

---

## ✨ Summary

**The payment system is now FULLY FUNCTIONAL:**

1. ✅ Khalti payment SDK initializes correctly
2. ✅ Users can complete payments for urgent reports
3. ✅ Payments are verified with Khalti API
4. ✅ Urgent reports are automatically approved after payment
5. ✅ Reports immediately become visible
6. ✅ No admin approval needed for paid urgent reports
7. ✅ Normal reports still require admin approval
8. ✅ Complete error handling and user feedback
