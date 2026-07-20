<?php

namespace App\Http\Middleware;

use Closure;
use App\OrganizationUnit;
use App\News;
use App\Gateway;

class BeforeMiddleware
{
   /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
   public function handle($request, Closure $next)
   {
      // Protect view sharing from fatal DB errors (e.g., local dev DB down)
      try {
         view()->share('global_ou', OrganizationUnit::find(config('app.global_ou')));
         view()->share('global_news', News::where('publish', 1)->orderBy('published_at', 'desc')->take(10)->get());
         view()->share('pay_by_cc', Gateway::whereActive(1)->whereIn('type', ['ebpg', 'migs'])->count() > 0);
         view()->share('pay_by_fpx', Gateway::whereActive(1)->whereType('fpx')->count() > 0);
      } catch (\Throwable $e) {
         // DB not available or other error — share safe defaults so views can render
         view()->share('global_ou', null);
         view()->share('global_news', collect());
         view()->share('pay_by_cc', false);
         view()->share('pay_by_fpx', false);
         // Optionally log at debug level
         if (function_exists('logger')) {
            logger()->debug('BeforeMiddleware skipping DB shares: ' . $e->getMessage());
         }
      }
      return $next($request);
   }
}
