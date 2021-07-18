<?php

namespace App\Controller\Admin;

use App\Entity\Subtype;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SubtypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Subtype::class;
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
