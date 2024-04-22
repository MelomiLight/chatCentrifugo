<?php

namespace App\Http\Controllers;

use App\Actions\CentrifugoAction;

class ExampleController extends Controller
{

    public CentrifugoAction $action;

    public function __construct(CentrifugoAction $action)
    {
        $this->action = $action;
    }

    public function example(): void
    {
        $this->action->publish('channel', ['value' => 'Hello world!!!']);
    }
}


