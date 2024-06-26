<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Repository\UsersRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Users;
use App\Entity\Facture; // Ensure this line is present
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin/dashboard', name: 'admin.index')]
    public function index(): Response
    {
       
//$this->denyAccessUnlessGranted('ROLE_ADMIN' , null , "You need Admin rights");
//Wait until login works 
       
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(ProductsCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard');
           
    }

    public function configureMenuItems(): iterable
    {
       // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('users and porducts');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('products', 'fa fa-laptop' , Products::class)     ; 
        yield MenuItem::linkToCrud('users', 'fa fa-user-circle-o' , Users::class)     ;  
       yield MenuItem::section('transaction info');
       yield MenuItem::linkToCrud('Factures', 'fas fa-file-invoice', Facture::class);
       yield MenuItem::linkToRoute('Total Earnings', 'fas fa-money-bill', 'admin_earnings');

    }
}