<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal de Notícias')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @yield('styles')
</head>
<body class="bg-gray-100 font-sans">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-md">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('noticias.index') }}" class="text-white text-2xl font-bold">
                    <i class="fas fa-newspaper mr-2"></i> Portal de Notícias
                </a>

                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('noticias.index') }}" class="text-white hover:text-blue-200 transition">Notícias</a>
                    <a href="#" class="text-white hover:text-blue-200 transition">Categorias</a>
                    <a href="#" class="text-white hover:text-blue-200 transition">Mais Lidas</a>
                    <a href="#" class="text-white hover:text-blue-200 transition">Contato</a>
                </nav>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-white" x-data="{open: false}" @click="open = !open">
                        <i class="fas fa-bars text-xl"></i>
                        <!-- Mobile menu (hidden by default) -->
                        <div x-show="open" class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                            <a href="{{ route('noticias.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white">Notícias</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white">Categorias</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white">Mais Lidas</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-blue-500 hover:text-white">Contato</a>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Sobre o Portal</h3>
                    <p class="text-gray-300">
                        Portal de notícias com as informações mais atualizadas e confiáveis. Mantendo você sempre bem informado.
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('noticias.index') }}" class="text-gray-300 hover:text-white transition">Notícias</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition">Política de Privacidade</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition">Termos de Uso</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Redes Sociais</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white transition"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Portal de Notícias. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
