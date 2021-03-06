<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('post', 'Blog post'),
            TextField::new('content', 'Message'),
            DateTimeField::new('postedAt', 'Posté le'),
            TextField::new('author', 'Auteur'),
            BooleanField::new('isAccepted', 'Accepté'),
        ];
    }
}
