<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfitController extends Controller
{
    public function index(){
        return Inertia::render('Profits/index');
    }
}
