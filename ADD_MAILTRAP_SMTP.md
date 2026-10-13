# How to Add Mailtrap SMTP Configuration

## Option 1: Via Admin Interface (Recommended)

1. Login as Admin user
2. Go to: `/mail-manager/smtp-setting/create` or `/smtp_mails/create`
3. Fill in the form with your Mailtrap credentials:

   - **Mail Server**: `smtp.mailtrap.io` (or `sandbox.smtp.mailtrap.io` for testing)
   - **Mail Port**: `2525` or `587`
   - **Mail Encryption**: Select `TLS` (value: 1)
   - **Mail Username**: Your Mailtrap username (from Mailtrap dashboard)
   - **Mail Password**: Your Mailtrap password (from Mailtrap dashboard)
   - **Mail Message Rate Limit**: `100` (or any number you prefer)

## Option 2: Via Laravel Tinker

Run this command and replace the values:

```bash
php artisan tinker
```

Then in tinker:

```php
use App\Models\SmtpMails;
use App\Traits\Helper;

// Helper class to encrypt password
class TempHelper {
    use \App\Traits\Helper;
}

$helper = new TempHelper();

// Replace these with your actual Mailtrap credentials
SmtpMails::create([
    'mail_server' => 'smtp.mailtrap.io',
    'mail_port' => '2525',
    'mail_crypto' => 1, // 1 = TLS, 2 = SSL, 0 = None
    'mail_username' => 'YOUR_MAILTRAP_USERNAME',
    'mail_password' => $helper->encryptString('YOUR_MAILTRAP_PASSWORD'),
    'mail_message_ratelimit' => 100,
    'created_by' => 1, // Admin user ID
]);
```

## After Adding SMTP Config

1. Make sure queue worker is running: `php artisan queue:work`
2. Test by submitting a complaint via chatbot
3. Check Mailtrap inbox for the email

