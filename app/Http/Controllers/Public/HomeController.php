<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Lecturer;
use App\Models\News;
use App\Models\StudyProgram;
use App\Support\PublicContentSanitizer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
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

        $latestNews = News::query()
            ->where('study_program_id', $tenant->id)
            ->where('status', 'published')
            ->with('media')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $lecturers = Lecturer::query()
            ->where('study_program_id', $tenant->id)
            ->where('is_active', true)
            ->with('media')
            ->orderBy('name')
            ->limit(4)
            ->get();

        $facilities = Facility::query()
            ->where('study_program_id', $tenant->id)
            ->with('media')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $activeLecturersCount = Lecturer::query()
            ->where('study_program_id', $tenant->id)
            ->where('is_active', true)
            ->count();

        $accreditation = $tenant->resolveLocalizedValue('accreditation');
        $vision = $tenant->resolveLocalizedValue('vision');
        $mission = $tenant->resolveLocalizedValue('mission');
        $objectives = $tenant->resolveLocalizedValue('objectives');

        $heroStats = [
            [
                'label' => __('home.stats.students.label'),
                'value' => $tenant->student ?? __('common.placeholders.not_available'),
                'desc' => __('home.stats.students.desc'),
                'icon' => 'students',
            ],
            [
                'label' => __('home.stats.lecturers.label'),
                'value' => $activeLecturersCount,
                'desc' => __('home.stats.lecturers.desc'),
                'icon' => 'lecturers',
            ],
            [
                'label' => __('home.stats.accreditation.label'),
                'value' => $accreditation ?? __('common.placeholders.not_available'),
                'desc' => __('home.stats.accreditation.desc'),
                'icon' => 'accreditation',
            ],
            [
                'label' => __('home.stats.established.label'),
                'value' => $tenant->established_year ?? __('common.placeholders.not_available'),
                'desc' => __('home.stats.established.desc'),
                'icon' => 'calendar',
            ],
        ];

        return view('public.home', [
            'tenant' => $tenant,
            'latestNews' => $latestNews,
            'lecturers' => $lecturers,
            'facilities' => $facilities,
            'visionHtml' => $this->contentSanitizer->sanitizeHtml($vision, __('home.about.vision_fallback')),
            'missionHtml' => $this->renderListContent($mission, __('home.about.mission_fallback')),
            'objectivesHtml' => $this->renderListContent($objectives, __('home.about.objectives_fallback')),
            'heroStats' => $heroStats,
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
