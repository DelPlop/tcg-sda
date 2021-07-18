<?php

namespace App\Controller\Admin;

use App\Entity\Culture;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CultureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Culture::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort([
            'position' => 'ASC'
        ]);
    }
}
