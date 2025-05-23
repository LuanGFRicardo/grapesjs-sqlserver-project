<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Verifica se uma URL de imagem é uma string Base64 e retorna a URL apropriada
     *
     * @param string|null $imagePath
     * @return string
     */
    public static function getImageUrl($imagePath)
    {
        if (!$imagePath) {
            return '';
        }

        // Verifica se a imagem já está em formato Base64
        if (strpos($imagePath, 'data:image') === 0 || strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }

        // Caso contrário, assume que é um caminho de arquivo no storage
        return asset('storage/' . $imagePath);
    }
}
