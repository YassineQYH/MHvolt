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
     * Génère un PDF et le renvoie en Response
     *
     * @param string $template Twig à rendre
     * @param array $data Données passées au template
     * @param string $filename Nom du fichier PDF
     * @param string $mode 'inline' pour affichage navigateur, 'attachment' pour téléchargement
     */
    public function generate(string $template, array $data, string $filename, string $mode = 'inline'): Response
    {
        $options = new Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
        $html = $this->twig->render($template, $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Envoyer le PDF en Response
        $dompdf->stream($filename, ["Attachment" => $mode === 'attachment']);

        return new Response(); // flux déjà envoyé
    }
}
