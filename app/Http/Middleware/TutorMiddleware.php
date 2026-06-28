<?php // app/Http/Middleware/TutorMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        abort_if(!Auth::user()?->is_tutor, 403);
        return $next($request);
    }
}