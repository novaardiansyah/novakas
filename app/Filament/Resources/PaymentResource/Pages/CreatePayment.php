<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\PaymentAccount;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePayment extends CreateRecord
{
  protected static string $resource = PaymentResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['user_id'] = auth()->id();

    $type_id = $data['type_id'] ?? 2;
    $amount  = $data['amount'] ?? 0;

    $payment_account = PaymentAccount::find($data['payment_account_id']);

    if ($type_id === 2) {
      if ($payment_account->deposit < $amount) {
        Notification::make()
          ->danger()
          ->title('Proses gagal!')
          ->body('Saldo akun kas tidak mencukupi.')
          ->send();

        $this->halt();
      }
    }

    return $data;
  }

  protected function handleRecordCreation(array $data): Model
  {
    $record = new ($this->getModel())($data);
    $record->save();

    $type_id = $data['type_id'] ?? 2;
    $amount  = $data['amount'] ?? 0;

    $payment_account = PaymentAccount::find($data['payment_account_id']);
    $deposit = ($type_id === 2) ? $payment_account->deposit - $amount : $payment_account->deposit + $amount;
    
    $payment_account->deposit = $deposit;
    $payment_account->save();

    return $record;
  }

  protected function getRedirectUrl(): string
  {
    $resource = static::getResource();
    return $resource::getUrl('index');
  }
}
