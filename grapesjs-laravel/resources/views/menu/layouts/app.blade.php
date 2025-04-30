<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Menu')</title>

  @stack('styles')
</head>
<body>

  @yield('content')

  @stack('scripts')
</body>
</html>