<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="template-id" content="{{ $templateModel->id }}">
    <meta name="template-name" content="{{ $templateModel->nome }}">
    <meta name="template-html" content="{{ rawurlencode($versao->html) }}">
    <meta name="template-projeto" content="{{ rawurlencode($versao->projeto) }}">
    
    <title>@yield('title', 'Editor')</title>

    @stack('styles')
</head>
<body>

    @yield('content')

    @stack('scripts')
</body>
</html>