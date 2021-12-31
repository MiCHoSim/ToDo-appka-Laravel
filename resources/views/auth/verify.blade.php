@extends('base')

@section('title', 'Overenie emailovej adresy')
@section('description', 'Overenie a znovu zaslanie odkazu pre overenie emailovej adresy.')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Overenie emailovej adresy</div>
                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                Odkaz pre overenie bol znova zaslaný do Vašej emailovej schránky.
                            </div>
                        @endif
                        Predtým, ako budete pokračovať, sa prosím uistíte, že ste nedostali odkaz na overenie.
                        Pokiaľ ste takýto email nedostali,
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">kliknite pre odoslanie nového odkazu </button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
