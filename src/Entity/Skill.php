<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Title should not be blank.')]
    #[Assert\Length(min: 1, max: 100, minMessage: 'Title must be at least {{ limit }} characters long.', maxMessage: 'Title cannot be longer than {{ limit }} characters.')]
    private ?string $title = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Level should not be blank.')]
    #[Assert\Range(min: 1, max: 100, minMessage: 'Level must be at least {{ limit }}.', maxMessage: 'Level cannot be greater than {{ limit }}.')]
    private ?int $level = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagefilename = null;

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank(message: 'Progress bar color should not be blank.')]
    #[Assert\Regex(pattern: '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', message: 'La couleur doit être au format HEX (#RRGGBB ou #RGB).')]
    private ?string $progressbarcolor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    /**
     * @var Collection<int, Career>
     */
    #[ORM\ManyToMany(targetEntity: Career::class, mappedBy: 'skills')]
    private Collection $careers;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'skills')]
    private Collection $projects;

    public function __construct()
    {
        $this->careers = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImagefilename(): ?string
    {
        return $this->imagefilename;
    }

    public function setImagefilename(?string $imagefilename): static
    {
        $this->imagefilename = $imagefilename;

        return $this;
    }

    public function getProgressbarcolor(): ?string
    {
        return $this->progressbarcolor;
    }

    public function setProgressbarcolor(string $progressbarcolor): static
    {
        $this->progressbarcolor = $progressbarcolor;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection<int, Career>
     */
    public function getCareers(): Collection
    {
        return $this->careers;
    }

    public function addCareer(Career $career): static
    {
        if (!$this->careers->contains($career)) {
            $this->careers->add($career);
            $career->addSkill($this);
        }

        return $this;
    }

    public function removeCareer(Career $career): static
    {
        if ($this->careers->removeElement($career)) {
            $career->removeSkill($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addSkill($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeSkill($this);
        }

        return $this;
    }
}
