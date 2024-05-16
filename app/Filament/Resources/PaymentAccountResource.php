<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentAccountResource\Pages;
use App\Models\PaymentAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PaymentAccountResource extends Resource
{
  protected static ?string $model = PaymentAccount::class;

  protected static ?string $navigationIcon = 'heroicon-o-credit-card';
  protected static ?string $navigationGroup = 'Keuangan';
  protected static ?string $label = 'Akun Kas';
  protected static ?int $navigationSort = 2;
  
  public static function getEloquentQuery(): Builder
  {
    $parent = parent::getEloquentQuery();
    if (auth()->user()->checkPermissionTo('*')) return $parent;
    return $parent->where('user_id', '=', auth()->id());
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Akun Kas')
          ->description('Detail Akun Kas')
          ->columns(2)
          ->schema([
            Forms\Components\TextInput::make('name')
              ->label('Nama Akun')
              ->required()
              ->maxLength(255),
            Forms\Components\TextInput::make('deposit')
              ->required()
              ->numeric()
              ->step(1000)
              ->default(0),
          ])
      ]);
  }

  public static function infolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Section::make('Akun Kas')
          ->description('Detail Akun Kas')
          ->columns(3)
          ->schema([
            Infolists\Components\TextEntry::make('name'),
            Infolists\Components\TextEntry::make('deposit')
              ->money(currency: 'IDR', locale: 'id'),
            Infolists\Components\TextEntry::make('created_at')
              ->label('Dibuat')
              ->date('d M Y, H:i'),
            Infolists\Components\TextEntry::make('updated_at')
              ->label('Diperbarui')
              ->date('d M Y, H:i'),
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->label('Nama Akun')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('deposit')
          ->sortable()
          ->formatStateUsing(function (string $state): string {
            return 'Rp. ' . number_format($state, 0, ',', '.');
          }),
        Tables\Columns\TextColumn::make('user.name')
          ->label('Nama Pemilik')
          ->hidden(fn() => !auth()->user()->can('*')),
        Tables\Columns\TextColumn::make('updated_at')
          ->label('Diperbarui')
          ->since()
          ->sortable(),
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make(),
          Tables\Actions\EditAction::make(),
          Tables\Actions\DeleteAction::make(),
        ])
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
      'index'  => Pages\ListPaymentAccounts::route('/'),
      'create' => Pages\CreatePaymentAccount::route('/create'),
      // 'edit'   => Pages\EditPaymentAccount::route('/{record}/edit'),
    ];
  }
}
