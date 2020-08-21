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
                <div class="card-header">{{ __('Upload de arquivos') }}</div>

                <div class="card-body">

                    <form action="{{ route('file.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="files">Selecione seu arquivo:</label>
                            <input type="file" name="file" class="form-control-file" id="files">
                        </div>
                        <input type="hidden" name="bucket" value="{{ $bucket }}">
                        <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">S3 Selecionado: <b>{{ $bucketName }}</b></div>

                <div class="card-body">
                    <ul class="list-group">
                        @foreach($files as $file)
                            <li class="list-group-item">
                                <span>
                                    <form action="{{ route('file.destroy', $file) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="bucket" value="{{ $bucket }}">
                                        <button type="submit" class="btn text-danger">X</button>
                                        <a href="{{ route('file.show', ['id' => $file, 'bucket' => $bucket]) }}">{{ $file }}</a>
                                    </form>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="row mt-3 ml-1">
                        <a href="{{ route('bucket.index') }}" class="btn btn-dark" role="button">Buckets</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
