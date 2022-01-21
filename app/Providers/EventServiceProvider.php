<?php

namespace App\Providers;

use App\Domains\Auth\Listeners\RoleEventListener;
use App\Domains\Auth\Listeners\UserEventListener;
use App\Listeners\ColorEventListener;
use App\Listeners\SizeEventListener;
use App\Listeners\ClothEventListener;
use App\Listeners\LineEventListener;
use App\Listeners\UnitEventListener;
use App\Listeners\BrandEventListener;
use App\Listeners\ModelProductEventListener;
use App\Listeners\MaterialEventListener;
use App\Listeners\ServiceEventListener;
use App\Listeners\ProductEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\StatusOrder;
use App\Observers\StatusOrderObserver;
use App\Models\MaterialOrder;
use App\Observers\MaterialOrderObserver;
use App\Models\ProductOrder;
use App\Observers\ProductOrderObserver;

/**
 * Class EventServiceProvider.
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Class event subscribers.
     *
     * @var array
     */
    protected $subscribe = [
        RoleEventListener::class,
        UserEventListener::class,
        ColorEventListener::class,
        SizeEventListener::class,
        ClothEventListener::class,
        LineEventListener::class,
        UnitEventListener::class,
        BrandEventListener::class,
        ModelProductEventListener::class,
        MaterialEventListener::class,
        ServiceEventListener::class,
        ProductEventListener::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
            StatusOrder::observe(StatusOrderObserver::class);
            MaterialOrder::observe(MaterialOrderObserver::class);
            ProductOrder::observe(ProductOrderObserver::class);
        //
    }
}
