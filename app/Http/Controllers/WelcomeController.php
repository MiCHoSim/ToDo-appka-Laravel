<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{
    /**
     * View the main page of the site.
     *
     * @param  Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        return view('welcome');
    }
}
