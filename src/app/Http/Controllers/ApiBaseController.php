<?php

namespace App\Http\Controllers;

use App\Models\Log;

class ApiBaseController extends Controller
{
    protected $log;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function handleError(\Exception $e)
    {
        $this->log->create([
            'level' => 'error',
            'message' => $e->getMessage(),
            'context' => json_encode($e->getTrace()),
            'client_ip' => request()->ip(),
        ]);

        return response()->json([
            'message' => 'An error occurred. Please try again later.'
        ], 500);
    }
}
