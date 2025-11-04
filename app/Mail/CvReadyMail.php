<?php


namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class CvReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $filePath;

    public function __construct($user, $filePath)
    {
        $this->user = $user;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Your CV is ready')
                    ->view('cv_ready')
                    ->attach(storage_path('app/public/' . $this->filePath));
    }
}
