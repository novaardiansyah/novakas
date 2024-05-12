<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  public static function form(Form $form): Form
  {
    return $form
    ->schema([
        Forms\Components\Section::make('User Information')
          ->description('Please fill your user information')
          ->columns(4)
          ->schema([
            Forms\Components\TextInput::make('name')
              ->required()
              ->maxLength(120),
            Forms\Components\TextInput::make('email')
              ->required()
              ->maxLength(120),
            Forms\Components\TextInput::make('password')
              ->password()
              ->required(fn(string $operation) => $operation === 'create')
              ->disabledOn('edit')
              ->minLength(3)
              ->maxLength(20),
            Forms\Components\Select::make('roles')
              ->multiple()
              ->preload()
              ->native(true)
              ->relationship('roles', 'name')
          ])
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('email')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('roles.name')
          ->sortable(),
        Tables\Columns\TextColumn::make('created_at')
          ->date(),
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
      'index'  => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit'   => Pages\EditUser::route('/{record}/edit'),
    ];
  }
}
