<?php

namespace Sinofaneliu\LaravelStart\Resources;

use Sinofaneliu\LaravelStart\Resources\Traits\ApiSuccess;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseCollection;

class ResourceCollection extends BaseCollection
{
    use ApiSuccess;
}
