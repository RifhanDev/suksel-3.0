# DuitNow API Registration Guide

## ⚠️ IMPORTANT: Registration is MANDATORY

**You CANNOT use DuitNow API without registering with PayNet first.** The API requires authentication credentials that are only provided after successful registration and certification.

## Registration Requirements

### 1. Prerequisites

Before registering, ensure you have:

- ✅ Valid business registration (SSM/ROC)
- ✅ Business bank account in Malaysia
- ✅ Company documents (registration certificate, business license)
- ✅ Technical team to handle integration
- ✅ Server with HTTPS capability
- ✅ Domain name for callback URLs

### 2. Registration Steps

#### Step 1: Create PayNet Developer Account

1. Visit: **https://docs.developer.paynet.my**
2. Click "Sign Up" or "Register"
3. Fill in your company information
4. Verify your email address

#### Step 2: Create Integration Project

1. Log into PayNet Developer Portal
2. Navigate to **"My Projects"**
3. Click **"Create New Project"**
4. Select **"DuitNow"** as the service
5. Fill in project details:
   - Project name
   - Description
   - Business type
   - Expected transaction volume

#### Step 3: Submit Business Information

You'll need to provide:

- **Company Details:**
  - Company name (as per SSM)
  - Registration number
  - Business address
  - Contact person details
- **Banking Information:**
  - Bank name
  - Account number
  - Account holder name
  - Bank branch code
- **Technical Information:**
  - Callback URL (e.g., `https://yourdomain.com/payment/duitnow/callback`)
  - Return URL (e.g., `https://yourdomain.com/payment/duitnow/respond`)
  - Server IP addresses (for whitelisting)
  - Technical contact details

#### Step 4: Upload Documents

Required documents:

- Company registration certificate (SSM)
- Business license (if applicable)
- Bank statement or bank letter
- Authorized signatory form
- Technical integration plan

#### Step 5: Certification Process

1. **Sandbox Access:**

   - PayNet will provide sandbox credentials
   - Test your integration in sandbox environment
   - Use PayNet's Certification Centre

2. **Self-Service Testing:**

   - Test payment initiation
   - Test payment completion
   - Test callback handling
   - Test error scenarios
   - Verify signature generation/verification

3. **Submit Test Results:**
   - Document all test cases
   - Submit test results to PayNet
   - PayNet will review your integration

#### Step 6: Approval & Go-Live

1. **PayNet Review:**

   - PayNet reviews your submission
   - May request additional information
   - Technical review of your integration

2. **Production Credentials:**

   - Once approved, you'll receive:
     - Production Merchant ID
     - Production API Key
     - Production endpoint URLs
     - Certificate files (if applicable)

3. **Go-Live:**
   - Update your system with production credentials
   - Conduct final testing
   - Start processing live transactions

## What You'll Receive After Registration

### Credentials:

```
Merchant ID: [Your unique merchant identifier]
API Key: [Your secret API key]
Sandbox Endpoint: https://sandbox.paynet.my/duitnow/api
Production Endpoint: https://api.paynet.my/duitnow/api
```

### Configuration for Your System:

```php
// In your Gateway database record:
type: 'duitnow'
merchant_code: '[Merchant ID from PayNet]'
private_key: '[API Key from PayNet]'
endpoint_url: '[Production endpoint URL]'
transaction_prefix: 'DN' // or your preferred prefix
```

## Timeline Estimate

| Phase                        | Duration                 |
| ---------------------------- | ------------------------ |
| Registration & Documentation | 1-2 days                 |
| PayNet Review                | 3-5 business days        |
| Sandbox Access               | Immediate after approval |
| Certification Testing        | 1-2 weeks                |
| Production Approval          | 1-2 weeks                |
| **Total**                    | **2-4 weeks**            |

## Fees & Charges

Contact PayNet directly for:

- Setup fees
- Monthly maintenance fees
- Transaction fees (per transaction)
- Settlement fees
- Any other applicable charges

**Contact PayNet Sales Team:**

- Email: [Check PayNet website]
- Phone: [Check PayNet website]
- Website: https://www.paynet.my

## Important Notes

### 1. Security Requirements

- ✅ Use HTTPS for all endpoints
- ✅ Implement proper signature verification
- ✅ Store API keys securely (never in code)
- ✅ Use environment variables for sensitive data
- ✅ Implement IP whitelisting (if required by PayNet)

### 2. Callback URLs

Your system must have:

- **Callback URL**: For server-to-server notifications
  - Route: `POST /payment/duitnow/callback`
  - Must be publicly accessible
  - Must use HTTPS
- **Return URL**: For user redirects
  - Route: `POST /payment/duitnow/respond`
  - Must be publicly accessible
  - Must use HTTPS

### 3. Testing

- Always test in sandbox first
- Never use production credentials in development
- Test all scenarios (success, failure, pending)
- Verify signature generation matches PayNet's requirements

### 4. Support

- PayNet Developer Portal: https://docs.developer.paynet.my
- PayNet Knowledge Base: https://knowledgebase.paynet.my
- PayNet Support: [Contact details on website]

## Next Steps After Registration

1. ✅ Receive sandbox credentials
2. ✅ Update `app/DuitNow.php` with correct API parameters
3. ✅ Update `DuitNowController.php` with correct status codes
4. ✅ Configure gateway in database with sandbox credentials
5. ✅ Test in sandbox environment
6. ✅ Complete certification
7. ✅ Receive production credentials
8. ✅ Update system with production credentials
9. ✅ Go live!

## Common Issues

### Issue: "Invalid Merchant ID"

- **Solution**: Ensure you're using the correct Merchant ID provided by PayNet
- Check if you're using sandbox credentials in production or vice versa

### Issue: "Invalid Signature"

- **Solution**: Verify signature generation algorithm matches PayNet's specification
- Check parameter ordering in signature string
- Ensure API key is correct

### Issue: "Callback Not Received"

- **Solution**: Verify callback URL is publicly accessible
- Check firewall/security settings
- Verify URL is using HTTPS
- Check PayNet's callback logs

## Resources

- **PayNet Developer Portal**: https://docs.developer.paynet.my
- **DuitNow Documentation**: https://docs.developer.paynet.my/docs/duitNow
- **PayNet Knowledge Base**: https://knowledgebase.paynet.my
- **PayNet Main Website**: https://www.paynet.my

---

**Remember**: You cannot use DuitNow API without completing the registration and certification process. Start the registration as soon as possible as it can take 2-4 weeks to complete.
