<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components AS FormComponents;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Page implements HasForms
{
  use InteractsWithForms;

  protected static ?string $navigationIcon = 'heroicon-o-user';
  protected static ?string $navigationGroup = 'Pengaturan';
  protected static string $view = 'filament.pages.edit-profile';
  protected static ?string $navigationLabel = 'Profile';
  protected static ?string $slug = 'profile/edit-profile';
  protected static ?int $navigationSort = 2;

  public ?array $data = []; 

  public function mount(): void 
  {
    $this->form->fill(auth()->user()->attributesToArray());
  }

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        FormComponents\Section::make('Profile')
          ->description('Perbarui detail profile anda disini.')
          ->schema([
            FormComponents\TextInput::make('name')
              ->label('Nama Lengkap')
              ->maxLength(120)
              ->required(),
            FormComponents\TextInput::make('password')
              ->password()
              ->minLength(8)
              ->maxLength(20)
              ->dehydrateStateUsing(fn ($state) => Hash::make($state))
              ->dehydrated(fn ($state) => filled($state)),
            FormComponents\TextInput::make('email')
              ->disabled(),
          ])
          ->columns(3),
      ])
      ->statePath('data');
  }

  protected function getFormActions(): array
  {
    return [
      Action::make('save')
      ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
      ->submit('save'),
    ];
  }

  public function save(): void
  {
    try {
      $data = $this->form->getState();
      $user = auth()->user();
      $user->update($data);
    } catch (Halt $exception) {
      return;
    }

    Notification::make() 
      ->success()
      ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
      ->send(); 
  }
}