<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Sugiere usar: composer require simplesoftwareio/simple-qrcode

class QrGeneratorService
{
    /**
     * Genera un SVG en base64 de un código QR que protege una ruta criptográficamente.
     * 
     * @param int $documentId
     * @return string Código SVG del QR renderizable en Vue.
     */
    public function generateSignedQrForDocument(int $documentId): string
    {
        // Genera la URL firmada. Caduca automáticamente en 24 horas exactas para evitar su abuso general o persistente.
        $signedUrl = URL::temporarySignedRoute(
            'api.documents.verify', 
            now()->addHours(24), 
            ['document_id' => $documentId]
        );

        // Diseña el código QR en SVG sin tocar el filesystem local para micro-segundos de rapidez.
        $qrImageSvg = QrCode::size(300)
            ->style('dot') // Diseño moderno 
            ->eye('circle')
            ->color(31, 41, 55) // Base dark-mode amigable / Tailwind Gray 800
            ->generate($signedUrl);

        return $qrImageSvg;
    }
}
