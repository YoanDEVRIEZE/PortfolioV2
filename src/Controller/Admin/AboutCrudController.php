<?php

namespace App\Controller\Admin;

use App\Entity\About;
use App\Repository\AboutRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted("ROLE_USER")]
class AboutCrudController extends AbstractCrudController
{
    private int $countAbout;

    public function __construct(
        private AboutRepository $aboutRepository,
        private TranslatorInterface $translator
    )
    {
        $this->countAbout = $this->aboutRepository->count([]);
    }

    public function configureActions(
        Actions $actions
    ): Actions {
        if($this->countAbout >= 3) {
            $actions = $actions->disable(Action::NEW);
        }

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::DELETE);
    }

    public function configureCrud(
        Crud $crud
    ): Crud {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('entity_label.about_singular'))
            ->setEntityLabelInPlural($this->translator->trans('entity_label.about_plural'))
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page_title.about_index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page_title.about_detail'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page_title.about_new'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page_title.about_edit'));
    }

    public function persistEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::persistEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.added', ['title' => $this->translator->trans('entity.about'), 'content' => $entityInstance->getTitle()]));
    }

    public function updateEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.updated', ['title' => $this->translator->trans('entity.about'),'content' => $entityInstance->getTitle()]));
    }

    public static function getEntityFqcn(): string
    {
        return About::class;
    }

    public function configureFields(
        string $pageName
    ): iterable {
        return [
            TextField::new('title')
                ->setMaxLength(20)
                ->setHelp($this->translator->trans('help.between_x_and_x_characters_maximum', ['first' => 1, 'last' => 20]))
                ->setLabel($this->translator->trans('crud.title'))
                ->setColumns(6),
            TextEditorField::new('description')
                ->setHelp($this->translator->trans('help.between_x_and_x_characters_maximum', ['first' => 10, 'last' => 255]))
                ->setLabel($this->translator->trans('crud.description'))
                ->onlyOnForms()
                ->setColumns(6),
            TextField::new('description')
                ->hideOnForm()
                ->setLabel($this->translator->trans('crud.description'))
                ->renderAsHtml(),
            DateTimeField::new('createdAt')
                ->setLabel($this->translator->trans('crud.created_at'))
                ->hideOnForm()
                ->formatValue(function ($value, $entity) {
                    return $entity->getCreatedAt()->format('d/m/Y H:i');
                }),
            DateTimeField::new('updatedAt')
                ->hideOnForm()
                ->setLabel($this->translator->trans('crud.updated_at'))
                ->formatValue(function ($value, $entity) {
                    if (!$entity->getUpdatedAt()) {
                        return '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_update').'</span>';
                    }

                    return $entity->getUpdatedAt()->format('d/m/Y H:i');
                })
        ];
    }
}
