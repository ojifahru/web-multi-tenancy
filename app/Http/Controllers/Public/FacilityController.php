<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(Request $request): View
    {
        /** @var StudyProgram|null $tenant */
        $tenant = $request->attributes->get('tenant');

        abort_unless($tenant instanceof StudyProgram, 404);

        $search = trim((string) $request->string('q'));

        $facilities = Facility::query()
            ->where('study_program_id', $tenant->id)
            ->with('media')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($facilityQuery) use ($search): void {
                    $facilityQuery
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('public.facilities.index', [
            'tenant' => $tenant,
            'facilities' => $facilities,
            'search' => $search,
        ]);
    }

    public function show(Request $request, string $locale, string $slug): View
    {
        /** @var StudyProgram|null $tenant */
        $tenant = $request->attributes->get('tenant');

        abort_unless($tenant instanceof StudyProgram, 404);

        $baseFacilityQuery = Facility::query()
            ->where('study_program_id', $tenant->id)
            ->with('media');

        $normalizedSlug = trim(urldecode($slug));

        $facility = (clone $baseFacilityQuery)
            ->where(function ($query) use ($normalizedSlug): void {
                $query
                    ->where('slug', $normalizedSlug)
                    ->orWhere('slug->id', $normalizedSlug)
                    ->orWhere('slug->en', $normalizedSlug);
            })
            ->first();

        if (! $facility && $normalizedSlug !== '') {
            $facility = (clone $baseFacilityQuery)
                ->get()
                ->first(function (Facility $candidate) use ($normalizedSlug): bool {
                    return $candidate->matchesSlug($normalizedSlug);
                });
        }

        abort_if(! $facility, 404);

        $relatedFacilities = Facility::query()
            ->where('study_program_id', $tenant->id)
            ->whereKeyNot($facility->id)
            ->with('media')
            ->orderBy('name')
            ->limit(4)
            ->get();

        return view('public.facilities.show', [
            'tenant' => $tenant,
            'facility' => $facility,
            'relatedFacilities' => $relatedFacilities,
        ]);
    }
}
