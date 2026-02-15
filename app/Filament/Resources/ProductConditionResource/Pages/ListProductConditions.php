<?php

namespace App\Filament\Resources\ProductConditionResource\Pages;

use App\Filament\Resources\ProductConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductConditions extends ListRecords
{
    protected static string $resource = ProductConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
