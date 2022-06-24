<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;

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
        $tags = Tag::all();
        //dd($categories);

        return view('admin.posts.create', compact('categories', 'tags'));;
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

        // Validate data e taga 
        $val_data = $request->validated();

        $slug = Post::generateSlug($request->title);
        $val_data['slug'] = $slug;

        // create the resource
        $new_post = Post::create($val_data);
        $new_post->tags()->attach($request->tags);

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
        $tags = Tag::all();

        /* con questa soluzione poi su compact si mette $data e basta
            $data = [
                'post' => $post,
                'categories' => Category::all(),
                'tags' => Tag::all(),
            ]; 
        */

        return view('admin.posts.edit', compact('post', 'categories', 'tags')); // invece che compact si scrive $data
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
        //dd($request->all());

        // validate data
        $val_data = $request->validated();

        $slug = Post::generateSlug($request->title);
        //dd($slug);

        $val_data['slug'] = $slug;
        
        // update the resource
        $post->update($val_data);

        // Sync tags
        $post->tags()->sync($request->tags);

        // redirect to get route
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
