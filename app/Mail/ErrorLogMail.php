<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorLogMail extends Mailable
{
    use Queueable, SerializesModels;

    public $exceptionMessage;
    public $exceptionFile;
    public $exceptionLine;
    public $exceptionTrace;
    public $exceptionClass;
    public $domainName;
    public $environment;
    public $url;
    public $ip;
    public $time;


    public function __construct($exceptionMessage, $exceptionFile, $exceptionLine, $exceptionTrace, $exceptionClass, $domainName, $environment, $url, $ip, $time)
    {
        $this->exceptionMessage = $exceptionMessage;
        $this->exceptionFile = $exceptionFile;
        $this->exceptionLine = $exceptionLine;
        $this->exceptionTrace = $exceptionTrace;
        $this->exceptionClass = $exceptionClass;
        $this->domainName = $domainName;
        $this->environment = $environment;
        $this->url = $url;
        $this->ip = $ip;
        $this->time = $time;
    }

    public function build()
    {
        return $this->view('emails.error_log')
            ->subject('[Exception: ' . config('app.name') . '] | ' . $this->exceptionClass . ' | Server - ' . $this->domainName . ' | Environment - ' . $this->environment)
            ->with([
                'exceptionMessage' => $this->exceptionMessage,
                'exceptionFile' => $this->exceptionFile,
                'exceptionLine' => $this->exceptionLine,
                'exceptionTrace' => $this->exceptionTrace,
                'exceptionTrace' => $this->exceptionTrace,
                'url' => $this->url,
                'ip' => $this->ip,
                'time' => $this->time,
            ]);
    }
}
