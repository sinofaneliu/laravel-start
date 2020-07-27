<?php

namespace Sinofaneliu\LaravelStart\Resources\Traits;

trait ApiSuccess
{
    public function with($request)
    {
        return [
            'return_code' => 'SUCCESS',
            'return_msg'  => 'OK',
        ];
    }
}
