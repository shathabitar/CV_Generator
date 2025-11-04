<?php

namespace App\Jobs;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CvReadyMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCvPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        $user = User::with(['educations', 'experiences', 'skills', 'references', 'certificates'])
                    ->findOrFail($this->userId);

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

        // Generate PDF
        // $pdf = Pdf::loadView('cv.pdf', compact('data'))
        //           ->setOptions(['isRemoteEnabled' => true]);

        // $filePath = 'cvs/' . $user->id . '_CV.pdf';
        // Storage::disk('public')->put($filePath, $pdf->output());


        // Optionally, email the PDF to user
        Mail::to('sbitar@cpf.jo')->send(new CvReadyMail($user, $filePath));
    }
}
