<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\StudyProgram;
use App\Support\PublicContentSanitizer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(private PublicContentSanitizer $contentSanitizer) {}

    public function index(Request $request): View
    {
        /** @var StudyProgram|null $tenant */
        $tenant = $request->attributes->get('tenant');

        abort_unless($tenant instanceof StudyProgram, 404);

        $search = trim((string) $request->string('q'));

        $news = News::query()
            ->where('study_program_id', $tenant->id)
            ->where('status', 'published')
            ->with('media')
            ->where('published_at', '<=', now());

        if ($search !== '') {
            $news->where(function ($query) use ($search): void {
                $query
                    ->where('title', 'like', '%'.$search.'%')
                    ->orWhere('excerpt', 'like', '%'.$search.'%')
                    ->orWhere('content', 'like', '%'.$search.'%');
            });
        }

        $news = $news
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        return view('public.news.index', [
            'tenant' => $tenant,
            'news' => $news,
            'search' => $search,
        ]);
    }

    public function show(Request $request, string $locale, string $slug): View
    {
        /** @var StudyProgram|null $tenant */
        $tenant = $request->attributes->get('tenant');

        abort_unless($tenant instanceof StudyProgram, 404);

        $locale = app()->getLocale();

        $baseNewsQuery = News::query()
            ->where('study_program_id', $tenant->id)
            ->where('status', 'published')
            ->with('media')
            ->where('published_at', '<=', now());

        $normalizedSlug = trim(urldecode($slug));

        $newsItem = (clone $baseNewsQuery)
            ->where(function ($query) use ($normalizedSlug): void {
                $query
                    ->where('slug', $normalizedSlug)
                    ->orWhere('slug->id', $normalizedSlug)
                    ->orWhere('slug->en', $normalizedSlug);
            })
            ->first();

        if (! $newsItem && $normalizedSlug !== '') {
            $newsItem = (clone $baseNewsQuery)
                ->get()
                ->first(function (News $candidate) use ($normalizedSlug): bool {
                    return $candidate->matchesSlug($normalizedSlug);
                });
        }

        abort_if(! $newsItem, 404);

        $relatedNews = News::query()
            ->where('study_program_id', $tenant->id)
            ->where('status', 'published')
            ->with('media')
            ->whereKeyNot($newsItem->id)
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $newsContent = $newsItem->resolveLocalizedValue('content', $locale);

        return view('public.news.show', [
            'tenant' => $tenant,
            'newsItem' => $newsItem,
            'relatedNews' => $relatedNews,
            'newsContentHtml' => $this->contentSanitizer->sanitizeHtml($newsContent, 'Konten berita belum tersedia.'),
        ]);
    }
}
