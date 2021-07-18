<?php

namespace App\Controller\Admin;

use App\Entity\Signet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SignetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Signet::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
