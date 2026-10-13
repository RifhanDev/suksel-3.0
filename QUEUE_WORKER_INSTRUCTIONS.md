# How to Process Email Queue

Your email system uses a queue-based approach. After emails are queued, you need to run the queue worker to process them.

## Option 1: Run Queue Worker Manually (For Testing)

```bash
php artisan queue:work
```

This will process all queued jobs. Press `Ctrl+C` to stop.

## Option 2: Run Queue Worker in Background (Recommended for Development)

```bash
php artisan queue:work > /dev/null 2>&1 &
```

Or for better logging:

```bash
php artisan queue:work >> storage/logs/queue.log 2>&1 &
```

## Option 3: Use Supervisor (Recommended for Production)

Create a supervisor config file at `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/worker.log
stopwaitsecs=3600
```

Then run:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

## Check Queue Status

To see if emails are being processed:

```bash
php artisan tinker
```

Then:
```php
use App\Models\MailQueue;
MailQueue::where('status', 'N')->count(); // Count pending emails
MailQueue::where('status', 'S')->count(); // Count sent emails
MailQueue::orderBy('id', 'desc')->limit(5)->get(['id', 'status', 'email_send_at']);
```

## After Running Queue Worker

Once the queue worker processes the jobs:
1. The emails will be sent via the mail server endpoint
2. The queue status will change from 'N' (Not sent) to 'S' (Sent)
3. You should see the emails in your Mailtrap inbox

