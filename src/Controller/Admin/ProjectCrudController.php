<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

#[IsGranted("ROLE_USER")]
class ProjectCrudController extends AbstractCrudController
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(
        Crud $crud
    ): Crud {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('entity_label.project_singular'))
            ->setEntityLabelInPlural($this->translator->trans('entity_label.project_plural'))
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page_title.project_index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page_title.project_detail'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page_title.project_new'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page_title.project_edit'));
    }

    public function persistEntity(
        EntityManagerInterface $entityManager,
        $entityInstance
    ): void {
        parent::persistEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.added', ['title' => $this->translator->trans('entity.project'), 'content' => $entityInstance->getTitle()]));
    }

    public function updateEntity(
        EntityManagerInterface $entityManager,
        $entityInstance
    ): void {
        $entityInstance->setUpdateAt();

        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.updated', ['title' => $this->translator->trans('entity.project'), 'content' => $entityInstance->getTitle()]));
    }

    public function deleteEntity(
        EntityManagerInterface $entityManager,
        $entityInstance
    ): void {
        parent::deleteEntity($entityManager, $entityInstance);
        $this->addFlash('danger', $this->translator->trans('confirmation.deleted', ['title' => $this->translator->trans('entity.project'), 'content' => $entityInstance->getTitle()]));
    }

    public function configureFields(
        string $pageName
    ): iterable
    {
        $isNewPage = Crud::PAGE_NEW === $pageName;

        return [
            TextField::new('title')
                ->setLabel($this->translator->trans('crud.title'))
                ->setColumns(6),
            TextField::new('description')
                ->setLabel($this->translator->trans('crud.description'))
                ->setColumns(6),
            TextField::new('link')
                ->setLabel($this->translator->trans('crud.link'))
                ->setColumns(6)
                ->formatValue(function ($value) {
                    if (!$value) {
                        return '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_link').'</span>';
                    }

                    return sprintf('<a href="%s" target="_blank" class="btn btn-secondary me-2 d-inline-flex align-items-center justify-content-center gap-2"><span>%s</span><i class="fa-brands fa-globe"></i></a>', $value, $this->translator->trans('crud.show'));
                }),
            TextField::new('coverpicture')
                ->setLabel($this->translator->trans('crud.cover_picture'))
                ->setRequired($isNewPage)
                ->setHelp($this->translator->trans('crud.image_format_webp'))
                ->onlyOnForms()
                ->setFormType(VichFileType::class)
                ->setFormTypeOptions([
                    'download_label' => true,
                    'allow_delete' => false,
                    'attr' => [
                        'accept' => 'image/webp',
                    ],
                ])
                ->setColumns(6),
            ImageField::new('coverpicturefilename')
                ->setLabel($this->translator->trans('crud.cover_picture'))
                ->setBasePath('images/project/cover/')
                ->hideOnForm()
                ->formatValue(function ($value) {
                    return 'images/project/cover/'.$value;
                }),
            TextField::new('projectpicture')
                ->setLabel($this->translator->trans('crud.project_picture'))
                ->setRequired($isNewPage)
                ->setHelp($this->translator->trans('crud.image_format_webp'))
                ->onlyOnForms()
                ->setFormType(VichFileType::class)
                ->setFormTypeOptions([
                    'download_label' => true,
                    'allow_delete' => false,
                    'attr' => [
                        'accept' => 'image/webp',
                    ],
                ])
                ->setColumns(6),
            ImageField::new('projectpicturefilename')
                ->setLabel($this->translator->trans('crud.project_picture'))
                ->setBasePath('images/project/picture/')
                ->hideOnForm()
                ->formatValue(function ($value) {
                    return 'images/project/picture/'.$value;
                }),
            TextEditorField::new('content')
                ->setLabel($this->translator->trans('crud.content'))
                ->setColumns(6),
            DateTimeField::new('createAt')
                ->setLabel($this->translator->trans('crud.created_at'))
                ->hideOnForm()
                ->formatValue(function ($value, $entity) {
                    return $entity->getCreateAt()->format('d/m/Y H:i');
                }),
            DateTimeField::new('updateAt')
                ->hideOnForm()
                ->setLabel($this->translator->trans('crud.updated_at'))
                ->formatValue(function ($value, $entity) {
                    if (!$entity->getUpdateAt()) {
                        return '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_update').'</span>';
                    }

                    return $entity->getUpdateAt()->format('d/m/Y H:i');
                }),
        ];
    }
}
