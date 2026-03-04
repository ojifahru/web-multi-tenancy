<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lecturer;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LecturerController extends Controller
{
    public function index(Request $request): View
    {
        /** @var StudyProgram|null $tenant */
        $tenant = $request->attributes->get('tenant');

        abort_unless($tenant instanceof StudyProgram, 404);

        $search = trim((string) $request->string('q'));

        $lecturers = Lecturer::query()
            ->where('study_program_id', $tenant->id)
            ->where('is_active', true)
            ->with('media');

        if ($search !== '') {
            $lecturers->where(function ($query) use ($search): void {
                $query
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('nidn', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $lecturers = $lecturers
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('public.lecturers.index', [
            'tenant' => $tenant,
            'lecturers' => $lecturers,
            'search' => $search,
        ]);
    }
}
