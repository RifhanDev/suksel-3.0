<?php

namespace App\Http\Middleware;

use App\Support\AllowedAttachment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RestrictUploadedFileTypes
{
    public function handle(Request $request, Closure $next): Response
    {
        $errors = [];

        foreach ($request->allFiles() as $key => $node) {
            $this->collectInvalid($node, (string) $key, $errors);
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        return $next($request);
    }

    /**
     * @param  array<string, string>  $errors
     */
    private function collectInvalid(mixed $node, string $attribute, array &$errors): void
    {
        if ($node instanceof UploadedFile) {
            if ($node->isValid() && ! AllowedAttachment::isAllowed($node)) {
                $errors[$attribute] = AllowedAttachment::MESSAGE;
            }

            return;
        }

        if (! is_array($node)) {
            return;
        }

        foreach ($node as $key => $child) {
            $this->collectInvalid($child, $attribute . '.' . $key, $errors);
        }
    }
}
