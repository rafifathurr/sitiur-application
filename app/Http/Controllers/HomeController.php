<?php

namespace App\Http\Controllers;

use App\Models\Archieve\Gallery;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['gallery'] = Gallery::whereNull('deleted_by')->whereNull('deleted_at')->get();
        return view('home', $data);
    }
}
