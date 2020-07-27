<?php

namespace Sinofaneliu\LaravelStart\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Sinofaneliu\LaravelStart\Resources\Traits\ApiSuccess;

class Resource extends JsonResource
{
    use ApiSuccess;
}
