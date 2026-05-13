<?php

namespace App\Controller\Admin;

use App\Entity\SiteParameter;
use App\Repository\SiteParameterRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted("ROLE_USER")]
class SiteParameterCrudController extends AbstractCrudController
{
    private int $countSiteParameter;

    public function __construct(
        private TranslatorInterface $translator,
        private SiteParameterRepository $siteParameterRepository
    )
    {
        $this->countSiteParameter = $this->siteParameterRepository->count([]);
    }

    public function configureCrud(
        Crud $crud
    ): Crud {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('entity_label.site_parameter_singular'))
            ->setEntityLabelInPlural($this->translator->trans('entity_label.site_parameter_plural'))
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page_title.site_parameter_index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page_title.site_parameter_detail'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('page_title.site_parameter_new'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page_title.site_parameter_edit'));
    }

    public function configureActions(
        Actions $actions
    ): Actions {
        if($this->countSiteParameter >= 1) {
            $actions = $actions->disable(Action::NEW);
        }

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::DELETE);
    }

    public static function getEntityFqcn(): string
    {
        return SiteParameter::class;
    }

    public function persistEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::persistEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.added', ['title' => $this->translator->trans('entity.site_parameter'), 'content' => $entityInstance->getTitle()]));
    }

    public function updateEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.updated', ['title' => $this->translator->trans('entity.site_parameter'),'content' => $entityInstance->getTitle()]));
    }

    public function configureFields(
        string $pageName
    ): iterable {
        return [
            TextField::new('title')
                ->setLabel($this->translator->trans('crud.homepage_title'))
                ->setColumns(6)
                ->setHelp($this->translator->trans('crud.homepage_title_help')),
            TextField::new('urlsite')
                ->setLabel($this->translator->trans('crud.website_url'))
                ->setHtmlAttributes([
                    "placeholder" => "https://127.0.0.1"
                ])
                ->setColumns(6)
                ->setHelp($this->translator->trans('crud.website_url_help'))
                ->formatValue(function ($value, $entity) {
                    if (!$value) {
                        return '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_link').'</span>';
                    }

                    return sprintf('<a href="%s" target="_blank" class="btn btn-secondary me-2 d-inline-flex align-items-center justify-content-center gap-2"><span>%s</span><i class="fa-brands fa-globe"></i></a>', $value, $this->translator->trans('crud.show'));
                }),
            TextareaField::new('description')
                ->setLabel($this->translator->trans('crud.website_description'))
                ->setColumns(6)
                ->setHelp($this->translator->trans('crud.website_description_help')),
            TextareaField::new('mediadescription')
                ->setLabel($this->translator->trans('crud.social_media_description'))
                ->setColumns(6)
                ->setHelp($this->translator->trans('crud.social_media_description_help')),
            ArrayField::new('keyword')
                ->setLabel($this->translator->trans('crud.keywords'))
                ->setFormTypeOptions([
                    'attr' => ['class' => 'keywords-field']
                ])
                ->setHelp($this->translator->trans('crud.keywords_help'))
                ->formatValue(function ($value, $entity) {
                    if (!$value) {
                        return '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_keyword').'</span>';
                    }

                    return implode(' ', array_map(
                        fn ($keyword) => sprintf(
                            '<span class="badge bg-primary text-white">%s</span>',
                            htmlspecialchars($keyword)
                        ),
                        $value
                    ));
                }),
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
