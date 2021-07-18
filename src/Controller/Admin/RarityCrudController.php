<?php

namespace App\Controller\Admin;

use App\Entity\Rarity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class RarityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rarity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort([
            'position' => 'ASC'
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('id'),
            TextField::new('name'),
            IntegerField::new('position'),
        ];
    }
}
