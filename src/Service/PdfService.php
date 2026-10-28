<?php
declare(strict_types=1);

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfService
{
    public function __construct(
        private readonly Environment $twig
    ) {}

    /**
     * Génère un PDF propre et le renvoie sous forme de Response Symfony
     */
    public function generate(string $template, array $data, string $filename, string $mode = 'inline'): Response
    {
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled(true);
        $options->setDefaultFont('Helvetica');

        $dompdf = new Dompdf($options);
        $html = $this->twig->render($template, $data);

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $disposition = ($mode === 'attachment') ? 'attachment' : 'inline';

        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('%s; filename="%s"', $disposition, $filename),
        ]);
    }
}
