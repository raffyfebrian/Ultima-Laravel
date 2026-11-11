<?php

namespace App\Services;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService
{
    /**
     * Buat QR code dalam format SVG (tanpa butuh ekstensi GD/Imagick).
     */
    public function svg(string $content, int $size = 480, int $margin = 2): string
    {
        $style = new RendererStyle(max(120, $size), max(0, $margin));
        $renderer = new ImageRenderer($style, new SvgImageBackEnd);
        $writer = new Writer($renderer);

        return $writer->writeString($content, 'UTF-8', ErrorCorrectionLevel::M());
    }
}
