<?php

namespace App\Controller\Admin;

use App\Entity\Signet;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SignetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Signet::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort([
            'characterName' => 'ASC'
        ]);
    }
}
