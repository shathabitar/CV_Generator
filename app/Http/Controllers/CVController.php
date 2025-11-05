<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Jobs\GenerateCvPdf;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use App\Http\Requests\StoreCvRequest;
use App\Services\CvService;

/**
 * @mixin \Illuminate\Http\Request
 */
class CVController extends Controller
{
    public function __construct(
        private CvService $cvService
    ) {}

    public function index()
    {
        // Cache skills for better performance
        $technicalSkills = Cache::remember('technical_skills', 3600, function () {
            return Skill::where('type', 'technical')->get();
        });
        
        $softSkills = Cache::remember('soft_skills', 3600, function () {
            return Skill::where('type', 'soft')->get();
        });

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

        return view('cv.preview', [
            'data' => $data,
            'userId' => null
        ]);
    }

    public function downloadCvPdf($id)
    {
        $data = $this->cvService->getUserCvData($id);
        $user = User::findOrFail($id);

        $pdf = Pdf::loadView('cv.pdf', compact('data'))
                ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download("{$user->name}_CV.pdf");
    }

    public function preview($id)
    {
        $data = $this->cvService->getUserCvData($id);

        return view('cv.preview', [
            'data' => $data,
            'userId' => $id, 
        ]);
    }

    public function generateCv($id)
    {
        User::findOrFail($id); // Verify user exists
        
        GenerateCvPdf::dispatch($id);

        return response()->json([
            'message' => 'Your CV is being generated. You will receive an email when it is ready.'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'about' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
            'reference' => 'nullable|array',
            'certificate' => 'nullable|array',
            'technical_skills' => 'nullable|array',
            'soft_skills' => 'nullable|array',
            'custom_technical_skills' => 'nullable|array',
            'custom_soft_skills' => 'nullable|array',
        ]);
        
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }
        
        $cvData = $this->cvService->composeCvData($validated, null);

        return view('cv.preview', [
            'data' => $cvData,
            'userId' => null // No user ID for form-generated CVs
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $data = $request->all();
        
        // Remove CSRF token from data
        unset($data['_token']);

        $pdf = Pdf::loadView('cv.pdf', compact('data'))
                ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download('My_CV.pdf');
    }
}

