<!DOCTYPE html>
<html lang="cs-SK">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="@yield('description')" />
    <title>@yield('title', env('APP_NAME'))</title>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />

    <script src="{{ mix('js/app.js') }}"></script>
</head>
<body>
<div class="d-flex justify-content-between p-3 px-4 mb-3 bg-white border-bottom shadow-sm">
    <h2 class="my-0">{{ env('APP_NAME') }}</h2>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="{{ url('') }}">Hlavná stránka</a>
        @auth
            <a class="p-2 text-dark" href="{{ route('task.index') }}">Zoznam ToDo</a>
            <a class="p-2 text-dark border-start" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">Odhlásiť sa</a>
            <form action="{{ route('logout') }}" method="POST" class="d-none" id="logout-form">
                @csrf
            </form>
        @else
            <a class="p-2 text-dark border-start" href="{{ route('login') }}">Prihlásenie</a>
            <a class="p-2 text-dark" href="{{ route('register') }}">Registrácia</a>
        @endauth
    </nav>
</div>
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
    <footer class="pt-4 my-md-5 border-top">
        <p>
            Jednoduchá ToDo appka v Laraveli.
        </p>
    </footer>
</div>
@stack('scripts')
</body>
</html>
