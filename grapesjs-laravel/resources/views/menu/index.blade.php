@extends('menu.layouts.app')

@section('title', 'Menu')

@push('styles')
    <link href="{{ asset('vendor/tailwindcss/css/tailwind.min.css') }}" rel="stylesheet"/>
@endpush

<div class="max-w-5xl mx-auto mt-10 px-4">
    <h2 class="text-2xl font-semibold mb-6">Selecione um Template para Editar</h2>
    @include('menu.partials.select-template')

    <hr class="my-8 border-gray-300">

    <h4 class="text-xl font-semibold mb-4">Criar Novo Template</h4>
    @include('menu.partials.create-template')

    <hr class="my-8 border-gray-300">

    <a href="{{ url('componentes') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-md transition">
        Gerenciar Componentes
    </a>

    <div id="historico-container" class="mt-6 hidden">
        <h5 class="text-lg font-medium mb-2">Histórico de Modificações</h5>
        <ul id="historico-lista" class="list-disc list-inside space-y-1"></ul>
    </div>
</div>

@push('scripts')
    @include('menu.scripts.global-config')
    @include('menu.scripts.navigation')
    @include('menu.scripts.api-functions')
@endpush