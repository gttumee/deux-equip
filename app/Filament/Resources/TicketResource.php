<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;


class TicketResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
    return static::getModel()::all()->count();
    }
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationLabel = 'チケット';
    protected static ?string $navigationIcon = 'heroicon-s-ticket';
    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel = 'チケット';
    protected static ?string $modelLabel = 'チケット';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
            ->schema([
           TextInput::make('name')
                ->label('作業名'),
                Select::make('status')
                ->label('進捗状況')
                ->options(config('status.statuses'))
                ->default('新規')
                ->required(),
                 TextInput::make('end_time')
                ->label('終了時間'),
                   TextInput::make('types')
                ->label('種類'),
                DatePicker::make('end_date')
                ->label('終了日'),
                Select::make('project_id')
                ->options(Project::all()
                ->pluck('name', 'id'))
                ->required()
                ->label('案件名'),
                Select::make('user_id')
                ->options(User::all()
                ->pluck('name', 'id'))
                ->label('担当者')
                ->required(),
                TextInput::make('hours')
                ->label('作業時間'),
                TextInput::make('client_name')
                ->label('依頼者'),
                Textarea::make('explanation')
                ->label('詳細')
                  ->columnSpan([
                'sm' => 1,
                'xl' => 3,
                '2xl' => 2,
            ]),
    ]),
             
               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->sortable()
                 ->label('作業名'),
                TextColumn::make('types')
                ->sortable()
                ->label('種類'),
                TextColumn::make('status')
                ->badge()
                ->sortable()
                ->label('進捗状況'),
                TextColumn::make('created_at')
                ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y-m-d'))
                ->sortable()
                ->label('開始日'),
                TextColumn::make('end_date')
                ->sortable()
                 ->badge()
                ->label('終了日'),               
                 TextColumn::make('end_time')
                ->sortable()
                ->label('終了時間'),
                 TextColumn::make('project.name')
                ->label('案件名')
                ->sortable()
                ->searchable(),
                TextColumn::make('client_name')
                ->sortable()
                ->label('依頼者'),
                TextColumn::make('user.name')
                ->sortable()
                ->label('担当者'),
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
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('client_name')
                    ->columnSpanFull(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}