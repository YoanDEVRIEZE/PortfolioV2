<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;
use Symfony\Component\Serializer\Attribute\Ignore;

#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'validation.title.not_blank')]
    #[Assert\Length(min: 1, max: 100, minMessage: 'validation.title.length_min', maxMessage: 'validation.title.length_max')]
    private ?string $title = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'validation.level.not_blank')]
    private ?int $level = null;

    #[Ignore] 
    #[Vich\UploadableField(mapping: 'img_skill', fileNameProperty: 'imagefilename')]
    #[Assert\File(maxSize: '10240k', mimeTypes: ['image/webp'], maxSizeMessage: 'validation.file.max_size', mimeTypesMessage: 'validation.file.webp_only')]
    private ?File $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagefilename = null;

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank(message: 'validation.progress_bar_color.not_blank')]
    #[Assert\Regex(pattern: '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', message: 'validation.color.hex_format')]
    private ?string $progressbarcolor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function getImage(): ?File
    {
        return $this->image;
    }

    public function setImage(File $image): void
    {
        $this->image = $image;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->createdAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable();

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
