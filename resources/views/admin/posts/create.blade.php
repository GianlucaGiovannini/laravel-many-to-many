@extends('layouts.admin')

@section('content')

<h2>Create a new Post</h2>

@include('partials.errors')

<form action="{{route('admin.posts.store')}}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" name="title" id="title" class="form-control" placeholder="Learn php article" aria-describedby="titleHelper" value="{{old('title')}}">
      <small id="titleHelper" class="text-muted">Type the post title, max: 150 </small>
    </div>

    <div class="mb-3">
      <label for="cover_image" class="form-label">Cover Image</label>
      <input type="file" name="cover_image" id="cover_image" class="form-control" placeholder="Learn php article" aria-describedby="cover_imageHelper" >
      <small id="cover_imageHelper" class="text-muted">Url image</small>
    </div>

    <!-- CATEGORIES -->
    <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
            <option value="">Select a category</option>
            @foreach($categories as $category)

            <option value="{{$category->id}}" {{old('category_id') == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
          
            @endforeach
        </select>
    </div>

    <!-- TAGS -->
    <div class="mb-3">
      <label for="tags" class="form-label">Tags</label>
      <select multiple class="form-select" name="tags[]" id="tags" aria-label="Tags">
        <option value="">Select tags</option>
        @forelse($tags as $tag)
        <option value="{{$tag->id}}">{{$tag->name}}</option>

        @empty
        <option value="">No Tags</option>

        @endforelse
      </select>
    </div>

    <div class="mb-3">
      <label for="content" class="form-label">Content</label>
      <textarea class="form-control" name="content" id="content" rows="3">
      {{old('content')}}
      </textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>

@endsection