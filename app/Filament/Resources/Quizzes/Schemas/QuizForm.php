<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('topic_id')
                    ->relationship('topic', 'title')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('prompt')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('canonical_answer')
                    ->columnSpanFull(),
                TextInput::make('acceptable_answers'),
                TextInput::make('numeric_tolerance')
                    ->numeric(),
                TextInput::make('eval_type')
                    ->required()
                    ->default('semantic'),
                Select::make('rubric_id')
                    ->relationship('rubric', 'name'),
                TextInput::make('order_index')
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
