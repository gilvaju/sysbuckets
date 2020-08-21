@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifique seu email!') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Email de verificação foi enviado para você.') }}
                        </div>
                    @endif

                    {{ __('Antes de proceder, verifique seu email.') }}
                    {{ __('Você não recebeu seu email?') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Clique para enviarmos um novo') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
