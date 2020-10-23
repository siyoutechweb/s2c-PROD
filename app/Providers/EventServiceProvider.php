<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use App\Events\OrderStatisticsEvent;
use App\Events\ProductStatisticsEvent;
//use App\Events\PurchaseOrderEvent;
use App\Listeners\OrderStatisticsListener;
use App\Listeners\ProductStatisticsListener;
use App\Listeners\PurchaseOrderListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ExampleEvent' => [
            'App\Listeners\ExampleListener',
        ],
        OrderStatisticsEvent::class => [
            OrderStatisticsListener::class
        ],
        //ProductStatisticsEvent::class=>[
        ProductStatisticsEvent::class => [
            ProductStatisticsListener::class
        ]
    ];
}
