<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Lecturer;
use App\Models\News;
use App\Models\StudyProgram;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    private const SUPPORTED_LOCALES = ['id', 'en'];

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        /** @var StudyProgram|null $tenant */
        $tenant = $request->attributes->get('tenant');

        abort_unless($tenant instanceof StudyProgram, 404);

        $urls = $this->buildStaticUrls($tenant)
            ->merge($this->buildNewsUrls($tenant))
            ->merge($this->buildLecturerUrls($tenant))
            ->merge($this->buildFacilityUrls($tenant))
            ->unique('loc')
            ->values();

        return response()
            ->view('sitemap', ['urls' => $urls], 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function buildStaticUrls(StudyProgram $tenant): Collection
    {
        $routeNames = [
            'public.home',
            'public.news.index',
            'public.lecturers.index',
            'public.facilities.index',
            'public.about',
            'public.contact',
        ];

        return collect($routeNames)
            ->flatMap(function (string $routeName) use ($tenant): array {
                return $this
                    ->buildLocalizedEntries(
                        $routeName,
                        fn(string $locale): array => [],
                        $tenant->updated_at?->toAtomString()
                    )
                    ->all();
            });
    }

    private function buildNewsUrls(StudyProgram $tenant): Collection
    {
        $newsItems = News::query()
            ->where('study_program_id', $tenant->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->get(['id', 'slug', 'updated_at']);

        return $newsItems->flatMap(function (News $newsItem): array {
            return $this
                ->buildLocalizedEntries(
                    'public.news.show',
                    function (string $locale) use ($newsItem): ?array {
                        $slug = $newsItem->resolveSlug($locale);

                        if ($slug === null || trim($slug) === '') {
                            return null;
                        }

                        return ['slug' => $slug];
                    },
                    $newsItem->updated_at?->toAtomString()
                )
                ->all();
        });
    }

    private function buildFacilityUrls(StudyProgram $tenant): Collection
    {
        $facilities = Facility::query()
            ->where('study_program_id', $tenant->id)
            ->get(['id', 'slug', 'updated_at']);

        return $facilities->flatMap(function (Facility $facility): array {
            return $this
                ->buildLocalizedEntries(
                    'public.facilities.show',
                    function (string $locale) use ($facility): ?array {
                        $slug = $facility->resolveSlug($locale);

                        if ($slug === null || trim($slug) === '') {
                            return null;
                        }

                        return ['slug' => $slug];
                    },
                    $facility->updated_at?->toAtomString()
                )
                ->all();
        });
    }

    private function buildLecturerUrls(StudyProgram $tenant): Collection
    {
        $lecturers = Lecturer::query()
            ->where('study_program_id', $tenant->id)
            ->where('is_active', true)
            ->get(['id', 'slug', 'updated_at']);

        return $lecturers->flatMap(function (Lecturer $lecturer): array {
            return $this
                ->buildLocalizedEntries(
                    'public.lecturers.show',
                    function (string $locale) use ($lecturer): ?array {
                        if (! is_string($lecturer->slug) || trim($lecturer->slug) === '') {
                            return null;
                        }

                        return ['slug' => $lecturer->slug];
                    },
                    $lecturer->updated_at?->toAtomString()
                )
                ->all();
        });
    }

    /**
     * @param  Closure(string): (array|null)  $parametersResolver
     */
    private function buildLocalizedEntries(string $routeName, Closure $parametersResolver, ?string $lastModified): Collection
    {
        $localizedUrls = collect(self::SUPPORTED_LOCALES)
            ->mapWithKeys(function (string $locale) use ($routeName, $parametersResolver): array {
                $parameters = $parametersResolver($locale);

                if ($parameters === null) {
                    return [];
                }

                return [
                    $locale => localized_route($routeName, $parameters, $locale),
                ];
            });

        if ($localizedUrls->isEmpty()) {
            return collect();
        }

        $defaultLocale = (string) config('app.locale', 'id');
        $xDefault = $localizedUrls->get($defaultLocale, $localizedUrls->first());

        $alternates = $localizedUrls
            ->map(fn(string $href, string $locale): array => [
                'hreflang' => $locale,
                'href' => $href,
            ])
            ->values();

        if (is_string($xDefault) && $xDefault !== '') {
            $alternates->push([
                'hreflang' => 'x-default',
                'href' => $xDefault,
            ]);
        }

        return $localizedUrls
            ->map(function (string $url) use ($alternates, $lastModified): array {
                return [
                    'loc' => $url,
                    'lastmod' => $lastModified,
                    'alternates' => $alternates->all(),
                ];
            })
            ->values();
    }
}
