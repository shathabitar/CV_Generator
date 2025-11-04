<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Reference;
use App\Models\Certificate; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Jobs\GenerateCvPdf;

class CVController extends Controller
{

    public function index()
    {
        $technicalSkills = Skill::where('type', 'technical')->get();
        $softSkills = Skill::where('type', 'soft')->get();

        return view('cv.form', compact('technicalSkills', 'softSkills'));
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'about' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'education' => 'nullable|array',
            'education.*.degree' => 'required|string|max:255',
            'education.*.institution' => 'required|string|max:255',
            'education.*.year' => 'required|string|max:50',
            'experience' => 'nullable|array',
            'experience.*.position' => 'required|string|max:255',
            'experience.*.company' => 'required|string|max:255',
            'experience.*.start_date' => 'required|string|max:50',
            'experience.*.end_date' => 'nullable|string|max:50',
            'experience.*.description' => 'nullable|string',
            'reference' => 'nullable|array',
            'reference.*.name' => 'required|string|max:255',
            'reference.*.company' => 'required|string|max:255',
            'reference.*.phone_number' => 'required|string|max:50',
            'reference.*.email' => 'required|email|max:255',
            'certificate' => 'nullable|array',
            'certificate.*.title' => 'required|string|max:255',
            'certificate.*.company' => 'required|string|max:255',
            'certificate.*.date' => 'required|string|max:50',
            'technical_skills' => 'nullable|array',
            'technical_skills.*' => 'string',
            'soft_skills' => 'nullable|array',
            'soft_skills.*' => 'string',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        return view('cv.preview', compact('data'));
    }

   public function downloadCvPdf($id)
    {
        $user = User::with(['educations', 'experiences', 'skills', 'references', 'certificates'])
                    ->findOrFail($id);

        $data = [
            'name' => $user->name,
            'photo' => $user->photo,
            'about' => $user->about,
            'education' => $user->educations->map(fn($edu) => [
                'degree' => $edu->degree,
                'institution' => $edu->institution,
                'year' => $edu->year,
            ]),
            'experience' => $user->experiences->map(fn($exp) => [
                'position' => $exp->position,
                'company' => $exp->company,
                'start_date' => $exp->start_date,
                'end_date' => $exp->end_date,
                'description' => $exp->description,
            ]),
            'technical_skills' => $user->skills->where('type', 'technical')->pluck('skill_name')->toArray(),
            'soft_skills' => $user->skills->where('type', 'soft')->pluck('skill_name')->toArray(),
            'reference' => $user->references->map(fn($ref) => [
                'name' => $ref->name,
                'company' => $ref->company,
                'phone_number' => $ref->phone_number,
                'email' => $ref->email,
            ]),
            'certificate' => $user->certificates->map(fn($cert) => [
                'title' => $cert->title,
                'company' => $cert->company,
                'date' => $cert->date,
            ]),
        ];

        $pdf = Pdf::loadView('cv.pdf', compact('data'))
                ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download("{$user->name}_CV.pdf");
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'about' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
            'reference' => 'nullable|array',
            'certificate' => 'nullable|array',
            'technical_skills' => 'nullable|array',
            'soft_skills' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'about' => $request->about,
            'photo' => $request->photo ? $request->file('photo')->store('photos', 'public') : null,
        ]);

        $skills = Skill::whereIn('skill_name', array_merge(
            $request->technical_skills ?? [],
            $request->soft_skills ?? []
        ))->pluck('id');
        $user->skills()->attach($skills);

        foreach ($request->education ?? [] as $edu) {
            $education = Education::create($edu);
            $user->educations()->attach($education->id);
        }

        foreach ($request->experience ?? [] as $exp) {
            $experience = Experience::create($exp);
            $user->experiences()->attach($experience->id);
        }

        foreach ($request->reference ?? [] as $ref) {
            $reference = Reference::create($ref);
            $user->references()->attach($reference->id);
        }

        foreach ($request->certificate ?? [] as $cert) {
            $certificate = Certificate::create($cert);
            $user->certificates()->attach($certificate->id);
        }

        return redirect()->route('cv.preview', $user->id);
    }

    
    public function preview($id)
{
    $user = User::with(['educations', 'experiences', 'skills', 'references', 'certificates'])
                ->findOrFail($id);

    $data = [
        'name' => $user->name,
        'photo' => $user->photo,
        'about' => $user->about,
        'education' => $user->educations->map(fn($edu) => [
            'degree' => $edu->degree,
            'institution' => $edu->institution,
            'year' => $edu->year,
        ]),
        'experience' => $user->experiences->map(fn($exp) => [
            'position' => $exp->position,
            'company' => $exp->company,
            'start_date' => $exp->start_date,
            'end_date' => $exp->end_date,
            'description' => $exp->description,
        ]),
        'technical_skills' => $user->skills->where('type', 'technical')->pluck('skill_name')->toArray(),
        'soft_skills' => $user->skills->where('type', 'soft')->pluck('skill_name')->toArray(),
        'reference' => $user->references->map(fn($ref) => [
            'name' => $ref->name,
            'company' => $ref->company,
            'phone_number' => $ref->phone_number,
            'email' => $ref->email,
        ]),
        'certificate' => $user->certificates->map(fn($cert) => [
            'title' => $cert->title,
            'company' => $cert->company,
            'date' => $cert->date,
        ]),
    ];

    return view('cv.preview', [
        'data' => $data,
        'userId' => $user->id, 
    ]);
}


    public function downloadPdf(Request $request)
    {
        $data = json_decode($request->input('data'), true);

        $pdf = Pdf::loadView('cv.pdf', compact('data'))
                ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download('My_CV.pdf');
    }

    public function generateCv($id)
    {
        GenerateCvPdf::dispatch($id);

        return response()->json([
            'message' => 'Your CV is being generated. You will receive an email when it’s ready.'
        ]);
    }


}

