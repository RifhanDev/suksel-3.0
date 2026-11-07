<?php

namespace App\Http\Controllers;

use App\Support\AttachmentStorage;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function stream(Request $request)
    {
        $path = AttachmentStorage::normalize((string) $request->query('path', ''));
        if ($path === '') {
            abort(404, 'Fail tidak dijumpai.');
        }

        if (! AttachmentStorage::isPublicPrefix($path) && ! auth()->check()) {
            abort(403, 'Akses fail tidak dibenarkan.');
        }

        $name = basename($path);
        $response = AttachmentStorage::responseOrNull($path, $name);
        if (! $response) {
            abort(404, 'Fail tidak dijumpai pada storan sistem.');
        }

        return $response;
    }
}
