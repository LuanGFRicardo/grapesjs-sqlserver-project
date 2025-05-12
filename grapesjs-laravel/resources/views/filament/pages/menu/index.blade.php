@extends('filament.pages.menu.layouts.app')

@section('title', 'Menu')

@push('styles')
    <link href="{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tailwindcss/css/tailwind.min.css') }}" rel="stylesheet"/>
@endpush

@section('content')
    <div class="max-w-5xl mx-auto mt-10 px-4">
        <h4 class="text-xl font-semibold mb-4">Criar Novo Template</h4>
        @include('filament.pages.menu.partials.create-template')

        <hr class="my-8 border-gray-300">

        <h2 class="text-2xl font-semibold mb-6">Selecione um Template para Editar</h2>
        @include('filament.pages.menu.partials.select-template')

        <div id="historico-container" class="mt-6 hidden">
            <h5 class="text-lg font-medium mb-2">Histórico de Modificações</h5>
            <ul id="historico-lista" class="list-disc list-inside space-y-1"></ul>
        </div>
    </div>
@endsection

@push('scripts')
    @include('filament.pages.menu.scripts.global-config')
    @include('filament.pages.menu.scripts.navigation')
    @include('filament.pages.menu.scripts.api-functions')
@endpush
