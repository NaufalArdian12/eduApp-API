<?php

namespace App\Filament\Resources\Videos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VideoForm
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
                TextInput::make('youtube_id')
                    ->required(),
                TextInput::make('youtube_url')
                    ->url()
                    ->required(),
                TextInput::make('duration_seconds')
                    ->numeric(),
                TextInput::make('order_index')
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
