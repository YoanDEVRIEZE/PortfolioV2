<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\PasswordValidate;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

#[IsGranted("ROLE_USER")]
class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private TranslatorInterface $translator, 
        private UserPasswordHasherInterface $passwordHasher,
        private PasswordValidate $passwordValidate
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(
        Actions $actions
    ): Actions {
        return $actions
            ->disable(Action::NEW, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(
        Crud $crud
    ): Crud {
        return $crud
            ->setEntityLabelInSingular($this->translator->trans('entity_label.user_singular'))
            ->setEntityLabelInPlural($this->translator->trans('entity_label.user_plural'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('page_title.user_edit'))
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('page_title.user_index'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('page_title.user_detail'));
    }

    public function updateEntity(
        EntityManagerInterface $entityManager, 
        $entityInstance
    ): void {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $data = $request->request->all('User');
        $currentPassword = $data['CurrentPassword'] ?? null;
        $plainPassword = $data['PlainPassword'] ?? null;
        $confirmPassword = $data['ConfirmPassword'] ?? null;

        if ($currentPassword || $plainPassword || $confirmPassword) {
            if(empty($currentPassword) || empty($plainPassword) || empty($confirmPassword)) {
                $this->addFlash('danger', $this->translator->trans('error.all_password_fields_required'));
                return;
            }

            if(!$this->passwordHasher->isPasswordValid($entityInstance, $currentPassword)) {
                $this->addFlash('danger', $this->translator->trans('error.current_password_incorrect'));
                return;
            }

            if ($plainPassword !== $confirmPassword) {
                $this->addFlash('danger', $this->translator->trans('error.passwords_do_not_match'));
                return;
            }

            if($this->passwordValidate::check($plainPassword)) {
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
                $entityInstance->setPassword($hashedPassword);
            }
        }

        $entityInstance->setUpdatedAt(new DateTimeImmutable());
        
        parent::updateEntity($entityManager, $entityInstance);
        $this->addFlash('success', $this->translator->trans('confirmation.updated', ['title' => $this->translator->trans('entity.user'),'content' => $entityInstance->getEmail()]));
    }

    public function configureFields(
        string $pageName
    ): iterable {
        $isNewPage = Crud::PAGE_NEW === $pageName;

        return [
            TextField::new('email')
                ->setLabel($this->translator->trans('crud.email'))
                ->setRequired(true)
                ->setMaxLength(255)
                ->setHelp($this->translator->trans('help.between_x_and_x_characters_maximum', ['first' => 5, 'last' => 255]))
                ->setColumns(6),
            TextField::new('phone')
                ->setLabel($this->translator->trans('crud.phone'))
                ->setRequired(false)
                ->onlyOnForms()
                ->setMaxLength(10)
                ->setHelp($this->translator->trans('help.x_characters_required', ['first' => 10]))
                ->setColumns(6),
            TextField::new('lastname')
                ->setLabel($this->translator->trans('crud.last_name'))
                ->setRequired(true)
                ->setMaxLength(50)
                ->setHelp($this->translator->trans('help.between_x_and_x_characters_maximum', ['first' => 1, 'last' => 50]))
                ->formatValue(function ($value, $entity) {
                    return $value ?: '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_last_name').'</span>';
                })
                ->setColumns(6),
            TextField::new('firstname')
                ->setLabel($this->translator->trans('crud.first_name'))
                ->setRequired(true)
                ->setMaxLength(50)
                ->setHelp($this->translator->trans('help.between_x_and_x_characters_maximum', ['first' => 1, 'last' => 50]))
                ->formatValue(function ($value, $entity) {
                    return $value ?: '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_first_name').'</span>';
                })
                ->setColumns(6),
            TextField::new('linkgithub')
                ->setLabel($this->translator->trans('crud.github_link'))
                ->setRequired(false)
                ->setMaxLength(255)
                ->setHelp($this->translator->trans('help.between_x_and_x_characters_maximum', ['first' => 1, 'last' => 255]))
                ->formatValue(function ($value, $entity) {
                    if (!$value) {
                        return '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_link').'</span>';
                    }

                    return sprintf('<a href="%s" target="_blank" class="btn btn-secondary me-2 d-inline-flex align-items-center justify-content-center gap-2"><span>%s</span><i class="fa-brands fa-github"></i></a>', $value, $this->translator->trans('crud.show'));
                })
                ->setColumns(6),
            TextField::new('linklinkedin')
                ->setLabel($this->translator->trans('crud.linkedin_link'))
                ->setRequired(false)
                ->setMaxLength(255)
                ->setHelp($this->translator->trans('help.between_x_and_x_characters_maximum', ['first' => 1, 'last' => 255]))
                ->formatValue(function ($value, $entity) {
                    if (!$value) {
                        return '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_link').'</span>';
                    }

                    $viewBtn = sprintf('<a href="%s" target="_blank" class="btn btn-primary me-2 d-inline-flex align-items-center justify-content-center gap-2"><span>%s</span><i class="fa-brands fa-linkedin"></i></a>', $value, $this->translator->trans('crud.show'));

                    return $viewBtn;
                })
                ->setColumns(6),
            Field::new('img')
                ->setLabel($this->translator->trans('crud.profile_picture'))
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
            Field::new('cv')
                ->setLabel($this->translator->trans('crud.your_cv'))
                ->setRequired(false)
                ->hideOnIndex()
                ->hideOnDetail()
                ->setHelp($this->translator->trans('crud.pdf_help'))
                ->setFormType(VichFileType::class)
                ->setFormTypeOptions([
                    'download_label' => true,
                    'allow_delete' => false,
                    'attr' => [
                        'accept' => 'application/pdf',
                    ]
                ])
                ->setColumns(6),
            ImageField::new('imgfilename')
                ->setLabel($this->translator->trans('crud.profile_picture'))
                ->setBasePath('images/profil/')
                ->hideOnForm()
                ->formatValue(function ($value) {
                    return $value ? 'images/profil/'.$value : 'images/profil/default-avatar.webp';
                }),
            FormField::addFieldset($this->translator->trans('crud.change_password')),
            FormField::addRow(),
            TextField::new('CurrentPassword')
                ->setLabel($this->translator->trans('crud.current_password'))
                ->setFormType(PasswordType::class)
                ->setColumns(6)
                ->onlyOnForms()
                ->setFormTypeOption('mapped', false)
                ->setHelp($this->translator->trans('crud.current_password_help'))
                ->renderAsHtml(),
            FormField::addRow(),
            TextField::new('PlainPassword')
                ->setLabel($this->translator->trans('crud.plain_password'))
                ->setFormType(PasswordType::class)
                ->setColumns(6)
                ->onlyOnForms()
                ->setFormTypeOption('mapped', false)
                ->setHelp($this->translator->trans('help.password_requirements', ['first' => 1, 'last' => 255]))
                ->renderAsHtml(),
            TextField::new('ConfirmPassword')
                ->setLabel($this->translator->trans('crud.confirm_password'))
                ->setFormType(PasswordType::class)
                ->setColumns(6)
                ->onlyOnForms()
                ->setFormTypeOption('mapped', false)
                ->setHelp($this->translator->trans('help.password_requirements', ['first' => 1, 'last' => 255]))
                ->renderAsHtml(),
            TextField::new('cvfilename')
                ->hideOnForm()
                ->setLabel($this->translator->trans('crud.cv'))
                ->formatValue(function ($value, $entity) {
                    if (!$value) {
                        return '<span class="badge bg-secondary text-white">'.$this->translator->trans('empty.no_file').'</span>';
                    }

                    $path = '/uploads/' . $value;
                    $viewBtn = sprintf('<a href="%s" target="_blank" class="btn btn-success me-2"><i class="fa fa-eye"></i></a>', $path, null);
                    $downloadBtn = sprintf('<a href="%s" download class="btn btn-warning"><i class="fa fa-download"></i></a>', $path, null);

                    return '<div class="d-flex">'.$viewBtn.$downloadBtn.'</div>';
                }),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setLabel($this->translator->trans('crud.created_at'))
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
