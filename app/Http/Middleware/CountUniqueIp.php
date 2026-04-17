<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visit;
use Carbon\Carbon;

class CountUniqueIp
{
    public function handle(Request $request, Closure $next)
    {
        $ip   = $request->ip();
        $date = Carbon::today()->toDateString();

        Visit::firstOrCreate(
            ['ip' => $ip, 'visit_date' => $date],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return $next($request);
    }
}