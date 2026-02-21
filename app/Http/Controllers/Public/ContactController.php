<?php

namespace App\Http\Controllers\Public;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\StudyProgram;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $this->resolveTenant($request);

        return view('public.contact', [
            'tenant' => $tenant,
        ]);
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $tenant = $this->resolveTenant($request);
        $validatedData = $request->validated();

        $contactMessage = $tenant->contactMessages()->create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'] ?? null,
            'subject' => $validatedData['subject'],
            'message' => $validatedData['message'],
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
        ]);

        $this->sendAdminNotification($tenant, $contactMessage);

        return redirect()
            ->route('public.contact')
            ->with('success', 'Pesan Anda berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }

    private function resolveTenant(Request $request): StudyProgram
    {
        /** @var StudyProgram|null $tenant */
        $tenant = $request->attributes->get('tenant');

        abort_unless($tenant instanceof StudyProgram, 404);

        return $tenant;
    }

    private function sendAdminNotification(StudyProgram $tenant, ContactMessage $contactMessage): void
    {
        $recipients = $tenant->users()->get();

        if ($recipients->isEmpty()) {
            return;
        }

        $viewUrl = ContactMessageResource::getUrl(
            name: 'view',
            parameters: ['record' => $contactMessage],
            panel: 'admin',
            tenant: $tenant,
        );

        Notification::make()
            ->title('Pesan kontak baru masuk')
            ->body(Str::limit("{$contactMessage->name} • {$contactMessage->subject}", 120))
            ->icon('heroicon-o-envelope')
            ->iconColor('warning')
            ->actions([
                Action::make('view-contact-message')
                    ->label('Lihat Pesan')
                    ->markAsRead()
                    ->url($viewUrl),
                Action::make('markAsUnread')
                    ->button()
                    ->markAsUnread(),
            ])
            ->sendToDatabase($recipients, isEventDispatched: true);
    }
}
