<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\CvService;
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

    public function __construct(
        protected int $userId
    ) {}

    public function handle(CvService $cvService): void
    {
        $user = User::findOrFail($this->userId);
        $data = $cvService->getUserCvData($this->userId);

        // Generate PDF
        $pdf = Pdf::loadView('cv.pdf', compact('data'))
                  ->setOptions(['isRemoteEnabled' => true]);

        $filePath = 'cvs/' . $user->id . '_CV.pdf';
        Storage::disk('public')->put($filePath, $pdf->output());

        // Email the PDF to user (use user's email if available)
        $email = $user->email ?? config('mail.from.address');
        Mail::to($email)->send(new CvReadyMail($user, $filePath));
    }
}
