<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ImportEmployees;
use Filament\Pages\Page;

class ImportPage extends Page
{
    protected static ?string $navigationLabel = "Import Page";

    protected static bool $shouldRegisterNavigation = false;


    protected static string $view = 'filament.pages.imports.index';

    protected function getHeaderWidgets(): array
    {
        return [

            ImportEmployees::class,
        ];
    }
}
