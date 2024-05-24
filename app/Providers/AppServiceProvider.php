<?php

namespace App\Providers;

use App\Support\Macros\CreateUpdateOrDelete;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Filament\Support\View\Components\Modal;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->addCreateUpdateOrDeleteHasManyMacro();

        Filament::serving(function () {

            Modal::closedByClickingAway(false);

            TextInput::macro('money', fn(): static => $this->numeric()
                ->prefix('Ksh')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
            );


        });

    }

    /**
     * @return void
     */
    protected function addCreateUpdateOrDeleteHasManyMacro(): void
    {
        HasMany::macro('createUpdateOrDelete', function (iterable $records) {
            /** @var HasMany */
            $hasMany = $this;

            return (new CreateUpdateOrDelete($hasMany, $records))();
        });
    }
}
