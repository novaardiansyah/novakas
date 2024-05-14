<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\PaymentAccount;
use Carbon\Carbon;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Log\Logger;

use function Filament\Support\format_number;

class PaymentResource extends Resource
{
  protected static ?string $model = Payment::class;

  protected static ?string $navigationIcon = 'heroicon-o-banknotes';
  protected static ?string $navigationGroup = 'Finance';
  protected static ?string $label = 'Keuangan';

  public static function getEloquentQuery(): Builder
  {
    $parent = parent::getEloquentQuery();
    if (auth()->user()->checkPermissionTo('*')) return $parent;
    return $parent->where('user_id', '=', auth()->id());
  }

  public static function form(Form $form): Form
  {
    return $form
      ->columns(3)
      ->schema([
        Forms\Components\Group::make([
          Forms\Components\Section::make('Keuangan')
            ->description('Detail pencatatan saldo keuangan.')
            ->columns(2)
            ->schema([
              Forms\Components\TextInput::make('amount')
                ->label('Nominal')
                ->required()
                ->numeric()
                ->step(1000),
              Forms\Components\DatePicker::make('date')
                ->label('Tanggal')
                ->required()
                ->default(Carbon::now())
                ->native(false),
              Forms\Components\Textarea::make('name')
                ->label('Catatan')
                ->nullable()
                ->columnSpanFull()
                ->rows(3),
            ]),
        ])
        ->columnSpan(2),

        Forms\Components\Group::make([
          Forms\Components\Section::make('Jenis Transaksi')
            ->collapsible()
            ->schema([
              Forms\Components\Select::make('type_id')
                ->label('Tipe Transaksi')
                ->relationship('payment_type', titleAttribute: 'name')
                ->required()
                ->default(2)
                ->disabledOn('edit')
                ->native(false)
            ]),

          Forms\Components\Section::make('Akun Kas')
            ->collapsible()
            ->schema([
              Forms\Components\Select::make('payment_account_id')
                ->label('Akun Kas')
                ->relationship('payment_account', titleAttribute: 'name')
                ->searchable()
                ->preload()
                ->live()
                ->required()
                ->disabledOn('edit')
                ->afterStateUpdated(function(Forms\Set $set, string $state, string $operation) {
                  $payment_account = PaymentAccount::find($state);
                  if ($operation === 'create') {
                    $set('payment_account_deposit', 'Rp ' . number_format($payment_account->deposit, 0, ',', '.'));
                  }
                }),
              Forms\Components\TextInput::make('payment_account_deposit')
                ->label('Saldo')
                ->disabled()
                ->default(0),
            ]),
        ])
        ->columnSpan(1)
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        //
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make()
          ->mutateRecordDataUsing(function (array $data): array {
            $payment_account = PaymentAccount::find($data['payment_account_id']);
            $data['payment_account_deposit'] = 'Rp. ' . number_format($payment_account->deposit, 0, ',', '.');
            return $data;
          })
          ->before(function (Model $record, array $data, $action): array {
            $amount = $data['amount'] ?? 0;

            $payment_account = PaymentAccount::find($record->payment_account_id);

            if ($record->type_id == 2) {
              $newDeposit = $payment_account->deposit + $record->amount;

              if ($newDeposit < $amount) {
                Notification::make()
                  ->danger()
                  ->title('Proses gagal!')
                  ->body('Saldo akun kas tidak mencukupi.')
                  ->send();
                $action->halt();
              }

              $payment_account->deposit = $newDeposit - $amount;
            } else {
              $payment_account->deposit = $payment_account->deposit - $record->amount + $amount;
            }

            $payment_account->save();
            return $data;
          }),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index'  => Pages\ListPayments::route('/'),
      'create' => Pages\CreatePayment::route('/create'),
      // 'edit' => Pages\EditPayment::route('/{record}/edit'),
    ];
  }
}
