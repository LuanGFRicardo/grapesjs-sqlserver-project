@extends('filament.pages.componentes.layouts.app')

@section('title', 'Gerenciador de Componentes')

@push('styles')
    <link href="{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tailwindcss/css/tailwind.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/font-awesome/css/all.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tom-select/css/tom-select.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tom-select/css/custom-tom-select.css') }}" rel="stylesheet"/>
    <script src="{{ asset('vendor/tom-select/js/tom-select.complete.min.js') }}"></script>
@endpush

@section('content')
    <div class="mx-auto w-full max-w-5xl space-y-8 px-4 py-10">

        <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-gray-900 dark:text-white">
            Gerenciador de Componentes
        </h2>

        <section class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-lg">
            <header class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Editar Componente Existente
                </h3>
            </header>

            <div class="px-6 py-6 space-y-6">
                @include('filament.pages.componentes.partials.select-componente')
                @include('filament.pages.componentes.partials.edit-form')
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-lg">
            <header class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Adicionar Novo Componente
                </h3>
            </header>

            <div class="px-6 py-6 space-y-6">
                @include('filament.pages.componentes.partials.create-form')
            </div>
        </section>

    </div>
@endsection

@push('scripts')
    @include('filament.pages.componentes.scripts.global-config')
    @include('filament.pages.componentes.scripts.api-functions')
    @include('filament.pages.componentes.scripts.tomselect-init')
@endpush
