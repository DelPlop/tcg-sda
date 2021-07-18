<?php

namespace App\Controller\Admin;

use App\Entity\ApplicationUser;
use App\Entity\Edition;
use App\Entity\Card;
use App\Entity\Phase;
use App\Entity\Type;
use App\Entity\Subtype;
use App\Entity\Rarity;
use App\Entity\Signet;
use App\Entity\Tag;
use App\Entity\Culture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        $url = $routeBuilder->setController(CardCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setFaviconPath('/img/favicon.ico')
            ->setTranslationDomain('admin')
            ->setTitle('Admin');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('dashboard.home', 'fa fa-home');
        yield MenuItem::section('dashboard.global');
        yield MenuItem::linkToCrud('dashboard.rarities', 'fas fa-list-ul', Rarity::class);
        yield MenuItem::linkToCrud('dashboard.editions', 'fas fa-list-ul', Edition::class);
        yield MenuItem::linkToCrud('dashboard.cultures', 'fas fa-list-ul', Culture::class);
        yield MenuItem::linkToCrud('dashboard.phases', 'fas fa-list-ul', Phase::class);
        yield MenuItem::linkToCrud('dashboard.types', 'fas fa-list-ul', Type::class);
        yield MenuItem::linkToCrud('dashboard.subtypes', 'fas fa-list-ul', Subtype::class);
        yield MenuItem::linkToCrud('dashboard.signets', 'fas fa-list-ul', Signet::class);
        yield MenuItem::linkToCrud('dashboard.tags', 'fas fa-list-ul', Tag::class);
        yield MenuItem::section('dashboard.utiles');
        yield MenuItem::linkToCrud('dashboard.cards', 'fas fa-bookmark', Card::class);
        yield MenuItem::linkToCrud('dashboard.users', 'fas fa-user-alt', ApplicationUser::class);
    }
}
