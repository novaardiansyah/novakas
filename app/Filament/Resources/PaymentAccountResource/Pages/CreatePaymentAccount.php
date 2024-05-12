<?php

namespace App\Filament\Resources\PaymentAccountResource\Pages;

use App\Filament\Resources\PaymentAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentAccount extends CreateRecord
{
  protected static string $resource = PaymentAccountResource::class;

  public function mutateFormDataBeforeCreate(array $data): array
  {
    $data['user_id'] = auth()->id();
    return $data;
  }

  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }
}
