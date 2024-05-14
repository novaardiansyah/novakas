<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\PaymentAccount;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
              Forms\Components\TextInput::make('name')
                ->required(),
              Forms\Components\TextInput::make('amount')
                ->required()
                ->numeric(),
              Forms\Components\DatePicker::make('date')
                ->required()
                ->default(Carbon::now())
                ->native(false),
            ]),
        ])
        ->columnSpan(2),

        Forms\Components\Group::make([
          Forms\Components\Section::make('Akun Kas')
          ->description('Pilih akun kas yang akan digunakan.')
          ->columns(1)
          ->schema([
            Forms\Components\Select::make('payment_account.name')
              ->label('Akun Kas')
              ->relationship('payment_account', titleAttribute: 'name')
              ->searchable()
              ->preload()
              ->live()
              ->required()
              ->afterStateUpdated(function(Forms\Set $set, string $state, string $operation) {
                $payment_account = PaymentAccount::find($state);
                if ($operation === 'create') {
                  $set('payment_account.deposit', 'Rp ' . number_format($payment_account->deposit, 2, ',', '.'));
                }
              }),
            Forms\Components\TextInput::make('payment_account.deposit')
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
        Tables\Actions\EditAction::make(),
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
      'index' => Pages\ListPayments::route('/'),
      'create' => Pages\CreatePayment::route('/create'),
      'edit' => Pages\EditPayment::route('/{record}/edit'),
    ];
  }
}
