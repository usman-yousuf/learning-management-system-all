<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return view('common::index');
    }
}
