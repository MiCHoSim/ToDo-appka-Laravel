@extends('base')

@section('title', 'ToDo appka v Laraveli')
@section('description', 'Jednoduchá ToDo appka v Laraveli')

@section('content')
    @if (session('verified'))
        <div class="alert alert-success">Emailová adresa bola úspešne overená.</div>
    @endif
    <h1 class="text-center mb-4">ToDo appka</h1>
    <article class="article mb-5">
        <span>Vitajte v ToDo appke.</span>
        <h2>
            <a class="text-decoration-none" href="{{ route('task.index')  }}">Tu si môžte pozrieť zoznám úloh.</a>
        </h2>
    </article>
@endsection


