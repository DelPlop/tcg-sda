<?php

namespace App\Controller\Admin;

use App\Entity\Subtype;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SubtypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Subtype::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort([
            'name' => 'ASC'
        ]);
    }
}
