<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Tag;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderByDesc('id')->get();
        return view('admin.tags.index', compact('tags'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $val_data = $request->validate([
            'name' => 'required|unique:tags'
        ]);

        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

        Tag::create($val_data);

        return redirect()->back()->with('message', "Tag $slug added successfully");
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {

        $val_data = $request->validate([
            'name' =>
            [
                'required', Rule::unique('categories')->ignore($tag)
            ]
        ]);

        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

        $tag->update($val_data);

        return redirect()->back()->with('message', " $slug updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->back()->with('message', "Category $tag->name removed");
    }
}
