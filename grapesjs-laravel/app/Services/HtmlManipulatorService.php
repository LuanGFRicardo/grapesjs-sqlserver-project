<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class HtmlManipulatorService 
{
    // Insere links CSS no <head> do HTML
    // Se não existir <head>, cria dentro de <html> ou gera documento completo
    public function inserirCssHead(string $html): string
    {
        // Pega links CSS padrão
        $linksCss = $this->pegarLinkCssPadrao();

        // Monta conteúdo do <head> com links CSS
        $headContent = '';
        foreach ($linksCss as $href) {
            $headContent .= '<link rel="stylesheet" href="' . $href . '">' . PHP_EOL;
        }
        
        // Se existe <head>, insere links nele
        if (stripos($html, '<head>') !== false) {
            return preg_replace('/<head>/i', '<head>' . PHP_EOL . $headContent, $html, 1);
        }
    
        // Se existe só <html>, cria <head> com links
        if (stripos($html, '<html>') !== false) {
            return preg_replace(
                '/<html[^>]*>/i',
                '$0' . PHP_EOL . '<head>' . PHP_EOL . $headContent . PHP_EOL . '</head>',
                $html,
                1
            );
        }
    
        // Se não existe <html> nem <head>, gera documento HTML completo
        return $this->gerarDocumentoHtml($headContent, $html);           
    }

    // Retorna array com links CSS padrão
    private function pegarLinkCssPadrao(): array
    {
        return [
            "{{ asset('css/template/style.css') }}",
            "{{ asset('vendor/googleapis/css/googleapiscss.css') }}",
            "{{ asset('vendor/tailwindcss/css/tailwind-build.css') }}",
            "{{ asset('vendor/tailwindcss/css/tailwind.min.css') }}"
        ];
    }

    // Gera documento HTML completo com <head> e <body>
    private function gerarDocumentoHtml(string $headContent, string $bodyContent): string
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
