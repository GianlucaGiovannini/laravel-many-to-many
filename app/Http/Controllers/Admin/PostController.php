<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Mail\NewPostCreated;
use App\Mail\PostUpdatedAdminMessage;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Post $post)
    {
        
        $posts = Post::orderByDesc('id')->get();

        // per far vedere solo i post dell'utente
       $post = Auth::user()->posts;
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
        // dd($request->all());

        // Validate data e taga 
        $val_data = $request->validated();

        $slug = Post::generateSlug($request->title);
        $val_data['slug'] = $slug;

        // assegniamo un post all'utente
        $val_data['user_id'] = Auth::id();

        //IMG verificare se la richiesta contiene un file
        // ddd($request->hasFile('cover_image')); // opzione 1
        // array_key_exists('cover_image', $request->all()) // opzione 2 in plain php
        if($request->hasFile('cover_image')) { 
            //IMG valida il file
            $request->validate([
                'cover_image' => 'nullable|image|max:500'
            ]);
            //IMG lo salviamo nel filesystem e recupero il percorso / path
            $path = Storage::put('post_images', $request->cover_image);

            //ddd($path);

            //IMG passo il percorso/path all'array con i dati validati
            $val_data['cover_image'] = $path;
        }

        //ddd($val_data);

        // create the resource
        $new_post = Post::create($val_data);
        $new_post->tags()->attach($request->tags);

        // anteprima email
        /* return (new NewPostCreated($new_post))->render(); */
        
        //invia mail usando l'istanza dell'utente
        Mail::to($request->user())->send(new NewPostCreated($new_post));
        // invia l'email usando una email
        /*  Mail::to('test@user.com')->send(new NewPostCreated($new_post)); */
        

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

        // IMG update 
        if($request->hasFile('cover_image')) { 
            //IMG valida il file
            $request->validate([
                'cover_image' => 'nullable|image|max:500'
            ]);

            // elimina la vecchia foto 
            Storage::delete($post->cover_image);

            //IMG lo salviamo nel filesystem e recupero il percorso / path
            $path = Storage::put('post_images', $request->cover_image);
            //ddd($path);

            //IMG passo il percorso/path all'array con i dati validati
            $val_data['cover_image'] = $path;
        }
        
        // update the resource
        $post->update($val_data);


        // Sync tags
        $post->tags()->sync($request->tags);

        Mail::to('admin@boolpress.it')->send(new PostUpdatedAdminMessage($post));

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
        Storage::delete($post->cover_image);
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "$post->title deleted successfully");
        
    }
}
