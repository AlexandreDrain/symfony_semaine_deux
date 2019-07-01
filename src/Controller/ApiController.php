<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function meteo(): JsonResponse
    {
        $today = [
            "temp" => 35,
            "unit" => 'celsius',
            "humidity" => "24"
        ];
        return $this->json($today);
    }

    /**
     * @return [type] [description]
     */
    public function redirectMeteo(): RedirectResponse
    {
        return $this->redirectToRoute('app_api_meteo');
    }

    public function pdf(): BinaryFileResponse
    {
        $pdf = new File('documents/Symfony_cours.pdf');
        return $this->file($pdf, 'sf4.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
