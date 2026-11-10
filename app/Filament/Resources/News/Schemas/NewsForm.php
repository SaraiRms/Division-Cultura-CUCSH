<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),

                TextInput::make('slug')
                    ->required(),

                TextInput::make('summary')
                    ->default(null),

                Textarea::make('body')
                    ->default(null)
                    ->columnSpanFull(),

                FileUpload::make('image_path')
                    ->image(),

                Toggle::make('is_featured')
                    ->required(),

                Select::make('status')
                    ->options([
            'draft' => 'Draft',
            'pending' => 'Pending',
            'published' => 'Published',
            'rejected' => 'Rejected',

        ])
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at'),
                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->default(null),
                TextInput::make('approved_by')
                    ->numeric()
                    ->default(null),
                DateTimePicker::make('approved_at'),
            
            ]);
    }
    
}
