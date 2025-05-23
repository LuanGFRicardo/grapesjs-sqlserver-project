@extends('editor.layouts.app')

@section('title', 'Editor GrapesJS')

@push('styles')
    {{-- TailwindCSS (CSS) --}}
    <link href="{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/tailwindcss/css/tailwind.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}">
    
    {{-- GrapesJS (CSS) --}}
    <link href="{{ asset('vendor/grapesjs/css/grapes.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/editor/custom.css') }}" rel="stylesheet"/>

    {{-- Font Awesome --}}
    <link href="{{ asset('vendor/font-awesome/css/all.min.css') }}" rel="stylesheet"/>
  
    {{-- CodeMirror (CSS) --}}
    <link href="{{ asset('vendor/codemirror/css/codemirror.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/codemirror/theme/dracula.min.css') }}" rel="stylesheet"/>
    
    {{-- Beautify (JS) --}}
    <script src="{{ asset('vendor/beautify/js/beautify.js' ) }}"></script>
@endpush

@section('content')
    <div id="gjs"></div>
    @include('editor.partials.modal-editor')
@endsection

@push('scripts')
    {{-- GrapesJS (JS) --}}
    @foreach ([
        'grapes.min.js',
        'grapesjs-preset-webpage.min.js',
        'grapesjs-preset-newsletter.js',
        'grapesjs-plugin-forms.min.js',
        'grapesjs-custom-code.min.js',
        'grapesjs-navbar.min.js',
        'grapesjs-tooltip.min.js',
        'grapesjs-custom-block.js'
    ] as $script)
    <script src="{{ asset("vendor/grapesjs/js/$script") }}"></script>

    {{-- CodeMirror (JS) --}}
    <script src="{{ asset('vendor/codemirror/js/codemirror.min.js') }}"></script>
    <script src="{{ asset('vendor/codemirror/js/htmlmixed.min.js') }}"></script>
    
    {{-- Beautify (JS) --}}
    <script src="{{ asset('vendor/beautify/js/beautify-html.js' )}}"></script>
    <script src="{{ asset('vendor/beautify/js/beautify-css.js' )}}"></script>
    @endforeach

    @include('editor.scripts.global-config')
    @include('editor.scripts.api-functions')
    @include('editor.scripts.html-utils')
    @include('editor.scripts.editor-init')
    @include('editor.scripts.code-editor')
    @include('editor.scripts.navigation')
@endpush