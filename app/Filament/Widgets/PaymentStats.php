<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\PaymentAccount;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

use function App\Helpers\UtilsHelper\formatNumberToShortString, false;

class PaymentStats extends BaseWidget
{
  use InteractsWithPageFilters;

  protected function getStats(): array
  {
    $startDate = $this->filters['startDate'];
    $endDate = $this->filters['endDate'];

    $startDate = $startDate ? Carbon::parse($startDate) : now()->subMonths(6);
    $endDate = $endDate ? Carbon::parse($endDate) : now();

    $today = now()->toDateString();

    $tmp_payments = Payment::selectRaw('SUM(CASE WHEN type_id = 1 AND date BETWEEN ? AND ? THEN amount ELSE 0 END) AS all_pemasukan', [$startDate, $endDate])
      ->selectRaw('SUM(CASE WHEN type_id = 2 AND date BETWEEN ? AND ? THEN amount ELSE 0 END) AS all_pengeluaran', [$startDate, $endDate])
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
