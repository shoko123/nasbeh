<?php

namespace App\Providers;

use App\Http\Requests\DigModelStoreRequest;
use App\Http\Requests\PotteryStoreRequest;
use App\Http\Requests\StoneStoreRequest;
use App\Models\App\DigModel;
use App\Models\DigModels\Locus;
use App\Models\DigModels\Pottery;
use App\Models\DigModels\Stone;
use App\Models\Groups\ModelGroup;
use App\Models\Groups\Models\LocusGroup;
use App\Models\Groups\Models\PotteryGroup;
use App\Models\Groups\Models\StoneGroup;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
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
        $this->app->singleton(DigModel::class, function ($app) {
            switch (request()->input('model')) {
                case 'Locus':
                    return new Locus;
                case 'Pottery':
                    return new Pottery;
                case 'Stone':
                    return new Stone;
            }
        });

        $this->app->singleton(ModelGroup::class, function ($app) {
            switch (request()->input('model')) {
                case 'Locus':
                    return new LocusGroup;
                case 'Pottery':
                    return new PotteryGroup;
                case 'Stone':
                    return new StoneGroup;
            }
        });
        $this->app->singleton(DigModelStoreRequest::class, function ($app) {
            switch (request()->input('model')) {
                case 'Pottery':
                    return new PotteryStoreRequest;
                case 'Stone':
                    return new StoneStoreRequest;
                default:
                    throw 'BadModelName';
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ResetPassword::createUrlUsing(
            function ($notifiable, $token) {
                return "http://localhost/auth/reset-password/{$token}?email={$notifiable->getEmailForPasswordReset()}";
            }
        );

        Relation::enforceMorphMap([
            'user' => 'App\Models\Auth\User',
            'permission' => 'Spatie\Permission\Models\Permission',
            'role' => 'Spatie\Permission\Models\Role',
            'Locus' => 'App\Models\DigModels\Locus',
            'Stone' => 'App\Models\DigModels\Stone',
        ]);

        JsonResource::withoutWrapping();
    }
}
