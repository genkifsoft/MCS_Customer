<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('db_log', function () {
            return \Log::channel('database');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // For debug only
        if (config('database.debug_slow_queries')) {
            $logger = app('db_log');

            DB::listen(function ($query) use ($logger) {
                if ($query->time > 100) {
                    $sql = $query->sql;

                    foreach ($query->bindings as $key => $binding) {
                        $regex = is_numeric($key)
                        ? "/\\?(?=(?:[^'\\\\']*'[^'\\\\']*')*[^'\\\\']*$)/u"
                        : "/:{$key}(?=(?:[^'\\\\']*'[^'\\\\']*')*[^'\\\\']*$)/u";
                        $sql = preg_replace($regex, sql_value($binding), $sql, 1);
                    }

                    $logger->warn('Slow query: ' . PHP_EOL . sql_format($sql) . PHP_EOL . 'Time: ' . $query->time . 'ms');
                }
            });
        }
    }
}
