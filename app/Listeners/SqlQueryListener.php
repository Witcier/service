<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SqlQueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (true === config('app.debug')) {
            foreach ($event->bindings as $key => $value) {
                if ($value instanceof \DateTimeInterface) {
                    $event->bindings[$key] = $value->format('Y-m-d H:i:s');
                } elseif (is_bool($value)) {
                    $event->bindings[$key] = (int)$value;
                }
            }

            $sql = str_replace('?', "'%s'", $event->sql);
            logger("[{$event->time}ms] " . vsprintf($sql, $event->bindings));
        }
    }
}
