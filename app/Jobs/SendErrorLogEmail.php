<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorLogMail;

class SendErrorLogEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exceptionMessage;
    protected $exceptionFile;
    protected $exceptionLine;
    protected $exceptionTrace;
    protected $exceptionClass;
    protected $domainName;
    protected $environment;
    protected $url;
    protected $ip;
    protected $time;


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

    public function handle()
    {
        $message = new ErrorLogMail(
            $this->exceptionMessage,
            $this->exceptionFile,
            $this->exceptionLine,
            $this->exceptionTrace,
            $this->exceptionClass,
            $this->domainName,
            $this->environment,
            $this->url,
            $this->ip,
            $this->time

        );


        if (config('errormail.error_mail_enabled')) {
            $message = $message->onQueue('default');
            Mail::to(config('errormail.error_mail_to'))->queue($message);
        }
    }
}
