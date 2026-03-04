<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StudyProgram;
use App\Support\PublicContentSanitizer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __construct(private PublicContentSanitizer $contentSanitizer) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        /** @var StudyProgram|null $tenant */
        $tenant = $request->attributes->get('tenant');

        abort_unless($tenant instanceof StudyProgram, 404);

        $about = $tenant->resolveLocalizedValue('about');
        $description = $tenant->resolveLocalizedValue('description');
        $vision = $tenant->resolveLocalizedValue('vision');
        $mission = $tenant->resolveLocalizedValue('mission');
        $objectives = $tenant->resolveLocalizedValue('objectives');

        return view('public.about', [
            'tenant' => $tenant,
            'aboutHtml' => $this->contentSanitizer->sanitizeHtml(
                $about ?: $description,
                'Profil program studi belum tersedia.',
            ),
            'visionHtml' => $this->contentSanitizer->sanitizeHtml($vision, 'Visi program studi belum tersedia.'),
            'missionHtml' => $this->renderListContent($mission, 'Misi belum tersedia.'),
            'objectivesHtml' => $this->renderListContent($objectives, 'Tujuan belum tersedia.'),
        ]);
    }

    protected function renderListContent(?string $value, string $fallback): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return e($fallback);
        }

        $looksLikeList = str($value)->contains(['<ol', '<ul', '<li']);

        if ($looksLikeList) {
            return $this->contentSanitizer->sanitizeHtml($value, $fallback);
        }

        $normalized = preg_replace('/<\/(p|div|h[1-6]|li)>/i', "\n", $value);
        $normalized = preg_replace('/<br\s*\/?>/i', "\n", (string) $normalized);
        $plainText = strip_tags((string) $normalized);

        $lines = array_filter(
            array_map(
                'trim',
                preg_split('/\r?\n+/', html_entity_decode($plainText, ENT_QUOTES | ENT_HTML5, 'UTF-8'))
            )
        );

        if (count($lines) === 0) {
            return e($fallback);
        }

        $html = '<ol class="list-decimal pl-5">';

        foreach ($lines as $line) {
            $html .= '<li>' . e($line) . '</li>';
        }

        $html .= '</ol>';

        return $this->contentSanitizer->sanitizeHtml($html, $fallback);
    }
}
