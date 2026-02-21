<?php

namespace App\Filament\Admin\Resources\ContactMessages\Pages;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use Filament\Resources\Pages\ViewRecord;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        if (! $this->getRecord()->is_read) {
            $this->getRecord()->update([
                'is_read' => true,
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
