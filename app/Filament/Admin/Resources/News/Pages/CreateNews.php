<?php

namespace App\Filament\Admin\Resources\News\Pages;

use App\Filament\Admin\Resources\News\NewsResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateNews extends CreateRecord
{
    use Translatable;

    protected static string $resource = NewsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenantId = Filament::getTenant()?->getKey();

        if ($tenantId !== null) {
            $data['study_program_id'] = $tenantId;
        }

        $data['author_id'] = Auth::id();

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            //            LocaleSwitcher::make(),
        ];
    }
}
