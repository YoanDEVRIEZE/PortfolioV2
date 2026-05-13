<?php

namespace App\Controller\Admin;

use App\Enum\CareerStatus;
use App\Entity\Career;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

#[IsGranted("ROLE_USER")]
class CareerCrudController extends AbstractCrudController
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Career::class;
    }

    public function configureCrud(
        Crud $crud
    ): Crud {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('entity_label.career_singular'))
            ->setEntityLabelInPlural($this->translator->trans('entity_label.career_plural'))
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page_title.career_index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page_title.career_detail'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page_title.career_new'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page_title.career_edit'));
    }

    public function persistEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::persistEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.added', ['title' => $this->translator->trans('entity.career'), 'content' => $entityInstance->getTitle()]));
    }

    public function updateEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        $entityInstance->setUpdatedAt();
        
        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.updated', ['title' => $this->translator->trans('entity.career'),'content' => $entityInstance->getTitle()]));
    }

    public function deleteEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::deleteEntity($entityManager, $entityInstance);
        $this->addFlash('danger', $this->translator->trans('confirmation.deleted', ['title' => $this->translator->trans('entity.career'), 'content' => $entityInstance->GetTitle()]));
    }


    public function configureFields(
        string $pageName
    ): iterable {
        $isNewPage = Crud::PAGE_NEW === $pageName;

        return [
            TextField::new('title')
                ->setLabel($this->translator->trans('crud.title'))
                ->setColumns(6),
            TextField::new('position')
                ->setLabel($this->translator->trans('crud.position'))
                ->setColumns(6),
            DateField::new('startdate')
                ->setLabel($this->translator->trans('crud.start_date'))
                ->setColumns(6),
            DateField::new('enddate')
                ->setLabel($this->translator->trans('crud.end_date'))
                ->setColumns(6),
            ChoiceField::new('status')
                ->setLabel($this->translator->trans('crud.status'))
                ->setChoices([
                    $this->translator->trans(CareerStatus::PermanentContract->translationKey()) => CareerStatus::PermanentContract,
                    $this->translator->trans(CareerStatus::FixedTermContract->translationKey()) => CareerStatus::FixedTermContract,
                    $this->translator->trans(CareerStatus::Apprenticeship->translationKey()) => CareerStatus::Apprenticeship,
                    $this->translator->trans(CareerStatus::Internship->translationKey()) => CareerStatus::Internship,
                    $this->translator->trans(CareerStatus::Freelance->translationKey()) => CareerStatus::Freelance,
                ])
                ->formatValue(function ($value, $entity) {
                    return $entity->getStatus()?->translationKey()
                        ? $this->translator->trans($entity->getStatus()->translationKey())
                        : null;
                })
                ->setColumns(6),
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
                    ]
                ])
                ->setColumns(6),
            ImageField::new('coverpicturefilename')
                ->setLabel($this->translator->trans('crud.cover_picture'))
                ->setBasePath('images/career/cover/')
                ->hideOnForm()
                ->formatValue(function ($value) {
                    return 'images/career/cover/'.$value;
                }),
            TextField::new('jobpicture')
                ->setLabel($this->translator->trans('crud.job_picture'))
                ->setRequired($isNewPage)
                ->setHelp($this->translator->trans('crud.image_format_webp'))
                ->onlyOnForms()
                ->setFormType(VichFileType::class)
                ->setFormTypeOptions([
                    'download_label' => true,
                    'allow_delete' => false,
                    'attr' => [
                        'accept' => 'image/webp',
                    ]
                ])
                ->setColumns(6),
            ImageField::new('jobpicturefilename')
                ->setLabel($this->translator->trans('crud.job_picture'))
                ->setBasePath('images/career/job/')
                ->hideOnForm()
                ->formatValue(function ($value) {
                    return 'images/career/job/'.$value;
                }),
            TextEditorField::new('content')
                ->setLabel($this->translator->trans('crud.content'))
                ->setColumns(6),
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
