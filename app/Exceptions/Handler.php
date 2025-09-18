<?php

namespace App\Exceptions;

use App\Jobs\SendErrorLogEmail;
use Carbon\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        // Log::info('Exception reported', ['exception' => $exception]);

        if ($this->shouldReport($exception) && !$this->isSMTPException($exception)) {
            // Log::info('Dispatching SendErrorLogEmail job');

            $domainName = request()->getHost();
            $environment = app()->environment();
            $url = request()->fullUrl();
            $ip = request()->ip();
            $time = Carbon::now()->isoFormat('dddd Do [of] MMMM YYYY hh:mm a [IST]');

            SendErrorLogEmail::dispatch(
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTraceAsString(),
                get_class($exception),
                $domainName,
                $environment,
                $url,
                $ip,
                $time
            );
        }

        parent::report($exception);
    }

    protected function isSMTPException(Throwable $exception)
    {
        $smtpErrorMessages = [
            'SMTP Error',
            'Connection could not be established',
            'SMTP server error',
            'Failed to authenticate',
            'SMTP connect() failed',
            'Expected response code',
            'Email send failed',
            'Could not send email',
            'SMTP data not accepted',
            'Error in sending email',
            'SMTP response error',
            'Invalid SMTP response',
            'SMTP connection timeout',
            'SMTP server did not respond',
            'SMTP protocol error',
            'Email send timeout',
            'Failed to connect to mail server',
            'Could not authenticate with SMTP server',
            'Email message rejected',
            'Email server connection failed',
            'Email address rejected',
            'does not comply with addr-spec of RFC 2822'
        ];

        foreach ($smtpErrorMessages as $message) {
            if (strpos($exception->getMessage(), $message) !== false) {
                return true;
            }
        }

        return false;
    }
}
