<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Carbon\Carbon;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;


class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [  
            '本日納品' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query
            ->where('end_date', 'like', Carbon::today()->format('Y-m-d') . '%')
            ->where('status', '!=', '完了')
        )    
                ->badge(Ticket::query()
                ->where('end_date', 'like', Carbon::today()->format('Y-m-d') . '%')
                ->where('status', '!=', '完了')->count())
                ->badgeColor('gray'),
                
            '明日納品' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query
                ->where('end_date', 'like', Carbon::tomorrow()->format('Y-m-d') . '%')
                ->where('status', '!=', '完了')
                )
                ->badge(Ticket::query()
                ->where('end_date', 'like', Carbon::tomorrow()->format('Y-m-d') . '%')
                ->where('status', '!=', '完了')->count())
                ->badgeColor('info'),

                '今週納品' => Tab::make()
                 ->modifyQueryUsing(fn (Builder $query) => $query
                 ->whereBetween('end_date', [
                Carbon::now()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->endOfWeek()->format('Y-m-d'),
          ])
            ->where('status', '!=', '完了')
       )
              ->badge(
            Ticket::query()
            ->whereBetween('end_date', [
                Carbon::now()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->endOfWeek()->format('Y-m-d'),
            ])
            ->where('status', '!=', '完了')
            ->count()
    )
    ->badgeColor('warning'),
            
           '未完成' => Tab::make() 
                ->modifyQueryUsing(fn (Builder $query) => $query
                ->where('end_date', '<', now())
                ->where('status', '!=', '完了'))
                ->badge('! ' . Ticket::query()->where('end_date', '<', now())->where('status', '!=', '完了')->count())
                ->badgeColor(Ticket::query()
                    ->where('end_date', '<', Carbon::today())
                    ->where('status', '!=', '完了')
                    ->count() > 0 ? 'danger' : 'success'),
             
            '完成' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Finish'))
                ->badge(Ticket::query()->where('status', '完了')->count())
                ->badgeColor('success'),  
                '全部' => Tab::make()
            ->badge(Ticket::query()->count()), 
        ];
    } 
}