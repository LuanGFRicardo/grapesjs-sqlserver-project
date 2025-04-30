<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class HtmlManipulatorService 
{
    // Insere links de CSS no <head> do HTML
    public function inserirCssHead(string $html): string
    {
        $linksCss = $this->getDefaultCssLinks();

        $headContent = '';
        foreach ($linksCss as $href) {
            $headContent .= '<link rel="stylesheet" href="' . $href . '">' . PHP_EOL;
        }
        
        if (stripos($html, '<head>') !== false) {
            return preg_replace('/<head>/i', '<head>' . PHP_EOL . $headContent, $html, 1);
        }
    
        if (stripos($html, '<html>') !== false) {
            return preg_replace(
                '/<html[^>]*>/i',
                '$0' . PHP_EOL . '<head>' . PHP_EOL . $headContent . PHP_EOL . '</head>',
                $html,
                1
            );
        }
    
        return $this->wrapHtmlDocument($headContent, $html);           
    }

    // Retorna links CSS padrões
    private function getDefaultCssLinks(): array
    {
        return [
            "{{ asset('css/template/style.css') }}",
            "{{ asset('vendor/googleapis/css/googleapiscss.css') }}",
            "{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}",
            "{{ asset('vendor/tailwindcss/css/base.css') }}",
            "{{ asset('vendor/tailwindcss/css/components.css') }}",
            "{{ asset('vendor/tailwindcss/css/tailwind.min.css') }}",
            "{{ asset('vendor/tailwindcss/css/utilities.css') }}"
        ];
    }

    // Gera um HTML completo se não houve <html> nem <head>
    private function wrapHtmlDocument(string $headContent, string $bodyContent): string
    {
        return '<!DOCTYPE html>' . PHP_EOL .
        '<html>' . PHP_EOL .
        '<head>' . PHP_EOL .
        $headContent . PHP_EOL .
        '</head>' . PHP_EOL .
        '<body>' . PHP_EOL .
        $bodyContent . PHP_EOL .
        '</body>' . PHP_EOL .
        '</html>';
    }
}