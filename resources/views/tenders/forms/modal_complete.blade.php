<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selesai</title>
    <style>
        body {
            font-family: Inter, system-ui, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: #f8fafc;
            color: #334155;
            font-size: 0.9rem;
        }
        .box {
            text-align: center;
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="box">
        <p>{{ $message ?? 'Berjaya disimpan.' }}</p>
    </div>
    <script>
        (function () {
            if (window.parent === window) {
                return;
            }
            window.parent.postMessage({
                type: 'vendor-form-complete',
                status: @json($flashKey ?? 'success'),
                message: @json($message ?? 'Berjaya disimpan.')
            }, window.location.origin);
        })();
    </script>
</body>
</html>
