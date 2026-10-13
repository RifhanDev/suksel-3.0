<?php

namespace App;

/**
 * @deprecated Use \App\Models\Fpx directly.
 *
 * Kept as a subclass so the remaining root-namespace call sites (currently
 * App\Console\Commands\FPXRequery) pick up fixes made to the canonical class
 * instead of running against a stale copy.
 *
 * The previous standalone copy had drifted badly: its bankList() still called
 * array_only(), a helper removed in Laravel 6, so it would have fatal-errored if
 * ever invoked — and it also carried the old hardcoded production URL, hardcoded
 * key filename and disabled TLS verification that were fixed in the canonical class.
 */
class Fpx extends \App\Models\Fpx
{
}
