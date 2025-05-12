<!DOCTYPE html>
<html lang="pt-BR">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gerenciar Componentes')</title>

    @stack('styles')
</head>
<body>

    @yield('content')

    @stack('scripts')
</body>
</html>