<?php

namespace App\Controller\Admin;

use App\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

#[IsGranted("ROLE_USER")]
class SkillCrudController extends AbstractCrudController
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {
    }

    public function configureCrud(
        Crud $crud
    ): Crud {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('entity_label.skill_singular'))
            ->setEntityLabelInPlural($this->translator->trans('entity_label.skill_plural'))
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page_title.skill_index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page_title.skill_detail'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page_title.skill_new'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page_title.skill_edit'));
    }

    public static function getEntityFqcn(): string
    {
        return Skill::class;
    }

    public function persistEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::persistEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.added', ['title' => $this->translator->trans('entity.skill'), 'content' => $entityInstance->getTitle()]));
    }

    public function updateEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        $entityInstance->setUpdatedAt();
        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.updated', ['title' => $this->translator->trans('entity.skill'),'content' => $entityInstance->getTitle()]));
    }

    public function deleteEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::deleteEntity($entityManager, $entityInstance);
        $this->addFlash('danger', $this->translator->trans('confirmation.deleted', ['title' => $this->translator->trans('entity.skill'), 'content' => $entityInstance->GetId()]));
    }

    public function configureFields(
        string $pageName
    ): iterable {
        $isNewPage = Crud::PAGE_NEW === $pageName;

        return [
            TextField::new('title')
                ->setRequired(true)
                ->setMaxLength(100)
                ->setHelp($this->translator->trans('help.between_x_and_x_characters_maximum', ['first' => 1, 'last' => 100]))
                ->setLabel($this->translator->trans('crud.title'))
                ->setColumns(6),
            Field::new('image')
                ->setRequired($isNewPage)
                ->setFormType(VichFileType::class)
                ->setFormTypeOptions([
                    'download_label' => true,
                    'allow_delete' => false,
                    'attr' => [
                        'accept' => 'image/webp',
                    ]
                ])
                ->setHelp($this->translator->trans('crud.image_format_webp'))
                ->setLabel($this->translator->trans('crud.logo'))
                ->OnlyOnForms()
                ->setColumns(6),
            ImageField::new('imagefilename')
                ->setBasePath('images/skill')
                ->setLabel($this->translator->trans('crud.logo'))
                ->hideOnForm(),
            IntegerField::new('level')
                ->setFormType(RangeType::class)
                ->setRequired(true)
                ->setLabel($this->translator->trans('crud.level'))
                ->setHelp($this->translator->trans('crud.level_help'))
                ->setColumns(6)
                ->setFormTypeOption('data', 1)
                ->setFormTypeOption('attr.min', 1)
                ->setFormTypeOption('attr.max', 100)
                ->setFormTypeOption('attr.step', 1)
                ->setFormTypeOption(
                    'attr.oninput',
                    'document.getElementById("progress-value").innerText = this.value + "%"'
                )
                ->setHelp(sprintf(
                    '
                    <div class="d-flex justify-content-between align-items-center">
                        <span>%s</span>
                        <span id="progress-value" class="badge bg-primary text-white">%s%%</span>
                    </div>
                    ',
                    $this->translator->trans('crud.level_help'),
                    $this->getContext()?->getEntity()?->getInstance()?->getProgressbarcolor() ?? 0
                )),
            ColorField::new('progressbarcolor')
                ->setRequired(true)
                ->setHelp($this->translator->trans('crud.associated_color_help'))
                ->setLabel($this->translator->trans('crud.associated_color'))
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
