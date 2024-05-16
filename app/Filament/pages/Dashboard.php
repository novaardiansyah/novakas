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
      Forms\Components\Section::make('Filter')
        ->description('Filter data berdasarkan formulir dibawah ini.')
        ->schema([
          Forms\Components\DatePicker::make('startDate')
            ->label('Tanggal')
            ->placeholder('Masukkan tanggal mulai')
            ->displayFormat('d M Y')
            ->closeOnDateSelection()
            ->native(false),
          Forms\Components\DatePicker::make('endDate')
            ->label('Sampai dengan')
            ->placeholder('Masukkan tanggal akhir')
            ->displayFormat('d M Y')
            ->closeOnDateSelection()
            ->native(false)
        ])
        ->columns(2)
    ]);
  }
}