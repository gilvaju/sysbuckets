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
                        @foreach($files as $key => $file)
                            <li class="list-group-item">
                                <button type="button" class="btn text-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{$key}}">X</button>
                                <a href="{{ url($file['url']) }}" class="ml-2">{{ $file['name'] }}</a>
                                <!-- Modal -->
                                <div class="modal fade" id="deleteModal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="deleteModal{{$key}}Label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModal{{$key}}Label">Apagar arquivo</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            </div>
                                            <div class="modal-body"> Você quer mesmo apagar? </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                                <form action="{{ route('file.destroy', $file['name']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="bucket" value="{{ $bucket }}">
                                                    <button type="submit" class="btn btn-danger">Sim</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $( document ).on( "click", "#delete", function() {
                                        $('#deleteModal{{$key}}').modal('hide');
                                    });
                                </script>


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
