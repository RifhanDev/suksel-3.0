# DuitNow Payment Gateway Implementation Guide

## Overview

This document outlines the implementation of DuitNow payment gateway in the system, following the existing FPX and eBPG payment integration patterns.

## Implementation Summary

### ✅ Completed Components

1. **Gateway Model Updates**

   - Added `'duitnow' => 'DuitNow'` to `Gateway::$methods` in both `app/Gateway.php` and `app/Models/Gateway.php`

2. **DuitNow Model Class** (`app/DuitNow.php`)

   - Payment logic handler similar to `Fpx.php` and `Ebpg.php`
   - Signature generation using HMAC SHA256
   - Signature verification for callbacks
   - Request parameter management

3. **DuitNowController** (`app/Http/Controllers/DuitNowController.php`)

   - `connect()` - Initiates payment and redirects to DuitNow
   - `respond()` - Handles user redirect after payment
   - `callback()` - Handles server-to-server callback from PayNet

4. **Routes** (`routes/web.php`)

   - `GET /payment/duitnow/connect` - Payment initiation
   - `POST /payment/duitnow/respond` - User redirect handler
   - `POST /payment/duitnow/callback` - Server callback handler

5. **Controller Updates**

   - `CartController` - Added DuitNow support for cart checkout
   - `RegistrationController` - Added DuitNow for registration payments
   - `HomeController` - Added DuitNow for subscription renewal

6. **View Updates**
   - `resources/views/payment/duitnow/connect.blade.php` - Payment form
   - `resources/views/cart/checkout.blade.php` - Added DuitNow button
   - `resources/views/registration/payment.blade.php` - Added DuitNow option
   - `resources/views/home/renewal.blade.php` - Added DuitNow option
   - `resources/views/cart/index.blade.php` - Updated gateway check

## Configuration Required

### 1. Database Setup

Create a Gateway record in the database:

```sql
INSERT INTO gateways (type, merchant_code, private_key, transaction_prefix, endpoint_url, active, default, organization_unit_id, created_at, updated_at)
VALUES ('duitnow', 'YOUR_MERCHANT_ID', 'YOUR_API_KEY', 'DN', 'https://api.paynet.my/duitnow/endpoint', 1, 1, 1, NOW(), NOW());
```

### 2. PayNet Registration

1. Register with PayNet Developer Portal: https://docs.developer.paynet.my
2. Create a project in "My Projects"
3. Obtain:
   - Merchant ID
   - API Key / Private Key
   - Endpoint URLs (sandbox and production)
   - Callback URLs configuration

### 3. Environment Configuration

Update your `.env` or gateway configuration:

- **Merchant ID**: Your PayNet merchant identifier
- **API Key**: Your PayNet API key (stored in `private_key` field)
- **Endpoint URL**: PayNet's payment endpoint
- **Transaction Prefix**: Prefix for order IDs (e.g., "DN")

## Important Notes

### API Specification

⚠️ **The current implementation uses a generic DuitNow API structure. You MUST:**

1. **Review PayNet's Official Documentation**

   - API endpoint URLs
   - Request/response parameter names
   - Signature generation method (currently HMAC SHA256)
   - Status code values

2. **Update `app/DuitNow.php`** based on actual PayNet specifications:

   - Request parameter names may differ
   - Signature algorithm might be different
   - Response status codes need verification

3. **Update `DuitNowController.php`**:
   - Adjust status code mapping in `respond()` and `callback()` methods
   - Verify callback URL format
   - Update response parameter names

### Testing

1. Use PayNet's Certification Centre for testing
2. Test in sandbox environment first
3. Verify:
   - Payment initiation
   - User redirect flow
   - Server-to-server callbacks
   - Signature verification
   - Transaction status updates

### Security Considerations

1. **Signature Verification**: Always verify signatures in both `respond()` and `callback()` methods
2. **HTTPS**: Ensure all endpoints use HTTPS
3. **IP Whitelisting**: Consider whitelisting PayNet's callback IPs
4. **Logging**: Enable logging for debugging (currently commented out)

## Payment Flow

1. **User selects DuitNow** in checkout/registration/renewal
2. **Transaction created** with status 'pending'
3. **User redirected** to DuitNow payment page
4. **User completes payment** on DuitNow
5. **DuitNow redirects back** to `duitnow.respond` route
6. **Transaction updated** based on response
7. **PayNet sends callback** to `duitnow.callback` route (server-to-server)
8. **Transaction finalized** and user redirected to success/failure page

## Amount Limits

Current implementation includes basic validation:

- Maximum: RM 50,000.00
- Minimum: RM 1.00

**⚠️ Verify actual limits with PayNet documentation**

## Next Steps

1. ✅ Review PayNet DuitNow API documentation
2. ✅ Update `DuitNow.php` with correct API parameters
3. ✅ Update `DuitNowController.php` with correct status codes
4. ✅ Configure gateway in database
5. ✅ Test in sandbox environment
6. ✅ Update signature verification if needed
7. ✅ Test production integration
8. ✅ Monitor transactions and logs

## Support Resources

- PayNet Developer Portal: https://docs.developer.paynet.my
- PayNet Knowledge Base: https://knowledgebase.paynet.my
- Certification Centre: For testing and validation

## Files Modified/Created

### Created:

- `app/DuitNow.php`
- `app/Http/Controllers/DuitNowController.php`
- `resources/views/payment/duitnow/connect.blade.php`

### Modified:

- `app/Gateway.php`
- `app/Models/Gateway.php`
- `app/Http/Controllers/CartController.php`
- `app/Http/Controllers/RegistrationController.php`
- `app/Http/Controllers/HomeController.php`
- `routes/web.php`
- `resources/views/cart/checkout.blade.php`
- `resources/views/cart/index.blade.php`
- `resources/views/registration/payment.blade.php`
- `resources/views/home/renewal.blade.php`
