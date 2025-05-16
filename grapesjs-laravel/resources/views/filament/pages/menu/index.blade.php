@extends('filament.pages.menu.layouts.app')

@section('title', 'Menu')

@push('styles')
    <link href="{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tailwindcss/css/tailwind.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tom-select/css/tom-select.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tom-select/css/custom-tom-select.css') }}" rel="stylesheet"/>
    <script src="{{ asset('vendor/tom-select/js/tom-select.complete.min.js') }}"></script>
@endpush

@section('content')
    <div class="mx-auto w-full max-w-5xl space-y-8 px-4 py-10">

        <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white">
            Gerenciador de Templates
        </h2>

        {{-- Criar Novo Template --}}
        <section class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
            <header class="rounded-t-xl border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-5 py-3">
                <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                    Criar Novo Template
                </h2>
            </header>
            <div class="px-5 py-6">
                @include('filament.pages.menu.partials.create-template')
            </div>
        </section>

        {{-- Selecionar Template --}}
        <section class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">
            <header class="rounded-t-xl border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-5 py-3">
                <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                    Selecione um Template para Editar
                </h2>
            </header>
            <div class="px-5 py-6">
                @include('filament.pages.menu.partials.select-template')

                {{-- Histórico --}}
                <div id="historico-container" class="mt-6 hidden">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-white mb-2">
                        Histórico de Modificações
                    </h3>
                    <ul id="historico-lista" class="list-disc list-inside text-sm space-y-1 text-gray-600 dark:text-gray-400"></ul>
                </div>
            </div>
        </section>

    </div>
@endsection

@push('scripts')
    @include('filament.pages.menu.scripts.global-config')
    @include('filament.pages.menu.scripts.navigation')
    @include('filament.pages.menu.scripts.api-functions')
    @include('filament.pages.menu.scripts.tomselect-init')
@endpush
