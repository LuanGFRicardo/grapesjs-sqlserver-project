@extends('componentes.layouts.app')

@section('title', 'Gerenciador de Componentes')

@push('styles')
    <link href="{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tailwindcss/css/tailwind.min.css') }}" rel="stylesheet"/>
@endpush

<div class="max-w-5xl mx-auto mt-10 px-4">
    <h2 class="text-2xl font-semibold mb-6">Gerenciador de Componentes</h2>

    <div class="bg-white border border-gray-300 rounded-md shadow-md mb-6">
        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300 text-lg font-medium rounded-t-md">
            Editar Componente Existente
        </div>
        <div class="p-4">
            @include('componentes.partials.select-componente')
            @include('componentes.partials.edit-form')
        </div>
    </div>

    <div class="bg-white border border-gray-300 rounded-md shadow-md mb-6">
        <div class="bg-gray-100 px-4 py-2 border-b border-gray-300 text-lg font-medium rounded-t-md">
            Adicionar Novo Componente
        </div>
        <div class="p-4">
            @include('componentes.partials.create-form')
        </div>
    </div>
</div>

@push('scripts')
    @include('componentes.scripts.global-config')
    @include('componentes.scripts.api-functions')
    @include('componentes.scripts.navigation')
@endpush