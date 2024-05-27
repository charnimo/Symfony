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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin/dashboard', name: 'admin.index')]
    public function index(): Response
    {
       

       
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
<<<<<<< HEAD
       // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('users and porducts');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('products', 'fa fa-laptop' , Products::class)     ; 
        yield MenuItem::linkToCrud('users', 'fa fa-user-circle-o' , Users::class)     ;  
       yield MenuItem::section('transaction info');

=======
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::subMenu('Blog', 'fa fa-laptop')->setSubItems([
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Users::class),
            MenuItem::linkToCrud('Posts', 'fa fa-file-text', Products::class),
            MenuItem::linkToCrud('Comments', 'fa fa-comment', Users::class),
        ]);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('products', 'fa fa-laptop' , Products::class)     ; 
        yield MenuItem::linkToCrud('users', 'fa fa-user-circle-o' , Users::class)     ;                 
>>>>>>> b5c15f148ecf72382add7347fdb34bb3257de04a
    }
}