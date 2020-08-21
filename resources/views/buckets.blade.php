@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Cadastro de buckets') }}</div>

                <div class="card-body">

                    <form action="{{ route('bucket.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>

                        <div class="form-group">
                            <label for="region">Região</label>
                            <input type="text" class="form-control" name="region" id="region">
                        </div>

                        <div class="form-group">
                            <label for="key">Key</label>
                            <input type="text" class="form-control" name="key" id="key">
                        </div>

                        <div class="form-group">
                            <label for="secret">Secret</label>
                            <input type="text" class="form-control" name="secret" id="secret">
                        </div>

                        <div class="form-group">
                            <label for="expirationTime">Tempo de expiração</label>
                            <input type="text" class="form-control" name="expirationTime" id="expirationTime">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Lista de buckets') }}</div>

                <div class="card-body">

                    <ul class="list-group">
                        @foreach($buckets as $bucket)
                            <li class="list-group-item">
                                <span>
                                    <form action="{{ route('bucket.destroy', $bucket->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn text-danger">X</button>
                                        <a href="{{ route('bucket.edit', $bucket->id) }}">{{ $bucket->name }}</a>
                                        <a href="{{ route('file.index', $bucket->id) }}" class="no-decoration ml-2">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                              <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                                              <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                                            </svg>
                                        </a>
                                    </form>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
