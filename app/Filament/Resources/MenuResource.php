<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Menu;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MenuResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MenuResource\RelationManagers;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationBadgeTooltip = 'The number of menus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')
                            ->extraAlpineAttributes(['@input' => '$el.value = $el.value.toUpperCase()'])
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->live()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                Grid::make(1)
                    ->schema([
                        Textarea::make('description')
                            ->extraAlpineAttributes(['@input' => '$el.value = $el.value.toUpperCase()'])
                            ->dehydrateStateUsing(fn($state) => strtoupper($state))
                            ->live()
                            ->rows(3)
                            ->required(),
                    ]),
                Grid::make(1)
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->imageEditor(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
