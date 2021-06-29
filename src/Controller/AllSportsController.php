<?php

namespace App\Controller;

use App\Repository\SportRepository;
use App\Service\SportNormalizee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/sports", name="api_sports_")
 */
class AllSportsController extends AbstractController
{
    /**
     * @Route(
     *      "",
     *      name="cget",
     *      methods={"GET"}
     * )
     */

    public function index(SportRepository $sportRepository, SportNormalizee $sportNormalizee ): Response
    {
        $result = $sportRepository->findAll();
        $data= [];
        foreach($result as $sport) {
            array_push($data, $sportNormalizee->sportNormalizee($sport));
        }
        return $this->json($data);
    }
}
