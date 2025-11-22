<?php

namespace App\Filament\Resources\Topics\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('grade_level_id')
                    ->relationship('gradeLevel', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('order_index')
                    ->numeric(),
                TextInput::make('min_videos_before_assessment')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_assessment_enabled')
                    ->required(),
            ]);
    }
}
