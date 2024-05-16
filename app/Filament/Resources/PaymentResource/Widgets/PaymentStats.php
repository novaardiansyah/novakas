<?php

namespace App\Filament\Resources\PaymentResource\Widgets;

use App\Models\Payment;
use App\Models\PaymentAccount;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use function App\Helpers\UtilsHelper\formatNumberToShortString;

class PaymentStats extends BaseWidget
{
  protected function getStats(): array
  {
    $today = now()->toDateString();

    $tmp_payments = Payment::selectRaw('SUM(CASE WHEN type_id = 1 THEN amount ELSE 0 END) AS all_pemasukan')
      ->selectRaw('SUM(CASE WHEN type_id = 2 THEN amount ELSE 0 END) AS all_pengeluaran')
      ->selectRaw('SUM(CASE WHEN type_id = 1 AND date = ? THEN amount ELSE 0 END) AS day_pemasukan', [$today])
      ->selectRaw('SUM(CASE WHEN type_id = 2 AND date = ? THEN amount ELSE 0 END) AS day_pengeluaran', [$today]);
    
    $total_saldo = PaymentAccount::sum('deposit');
    if (!auth()->user()->checkPermissionTo('*')) {
      $total_saldo  = PaymentAccount::where('user_id', '=', auth()->id())->sum('deposit');
      $tmp_payments = $tmp_payments->where('user_id', '=', auth()->id());
    }
    $payments = $tmp_payments->first();

    return [
      Stat::make('Total Pemasukan', formatNumberToShortString($payments->all_pemasukan, false))
        ->description('Hari ini: ' . formatNumberToShortString($payments->day_pemasukan, false))
        ->descriptionIcon('heroicon-m-arrow-trending-up')
        ->color('success'),
      Stat::make('Total Pengeluaran', formatNumberToShortString($payments->all_pengeluaran, false))
        ->description('Hari ini: ' . formatNumberToShortString($payments->day_pengeluaran, false))
        ->descriptionIcon('heroicon-m-arrow-trending-down')
        ->color('danger'),
      Stat::make('Total Saldo', formatNumberToShortString($total_saldo, false))
        ->description('Saldo tersisa disemua Akun Kas')
        ->descriptionIcon('heroicon-m-credit-card')
        ->color('primary'),
    ];
  }
}
