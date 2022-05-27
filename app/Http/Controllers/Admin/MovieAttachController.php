<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Inertia\Inertia;

class MovieAttachController extends Controller
{
    public function index(Movie $movie)
    {
        return Inertia::render('Movies/Attach', ['movie' => $movie]);
    }
}
