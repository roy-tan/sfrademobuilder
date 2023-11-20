<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Jobs\TestJob;

class TestController extends Controller
{
    public function test()
    {        
        Log::info('TestJob Dispatch');
        TestJob::dispatch();
        return response('Hello World!', 200)
        ->header('Content-Type', 'text/plain');
    }
}