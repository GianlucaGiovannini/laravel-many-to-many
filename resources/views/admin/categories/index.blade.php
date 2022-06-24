@extends('layouts.admin')

@section('content')


<div class="container">
    <h1 class="mt-3 py-3">All Categories</h1>

    @include('partials.session_message')
    @include('partials.errors')

    <div class="row ">
        <div class="col pe-5 pt-5">
            <form action="" method="post" class="d-flex align-items-center mt-5">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Nome nuova categoria" aria-label="Nome nuova categoria" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Add</button>
                </div>
            </form>
        </div>
        <div class="col">

            <table class="table table-striped table-inverse table-responsive">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td scope="row" class="align-middle">{{$category->id}}</td>
                        <td class="align-middle">
                            <form id="category-{{$category->id}}" action="{{route('admin.categories.update', $category->slug)}}" method="post">
                                @csrf
                                @method('PATCH')
                                <input class="border-0 bg-transparent" type="text" name="name" value="{{$category->name}}">
                            </form>

                        </td>
                        <td class="align-middle">
                            {{$category->slug}}
                        </td>

                        <td class="align-middle">
                            <span class="badge badge-info bg-dark">
                                {{count($category->posts)}}
                            </span>
                        </td>

                        <td>
                            <button form="category-{{$category->id}}" type="submit" class="btn btn-primary m-1 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                </svg>
                            </button>



                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-danger m-1 text-white" data-bs-toggle="modal" data-bs-target="#delete-category-{{$category->id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                </svg>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="delete-category-{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="modelTitle-{{$category->id}}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Cancella <strong>{{$category->slug}}</strong>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Sei sicuro di voler eliminare questa categoria?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Chiudi
                                            </button>
                                            <form action="{{route('admin.categories.destroy', $category->slug)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-white">
                                                    Cancella
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>






                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td scope="row">No categories! Add your first category.</td>

                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>


@endsection