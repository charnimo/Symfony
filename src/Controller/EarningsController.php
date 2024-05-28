<?php
// src/Controller/Admin/EarningsController.php
namespace App\Controller\Admin;

use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EarningsController extends AbstractController
{
    #[Route('/admin/earnings', name: 'admin_earnings')]
    public function index(FactureRepository $factureRepository): Response
    {
        $totalEarnings = $factureRepository->createQueryBuilder('f')
            ->select('SUM(f.total)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('admin/earnings.html.twig', [
            'totalEarnings' => $totalEarnings,
        ]);
    }
}
