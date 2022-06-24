<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderByDesc('id')->get();
        //dd($posts);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        //dd($categories);

        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //dd($request->all());

        // Validate data
        $val_data = $request->validated();
        // Gererate the slug

        $slug = Post::generateSlug($request->title);
        // $slug = Str::slug($request->title, '-');
        //dd($slug);
        $val_data['slug'] = $slug;

        // create the resource
        Post::create($val_data);
        // redirect to a get route
        return redirect()->route('admin.posts.index')->with('message', 'Post creato con successo');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        /* Rule::unique('post')->ignore($post->title, 'title');
        Rule::unique('posts')->ignore($post->id, 'title');
        Rule::unique('posts')->ignore($post); */

        $val_data = $request->validated();
        //dd($val_data);

        $slug = Post::generateSlug($request->title);
        //dd($slug);

        $val_data['slug'] = $slug;


        $post->update($val_data);

        return redirect()->route('admin.posts.index')->with('message', "$post->title modificato con successo!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "$post->title deleted successfully");
        
    }
}
