<?php

namespace App\Twig;

use App\Repository\TrottinetteRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private TrottinetteRepository $trottinetteRepository;

    public function __construct(TrottinetteRepository $trottinetteRepository)
    {
        $this->trottinetteRepository = $trottinetteRepository;
    }

    public function getGlobals(): array
    {
        return [
            'trottinettes_menu' => $this->trottinetteRepository->findAll(),
        ];
    }
}
