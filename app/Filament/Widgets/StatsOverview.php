<?php

namespace App\Filament\Widgets;

use App\Models\Benefit;
use App\Models\DeductionType;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Employees', Employee::count()),
            Stat::make('Benefits', Benefit::count()),
            Stat::make('Deductions', DeductionType::count()),
        ];
    }
}
