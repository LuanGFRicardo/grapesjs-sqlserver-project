@extends('noticias.layouts.noticias')

@section('title', $noticia->nome)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6 text-gray-500 text-sm">
            <a href="{{ route('noticias.index') }}" class="hover:text-blue-600">Início</a>
            <span class="mx-2">/</span>
            <span class="text-gray-700">{{ $noticia->nome }}</span>
        </nav>

        <!-- Conteúdo Principal -->
        <article class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Cabeçalho -->
            <header class="p-8 border-b border-gray-200">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $noticia->nome }}</h1>

                <div class="flex flex-wrap items-center text-gray-600 mb-6">
                    <div class="mr-6 mb-2">
                        <i class="far fa-user mr-2"></i>
                        <span>{{ $noticia->autor }}</span>
                    </div>
                    <div class="mr-6 mb-2">
                        <i class="far fa-calendar mr-2"></i>
                        <span>{{ \Carbon\Carbon::parse($noticia->data)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="mb-2">
                        <i class="far fa-eye mr-2"></i>
                        <span>{{ $noticia->visitas }} visualizações</span>
                    </div>
                </div>

                <p class="text-xl text-gray-600 leading-relaxed">{{ $noticia->descricao }}</p>

                <!-- Compartilhar -->
                <div class="mt-6">
                    <span class="font-medium text-gray-800 mr-4">Compartilhar:</span>
                    <div class="inline-flex space-x-4 text-lg">
                        <a href="#" class="text-blue-600 hover:text-blue-800"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-blue-400 hover:text-blue-600"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-green-600 hover:text-green-800"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="text-blue-500 hover:text-blue-700"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </header>

            <!-- Imagem Principal -->
            @if($noticia->imagem_gr)
                <div class="border-b border-gray-200">
                    <img src="{{ asset('storage/' . $noticia->imagem_gr) }}" alt="{{ $noticia->nome }}" class="w-full h-auto max-h-96 object-cover object-center">
                </div>
            @endif

            <!-- Template Dinâmico -->
            @if($templateHistorico)
                <style>
                    {!! $templateHistorico->css !!}
                </style>

                <div id="template-render" data-func="sql:noticia">
                    {!! $templateHistorico->html !!}
                </div>
            @else
                <p class="text-red-600 font-semibold">Template indisponível para esta notícia.</p>
            @endif

        </article>

        <!-- Tags -->
        <div class="mt-8 flex flex-wrap gap-2">
            <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">Notícias</span>
            <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">Informações</span>
            <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">Atualidades</span>
        </div>

        <!-- Autor -->
        <div class="mt-12 bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mr-4 text-blue-500">
                    <i class="fas fa-user text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $noticia->autor }}</h3>
                    <p class="text-gray-600">Autor</p>
                </div>
            </div>
            <p class="text-gray-700">
                Autor especializado em trazer as melhores e mais confiáveis informações para os leitores.
            </p>
        </div>
    </div>

    <!-- Notícias Relacionadas -->
    @if($noticiasRelacionadas->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">Notícias Relacionadas</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($noticiasRelacionadas as $relacionada)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="h-48 overflow-hidden">
                            @if($relacionada->imagem_pq)
                                <img src="{{ asset('storage/' . $relacionada->imagem_pq) }}" alt="{{ $relacionada->nome }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-3xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2 text-gray-800 hover:text-blue-600 transition duration-300">
                                <a href="{{ route('noticias.show', $relacionada->id) }}">
                                    {{ $relacionada->nome }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $relacionada->descricao }}</p>
                            <div class="flex justify-between items-center text-sm text-gray-500">
                                <div>
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($relacionada->data)->format('d/m/Y') }}
                                </div>
                                <div>
                                    <i class="far fa-eye mr-1"></i>
                                    {{ $relacionada->visitas }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    <script>
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        function handleApiError(contexto, err) {
            console.error(`❌ Erro em ${contexto}:`, err);
            alert(`❌ Erro ao ${contexto.toLowerCase()}.`);
        }

        const carregarDadosShow = () => {
            const sqlContainers = document.querySelectorAll('[data-func^="sql:"]');

            sqlContainers.forEach(container => {
                const funcValue = container.getAttribute('data-func');
                const [, tipo] = funcValue.split(':');

                fetch(`/dados/${tipo}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => {
                    if (!r.ok) throw new Error(`Erro HTTP: ${r.status}`);
                    return r.json();
                })
                .then(data => {
                    if (!Array.isArray(data)) {
                        console.error("❌ Dados inválidos (esperado array):", data);
                        return;
                    }

                    let html = "";
                    data.forEach(item => {
                        html += `<p>${item.valor ?? JSON.stringify(item)}</p>`;
                    });

                    container.innerHTML = html;
                })
                .catch(err => handleApiError("Carregar dados do template", err));
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            carregarDadosShow();
        });
    </script>
@endsection

@section('styles')
<style>
    /* Estilo para truncar texto em 2 linhas */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Estilizando o conteúdo do editor de rich text */
    .prose img {
        border-radius: 0.5rem;
        margin: 2rem 0;
    }

    .prose h2 {
        font-size: 1.75rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .prose h3 {
        font-size: 1.5rem;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .prose p {
        margin-bottom: 1.25rem;
        line-height: 1.8;
    }

    .prose a {
        color: #2563eb;
        text-decoration: underline;
    }

    .prose blockquote {
        border-left: 4px solid #3b82f6;
        padding-left: 1rem;
        font-style: italic;
        color: #4b5563;
        margin: 1.5rem 0;
    }
</style>
@endsection
