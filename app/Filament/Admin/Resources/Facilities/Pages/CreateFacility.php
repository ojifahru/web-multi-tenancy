<?php

namespace App\Filament\Admin\Resources\Facilities\Pages;

use App\Filament\Admin\Resources\Facilities\FacilityResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateFacility extends CreateRecord
{
    use Translatable;

    protected static string $resource = FacilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //            LocaleSwitcher::make(),
        ];
    }
}
