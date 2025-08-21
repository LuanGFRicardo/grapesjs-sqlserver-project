@extends('noticias.layouts.noticias')

@section('title', 'Portal de Notícias - Últimas Atualizações')

@section('content')
    <!-- Destaque Principal -->
    @if($noticias->count() > 0)
        <div class="mb-10">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-2/3">
                        @if($noticias[0]->imagem_gr)
                            <img src="{{ asset('storage/' . $noticias[0]->imagem_gr) }}" alt="{{ $noticias[0]->nome }}" class="w-full h-80 object-cover">
                        @else
                            <div class="w-full h-80 bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-newspaper text-5xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="md:w-1/3 p-6">
                        <span class="bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Destaque</span>
                        <h2 class="mt-3 text-2xl font-bold text-gray-800 leading-tight hover:text-blue-600 transition duration-300">
                            <a href="{{ route('noticias.show', $noticias[0]->id) }}">
                                {{ $noticias[0]->nome }}
                            </a>
                        </h2>
                        <p class="mt-2 text-gray-600 line-clamp-3">{{ $noticias[0]->descricao }}</p>
                        <div class="mt-4 flex items-center text-sm text-gray-500">
                            <i class="far fa-calendar mr-2"></i>
                            {{ \Carbon\Carbon::parse($noticias[0]->data)->format('d/m/Y H:i') }}
                            <span class="mx-2">•</span>
                            <i class="far fa-user mr-2"></i>
                            {{ $noticias[0]->autor }}
                            <span class="mx-2">•</span>
                            <i class="far fa-eye mr-2"></i>
                            {{ $noticias[0]->visitas }} visualizações
                        </div>
                        <a href="{{ route('noticias.show', $noticias[0]->id) }}" class="mt-5 inline-block px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-300">
                            Ler mais
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Seção de Notícias -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">Últimas Notícias</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($noticias->skip(1) as $noticia)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="h-48 overflow-hidden">
                        @if($noticia->imagem_pq)
                            <img src="{{ asset('storage/' . $noticia->imagem_pq) }}" alt="{{ $noticia->nome }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-newspaper text-3xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-gray-800 hover:text-blue-600 transition duration-300">
                            <a href="{{ route('noticias.show', $noticia->id) }}">
                                {{ $noticia->nome }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $noticia->descricao }}</p>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <div>
                                <i class="far fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($noticia->data)->format('d/m/Y') }}
                            </div>
                            <div>
                                <i class="far fa-eye mr-1"></i>
                                {{ $noticia->visitas }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-12">
        {{ $noticias->links() }}
    </div>
@endsection

@section('styles')
<style>
    /* Estilo para truncar texto em 3 linhas */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
