<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentAccountStats extends BaseWidget
{
  protected function getStats(): array
  {
    return [
      Stat::make('Total Pemasukan', 'Rp. 40.000.000,00')
        ->description('Hari ini: Rp. 120.000,00')
        ->descriptionIcon('heroicon-m-arrow-trending-up')
        ->color('success'),
      Stat::make('Total Pengeluaran', 'Rp. 3.500.000,00')
        ->description('Hari ini: Rp. 350.000,00')
        ->descriptionIcon('heroicon-m-arrow-trending-down')
        ->color('danger'),
      Stat::make('Total Saldo', 'Rp. 1.250,000,00')
        ->description('Sisa dari keseluruhan saldo')
        ->descriptionIcon('heroicon-m-arrow-trending-up')
        ->color('primary'),
    ];
  }
}
