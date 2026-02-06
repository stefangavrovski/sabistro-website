<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{CartItem, Order, Product, Category, Review};
use App\Policies\{CartItemPolicy, OrderPolicy, ProductPolicy, CategoryPolicy, ReviewPolicy};

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        CartItem::class => CartItemPolicy::class,
        Order::class => OrderPolicy::class,
        Product::class => ProductPolicy::class,
        Category::class => CategoryPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}