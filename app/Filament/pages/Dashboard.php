<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Forms;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
  use HasFiltersForm;

  public static function getNavigationLabel(): string
  {
    return 'Dashboard';
  }

  public function getTitle(): string
  {
    return 'Dashboard';
  }

  public function filtersForm(Form $form): Form
  {
    return $form->schema([
      Forms\Components\Section::make('Filters')
        ->description('Filter your dashboard using the form below.')
        ->schema([
          Forms\Components\DatePicker::make('startDate')
            ->native(false),
          Forms\Components\DatePicker::make('endDate')
            ->native(false)
        ])
        ->columns(2)
    ]);
  }
}