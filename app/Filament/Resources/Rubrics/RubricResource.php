<?php

namespace App\Filament\Resources\Rubrics;

use App\Filament\Resources\Rubrics\Pages\CreateRubric;
use App\Filament\Resources\Rubrics\Pages\EditRubric;
use App\Filament\Resources\Rubrics\Pages\ListRubrics;
use App\Filament\Resources\Rubrics\Schemas\RubricForm;
use App\Filament\Resources\Rubrics\Tables\RubricsTable;
use App\Models\Rubric;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RubricResource extends Resource
{
    protected static ?string $model = Rubric::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Rubric';

    public static function form(Schema $schema): Schema
    {
        return RubricForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RubricsTable::configure($table);
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
            'index' => ListRubrics::route('/'),
            'create' => CreateRubric::route('/create'),
            'edit' => EditRubric::route('/{record}/edit'),
        ];
    }
}
