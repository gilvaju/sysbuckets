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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Bucket') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('bucket.update', $bucket) }}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" value="{{ $bucket->name }}" name="name" id="name">
                        </div>

                        <div class="form-group">
                            <label for="region">Região</label>
                            <input type="text" class="form-control" value="{{ $bucket->region }}" name="region" id="region">
                        </div>

                        <div class="form-group">
                            <label for="key">Key</label>
                            <input type="text" class="form-control" value="{{ $bucket->key }}" name="key" id="key">
                        </div>

                        <div class="form-group">
                            <label for="secret">Secret</label>
                            <input type="text" class="form-control" value="{{ $bucket->secret }}" name="secret" id="secret">
                        </div>

                        <div class="form-group">
                            <label for="expirationTime">Tempo de expiração</label>
                            <input type="text" class="form-control" value="{{ $bucket->expirationTime }}" name="expirationTime" id="expirationTime">
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <a class="btn btn-outline-secondary mr-2 d-flex align-items-center btn-lg" href="{{ route('bucket.index') }}" role="button">Voltar</a>
                            <button type="submit" class="btn btn-success btn-lg" type="button">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
