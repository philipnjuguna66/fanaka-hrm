<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard;
use Filament\Pages\Page;

class CustomDashBoard extends Dashboard
{
    public function getWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }

}
