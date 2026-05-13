<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted("ROLE_USER")]
class MessageCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private TranslatorInterface $translator
    ) {
    }

    public function detail(
        AdminContext $context
    ): KeyValueStore {
        if(!$context->getEntity()->getInstance()->getIsRead()) {
            $context->getEntity()->getInstance()->setIsRead(true);
            $this->entityManagerInterface->flush();
        }

        return parent::detail($context);
    }

    public static function getEntityFqcn(): string
    {
        return Message::class;
    }

    public function configureCrud(
        Crud $crud
    ): Crud {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('entity_label.message_singular'))
            ->setEntityLabelInPlural($this->translator->trans('entity_label.message_plural'))
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page_title.message_index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page_title.message_detail'));
    }

    public function configureActions(Actions $actions): Actions
    {

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Crud::PAGE_NEW, Crud::PAGE_EDIT);
    }

    public function deleteEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::deleteEntity($entityManager, $entityInstance);
        $this->addFlash('danger', $this->translator->trans('confirmation.deleted', ['title' => $this->translator->trans('entity.message'), 'content' => $entityInstance->GetId()]));
    }

    public function configureFields(
        string $pageName
    ): iterable {
        return [
            DateTimeField::new('createdAt')
                ->setLabel($this->translator->trans('crud.received_on'))
                ->formatValue(function ($value, $entity) {
                    return $entity->getCreatedAt()->format('d/m/Y H:i');
                }),
            TextField::new('lastname')
                ->setLabel($this->translator->trans('crud.last_name')),
            TextField::new('firstname')
                ->setLabel($this->translator->trans('crud.first_name')),
            TextField::new('email')
                ->setLabel($this->translator->trans('crud.email')),
            BooleanField::new('isread')
                ->setLabel($this->translator->trans('crud.is_read')),
            TextareaField::new('content')
                ->setLabel($this->translator->trans('crud.content'))
                ->onlyOnDetail()
        ];
    }
}
