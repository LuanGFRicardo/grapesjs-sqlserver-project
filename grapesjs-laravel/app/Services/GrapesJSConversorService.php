<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Template;
use App\Models\TemplateHistorico;
use App\Models\Noticia;
use App\Services\HtmlManipulatorService;

class GrapesJSConversorService
{
    // Converte HTML em componentes GrapesJS
    public function htmlToGrapesComponents(string $conteudoHtml): array
    {
        $conteudoHtmlUtf8 = mb_convert_encoding($conteudoHtml, 'HTML-ENTITIES', 'UTF-8');

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);

        @$dom->loadHTML('<div>' . $conteudoHtmlUtf8 . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        $body = $dom->getElementsByTagName('div')->item(0);

        $components = [];

        foreach ($body->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $components[] = $this->domNodeToGrapesComponent($node);
            } elseif ($node->nodeType === XML_TEXT_NODE) {
                $text = trim($node->textContent);
                if ($text !== '') {
                    $components[] = $text;
                }
            }
        }

        return $components;
    }

    // Converte NODE do DOM em componente GrapesJS
    public function domNodeToGrapesComponent(\DOMNode $node): array
    {
        $component = [
            'tagName' => $node->nodeName,
            'type' => $this->mapTagToType($node->nodeName),
        ];

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $component['attributes'][$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE) {
                    $text = trim($child->textContent);
                    if ($text !== '') {
                        $component['components'][] = $text;
                    }
                } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                    $component['components'][] = $this->domNodeToGrapesComponent($child);
                }
            }
        }

        return $component;
    }

    // Mapeia tags HTML para tipos GrapesJS
    private function mapTagToType(string $tag): string
    {
        $map = [
            'p' => 'text',
            'img' => 'image',
            'a' => 'link',
            'figure' => 'figure',
            'ul' => 'list',
            'li' => 'list-item',
        ];

        return $map[$tag] ?? $tag;
    }
}
