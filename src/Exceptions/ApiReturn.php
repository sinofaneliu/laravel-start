<?php

namespace Sinofaneliu\LaravelStart\Exceptions;

use Exception;

class ApiReturn extends Exception
{
    public $code;

    public $message;

    public $data;

    function __construct($code = 'SUCCESS', $message = '', $data = [])
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'return_code' => $this->code,
            'return_msg'  => $this->message,
            'data'        => $this->data,
        ]);
    }

}
