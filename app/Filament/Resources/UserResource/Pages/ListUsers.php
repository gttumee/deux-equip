<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;


class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
     public function getTabs(): array
    {
        return [  
             '全部' => Tab::make()
            ->badge(User::query()->count()),

            '東京' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query
            ->where('branch', '=', '東京')
            )
            ->badge(User::query()
            ->where('branch', '=', '東京')->count())
            ->badgeColor('gray'),

            '大連' => Tab::make()
             ->modifyQueryUsing(fn (Builder $query) => $query
             ->where('branch', '=', '大連')
        )  
             ->badge(User::query()
             ->where('branch', '=', '大連')->count())
             ->badgeColor('gray'),   
        ];
    }
}