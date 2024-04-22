<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('channel', function () {
    return true;
});
