<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Menu;
use Filament\Tables;
use App\Models\MealPlan;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class WeeklyMenuWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('Randomize Menu for Next Week')
                    ->action(fn() => self::generateWorkweekMealPlan())
                    ->requiresConfirmation()
                    ->label('Randomize Menu for Next Week')
                    ->icon('heroicon-o-arrow-path'),
            ])
            ->query(
                MealPlan::query()
                    ->with('menu')
            )
            ->columns([
                TextColumn::make('date')
                    ->date('l, F d, Y'),
                TextColumn::make('menu.name'),
            ]);
    }

    public static function generateWorkweekMealPlan()
    {
        $startDate = now()->next(Carbon::MONDAY);
        $endDate = $startDate->copy()->addDays(4);

        MealPlan::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->delete();

        $menus = Menu::query()
            ->pluck('id')
            ->shuffle()
            ->toArray();

        if (count($menus) < 5) {
            throw new \Exception('Not enough unique menu items for the workweek.');
        }

        foreach (range(0, 4) as $i) {
            MealPlan::create([
                'date' => $startDate->copy()->addDays($i),
                'menu_id' => $menus[$i],
            ]);
        }
    }
}
