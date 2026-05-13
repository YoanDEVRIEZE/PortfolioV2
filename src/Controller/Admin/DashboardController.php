<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\CareerRepository;
use App\Repository\MessageRepository;
use App\Repository\ProjectRepository;
use App\Repository\SkillRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted("ROLE_USER")]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private int $messagecount;
    private int $projectcount;
    private int $careercount;
    private int $skillcount;
    private iterable $projects;
    private iterable $careers;
    private iterable $skills;

    public function __construct(
        private Packages $assets,
        private AdminUrlGenerator $adminUrlGenerator,
        private MessageRepository $messageRepository,
        private ProjectRepository $projectRepository,
        private CareerRepository $careerRepository,
        private SkillRepository $skillRepository,
        private TranslatorInterface $translator,
    ) {
        $this->messagecount = $this->messageRepository->count([]);
        $this->projectcount = $this->projectRepository->count([]);
        $this->careercount = $this->careerRepository->count([]);
        $this->skillcount = $this->skillRepository->count([]);
        $this->projects = $this->projectRepository->findBy([], ['id' => 'DESC'], 3);
        $this->careers = $this->careerRepository->findBy([], ['id' => 'DESC'], 3);
        $this->skills = $this->skillRepository->findBy([], ['id' => 'DESC'], 3);
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard/dashboard.html.twig', [
            'messagecount' => $this->messagecount,
            'projectcount' => $this->projectcount,
            'careercount' => $this->careercount,
            'skillcount' => $this->skillcount,
            'projects' => $this->projects,
            'careers' => $this->careers,
            'skills' => $this->skills,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PortfolioV2')
            ->renderContentMaximized()
            ->setDefaultColorScheme('dark')
            ->setLocales([
                'en' => '🇬🇧 English', 
                'fr' => '🇫🇷 Français'
            ]);
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard($this->translator->trans('admin.dashboard'), 'fa fa-home'),
            MenuItem::linkTo(SiteParameterCrudController::class, $this->translator->trans('admin.menu_site_parameters'), 'fa-solid fa-gear'),
            MenuItem::linkTo(AboutCrudController::class, $this->translator->trans('admin.menu_about'), 'fa-solid fa-user'),
            MenuItem::linkTo(SkillCrudController::class, $this->translator->trans('admin.menu_skills'), 'fa-solid fa-code'),
            MenuItem::linkTo(CareerCrudController::class, $this->translator->trans('admin.menu_careers'), 'fa fa-briefcase'),
            MenuItem::linkTo(ProjectCrudController::class, $this->translator->trans('admin.menu_projects'), 'fa fa-diagram-project'),
            MenuItem::linkTo(MessageCrudController::class, $this->translator->trans('admin.menu_messages'), 'fa fa-envelope'),
            MenuItem::linkTo(UserCrudController::class, $this->translator->trans('admin.menu_user'), 'fa fa-users'),
            MenuItem::linkToRoute($this->translator->trans('admin.back_to_website'), 'fa-solid fa-arrow-left', 'app_home'),
        ];
    }

    public function configureUserMenu(
        UserInterface $user
    ): UserMenu {
        /** @var User $user */
        return parent::configureUserMenu($user)
            ->setAvatarUrl($this->assets->getUrl($user->getImgfilename() ? 'images/profil/'.$user->getImgfilename() : 'images/profil/default-avatar.webp'));
    }
}
