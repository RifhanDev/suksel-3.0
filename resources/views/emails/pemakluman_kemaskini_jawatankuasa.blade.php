<!doctype html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <title>Kemaskini Keahlian Jawatankuasa</title>
</head>
<body style="margin:0;padding:24px;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#334155;">
    <div style="max-width:640px;margin:0 auto;background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:32px;">
        <h2 style="margin:0 0 20px;color:#1e293b;font-size:22px;">Kemaskini Keahlian Jawatankuasa</h2>
        <p>Salam sejahtera {{ $recipientName }},</p>

        @if ($action === 'added')
            <p>Anda telah dilantik ke dalam <strong>{{ $jenisLabel }}</strong> bagi tender/sebut harga berikut:</p>
            <p><strong>{{ $tender->no_tender ?: $tender->ref_number ?: '-' }}</strong> — {{ $tender->name ?? '-' }}</p>
            <p>Peranan anda: <strong>{{ $newRole ?? 'Ahli' }}</strong><br>P&P: <strong>{{ $newPp ?? '-' }}</strong></p>
        @elseif ($action === 'removed')
            <p>Dimaklumkan bahawa pelantikan anda dalam <strong>{{ $jenisLabel }}</strong> bagi tender/sebut harga berikut telah ditamatkan:</p>
            <p><strong>{{ $tender->no_tender ?: $tender->ref_number ?: '-' }}</strong> — {{ $tender->name ?? '-' }}</p>
        @else
            <p>Maklumat pelantikan anda dalam <strong>{{ $jenisLabel }}</strong> telah dikemaskini bagi tender/sebut harga berikut:</p>
            <p><strong>{{ $tender->no_tender ?: $tender->ref_number ?: '-' }}</strong> — {{ $tender->name ?? '-' }}</p>
            <p>
                Peranan: <strong>{{ $oldRole ?? '-' }}</strong> kepada <strong>{{ $newRole ?? '-' }}</strong><br>
                P&P: <strong>{{ $oldPp ?? '-' }}</strong> kepada <strong>{{ $newPp ?? '-' }}</strong>
            </p>
        @endif

        <p>Sila log masuk ke sistem untuk maklumat lanjut.</p>
        <p style="margin-top:28px;color:#64748b;font-size:13px;">E-mel ini dijana secara automatik oleh Sistem Tender Online Selangor.</p>
    </div>
</body>
</html>
