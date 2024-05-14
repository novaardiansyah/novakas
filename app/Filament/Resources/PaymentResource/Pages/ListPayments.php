<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;

class ListPayments extends ListRecords
{
  protected static string $resource = PaymentResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }

  public function getTabs(): array
  {
    return [
      'All' => Tab::make(),
      'Pemasukan' => Tab::make()
        ->modifyQueryUsing(fn (Builder $query) => $query->where('type_id', '=', 1)),
      'Pengeluaran' => Tab::make()
        ->modifyQueryUsing(fn (Builder $query) => $query->where('type_id', '=', 2)),
    ];
  }
}
