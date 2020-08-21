@extends('layouts.app')$bucket->find($id)

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
