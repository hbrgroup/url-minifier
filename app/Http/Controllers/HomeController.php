<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function home(): RedirectResponse
    {
        return response()->redirectTo('/_');
    }
}
