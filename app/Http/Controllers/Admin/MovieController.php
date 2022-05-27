<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Request;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index()
    {

        return Inertia::render('Movies/Index', [
            'movies' => Movie::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })->paginate(5)->withQueryString(),
            'filters' => Request::only(['search', 'perPage'])
        ]);
    }

    public function store()
    {
        $movie = Movie::where('tmdb_id', Request::input('movieTMDbId'))->exists();
        if ($movie) {

            return Redirect::back()->with('flash.banner', 'Movie Exists');
        }

        $apiMovie = Http::asJson()->get(config('services.tmdb.endpoint').'movie/'.Request::input('movieTMDBId'). '?api_key=' . config('services.tmdb.secret'). '&language=en-US');

        if ($apiMovie->successful()) {
            $newMovie = $apiMovie->json();

            $created_movie = Movie::create([
                'tmdb_id' => $apiMovie['id'],
                'title' => $apiMovie['title'],
                'runtime' => $apiMovie['runtime'],
                'rating' => $apiMovie['vote_average'],
                'release_date' => $apiMovie['release_date'],
                'lang' => $apiMovie['original_language'],
                'video_format' => 'HD',
                'is_public' => false,
                'overview' => $apiMovie['overview'],
                'poster_path' => $apiMovie['poster_path'],
                'backdrop_path' => $apiMovie['backdrop_path']
            ]);
            $tmdb_genres = $apiMovie['genres'];
            $tmdb_genres_ids = collect($tmdb_genres)->pluck('id');
            $genres = Genre::whereIn('tmdb_id', $tmdb_genres_ids)->get();
            $created_movie->genres()->attach($genres);
            return Redirect::back()->with('flash.banner', 'Movie Created');

        } else {
            return Redirect::back()->with('flash.banner', 'APi Error')->with('flash.bannerStyle', 'danger');

        }
    }

    public  function edit(Movie $movie)
    {
        return Inertia::render('Movies/Edit', ['movie' => $movie]);
    }

    public function update(Movie $movie)
    {
        $validated = Request::validate([
            'title' => 'required',
            'poster_path' => 'required',
            'runtime' => 'required',
            'lang' => 'required',
            'video_format' => 'required',
            'rating' => 'required',
            'backdrop_path' => 'required',
            'overview' => 'required',
            'is_public' => 'required'
        ]);

        $movie->update($validated);

        return Redirect::route('admin.movies.index')->with('flash.banner', 'Movie Updated');
    }

    public function destroy(Movie $movie){

        $movie  ->genres()->sync([]);
        $movie->delete();
        return Redirect::route('admin.movies.index')->with('flash.banner', 'Movie Deleted')->with('flash.bannerStyle', 'danger');


    }
}
