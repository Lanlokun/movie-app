<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
//use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Request;
use Inertia\Inertia;

class TagController extends Controller
{
    public function index()
    {
        return Inertia::render('Tags/Index', [
            'tags' => Tag::query()
                        ->when(Request::input('search'), function ($query, $search){
                            $query->where('tag_name', 'like', "%{$search}%");
                        })->paginate(5)->withQueryString(),
            'filters' => Request::only(['search', 'perPage'])
        ]);
    }

    public  function  create()
    {
        return Inertia::render(('Tags/Create'));
    }

    public function store()
    {
        Tag::create([
            'tag_name' => Request::input('tagName'),
            'slug' => Str::slug(Request::input('tagName'))
        ]);

        return redirect(route('admin.tags.index'))->with('flash.banner', 'Tag Created');
    }



    public  function edit(Tag $tag)
    {
        return Inertia::render('Tags/Edit', [
            'tag' => $tag
        ]);
    }

    public function update(Tag $tag)
    {

        $tag->update([
            'tag_name' => Request::input('tagName'),
            'slug' => Str::slug(Request::input('tagName')),

        ]);
        return redirect()->route('admin.tags.index')->with('flash.banner', 'Tag Updated');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('flash.banner', 'Tag deleted')->with('flash.bannerStyle', 'danger');
    }
}
