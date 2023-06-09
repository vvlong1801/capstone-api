<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services;

class AppServiceProvider extends ServiceProvider
{
    protected $bindingServices = [
        Services\Interfaces\UserServiceInterface::class => Services\UserService::class,
        Services\Interfaces\AuthServiceInterface::class => Services\AuthService::class,
        // Services\Interfaces\ExerciseServiceInterface::class => Services\ExerciseService::class,
        // Services\Interfaces\GroupExerciseServiceInterface::class => Services\GroupExerciseService::class,
        // Services\Interfaces\ChallengeServiceInterface::class => Services\ChallengeService::class,
        // Services\Interfaces\EquipmentServiceInterface::class => Services\EquipmentService::class,
        // Services\Interfaces\MuscleServiceInterface::class => Services\MuscleService::class,
        // Services\Interfaces\MediaServiceInterface::class => Services\MediaServices\LocalService::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public function registerServices()
    {
        foreach ($this->bindingServices as $interface => $service) {
            app()->bind($interface, $service);
        }
    }
}
