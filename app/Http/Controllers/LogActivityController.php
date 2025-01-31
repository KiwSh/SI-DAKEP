<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogActivityController extends Controller
{

    public function index(Request $request)
    {
        $log = Activity::latest()->paginate(10);
        return view('logactivity_index', [
            'models' => $log,
            'title' => 'Log Activity',
        ]);
    }
}