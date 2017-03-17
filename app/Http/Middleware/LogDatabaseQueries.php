<?php
/**
 * Enable logging of database queries.
 * http://jofad.com/2015/06/16/laravel-5-1-database-query-logging/
 */

namespace App\Http\Middleware;

use DB;
use Log;
use Closure;

class LogDatabaseQueries
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!getenv('DB_LOG', false)) {
            return $next($request);
        }

        DB::enableQueryLog();

        $response = $next($request);
        foreach (DB::getQueryLog() as $log) {
            Log::debug($log['query'], ['bindings' => $log['bindings'], 'time' => $log['time']]);
        }

        return $response;
    }
}
