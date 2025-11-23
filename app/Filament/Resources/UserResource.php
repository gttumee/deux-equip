<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
      public static function getNavigationBadge(): ?string
    {
    return static::getModel()::all()->count();
    }
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    protected static ?string $pluralModelLabel = '社員管理';
    protected static ?string $modelLabel = '社員管理';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')
                ->required()
                ->label('名前')
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->label('メール')
                ->required()
                ->maxLength(255),
            TextInput::make('password')
                ->password()
                ->label('パスワード')
                ->required()
                ->minLength(8)
                ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
            Select::make('role')
                ->label('権限')
                ->options(config('status.admin'))
                ->default('Staff')
                ->required()
                ->columnSpan(1),
            Select::make('branch')
                ->label('支店')
                ->options(config('status.branch'))
                ->default('Tokyo')
                ->required()
                ->columnSpan(1),
            DatePicker::make('start_date')
            ->label('入社日')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->sortable()
                ->label('名前'),
                TextColumn::make('role')
                ->sortable()
                ->label('権限'),
                TextColumn::make('branch')
                ->sortable()
                ->Label('支店'),
                TextColumn::make('start_date')
                ->sortable()
                ->Label('入社日'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}