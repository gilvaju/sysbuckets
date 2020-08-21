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
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Arquivo:</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="files">
                                <label class="custom-file-label" for="files">Procurar</label>
                            </div>
                        </div>
                        <input type="hidden" name="bucket" value="{{ $bucket }}">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Lista de buckets') }}</div>

                <div class="card-body">

                    <ul class="list-group">
                        @foreach($files as $file)
                            <li class="list-group-item">
                                <span>
                                    <form action="{{ route('bucket.destroy', 1) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn text-danger">X</button>
                                        <a href="{{ route('file.edit', 1) }}">{{ $file }}</a>
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
