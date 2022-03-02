<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        Security $security
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;

        // get the user id from the logged in user
        if (null !== $this->security->getUser()) {
            $this->password = $this->security->getUser()->getPassword();
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username', 'utilisateurs'),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyOnForms(),
            ArrayField::new('roles')
        ];
    }

    /**
     *
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function updateEntity(
        EntityManagerInterface $entityManager,
        $entityInstance
    ): void {


        // set new password with encoder interface
        if (method_exists($entityInstance, 'setPassword')) {
            $clearPassword = trim($this->get('request_stack')->getCurrentRequest()->request->all('User')['password']);

            // if user password not change save the old one
            if (isset($clearPassword) === true && $clearPassword === '') {
                $entityInstance->setPassword($this->password);
            } else {
                $encodedPassword = $this->passwordEncoder->encodePassword($this->getUser(), $clearPassword);
                $entityInstance->setPassword($encodedPassword);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
