<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Card::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('edition');
        yield AssociationField::new('rarity');
        yield IntegerField::new('position');
        yield TextField::new('code');
        yield TextField::new('originalName');
        yield TextField::new('localName');
        yield AssociationField::new('culture');
        yield AssociationField::new('type');
        if (Crud::PAGE_DETAIL === $pageName || Crud::PAGE_EDIT === $pageName) {
            yield AssociationField::new('subtype');
        }
        if (Crud::PAGE_DETAIL === $pageName) {
            yield ArrayField::new('tags');
            yield ArrayField::new('phases');
        } elseif (Crud::PAGE_EDIT === $pageName) {
            yield AssociationField::new('tags');
            yield AssociationField::new('phases');
        }
        if (Crud::PAGE_DETAIL === $pageName || Crud::PAGE_EDIT === $pageName) {
            yield IntegerField::new('cost');
            yield IntegerField::new('strength');
            yield TextField::new('strengthModifier');
            yield IntegerField::new('vitality');
            yield TextField::new('vitalityModifier');
            yield IntegerField::new('resistance');
            yield TextField::new('resistanceModifier');
            yield AssociationField::new('signet');
            yield IntegerField::new('siteNumber');
            yield IntegerField::new('shadowNumber');
            yield TextField::new('originalText');
            yield TextField::new('originalQuote');
            yield TextField::new('localText');
            yield TextField::new('localQuote');
            yield BooleanField::new('isUnique');
            yield BooleanField::new('isRingBearer');
            yield BooleanField::new('isTengwar');
            yield BooleanField::new('isRf');
            yield BooleanField::new('isRfa');
            yield BooleanField::new('hasLocalImage');
        }
        if (Crud::PAGE_DETAIL === $pageName) {
            yield ArrayField::new('multiCards');
        } elseif (Crud::PAGE_EDIT === $pageName) {
            yield AssociationField::new('multiCards');
        }
        if (Crud::PAGE_DETAIL === $pageName || Crud::PAGE_EDIT === $pageName) {
            yield BooleanField::new('isAuthorized');
            yield BooleanField::new('isDisplayable');
        }
    }
}
