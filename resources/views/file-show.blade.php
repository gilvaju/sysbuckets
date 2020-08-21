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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">S3 Selecionado: <b>{{ $bucketName }}</b></div>

                <div class="card-body">
                    <img width="100%" src={{route('storage.file', ['filePath' => $path])}} />
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row mt-3 ml-1">
                <a href="{{ route('file.index', $bucket) }}" class="btn btn-dark" role="button">Voltar</a>
            </div>
        </div>
    </div>
</div>
@endsection
