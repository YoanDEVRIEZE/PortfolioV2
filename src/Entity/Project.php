<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Title should not be blank.')]
    #[Assert\Length(min: 1, max: 100, minMessage: 'Title must be at least {{ limit }} characters long.', maxMessage: 'Title cannot be longer than {{ limit }} characters.')]
    private ?string $title = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Description should not be blank.')]
    #[Assert\Length(min: 1, max: 150, minMessage: 'Description must be at least {{ limit }} characters long.', maxMessage: 'Description cannot be longer than {{ limit }} characters.')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Content should not be blank.')]
    #[Assert\Length(min: 20, max: 5000, minMessage: 'Content must be at least {{ limit }} characters long.', maxMessage: 'Content cannot be longer than {{ limit }} characters.')]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $coverpicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverpicturefilename = null;

    #[ORM\Column(length: 255)]
    private ?string $projectpicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $projectpicturefilename = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(requireTld: true, message: "The URL '{{ value }}' is not a valid URL.")]
    private ?string $link = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'projects')]
    private Collection $skills;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverpicture(): ?string
    {
        return $this->coverpicture;
    }

    public function setCoverpicture(string $coverpicture): static
    {
        $this->coverpicture = $coverpicture;

        return $this;
    }

    public function getCoverpicturefilename(): ?string
    {
        return $this->coverpicturefilename;
    }

    public function setCoverpicturefilename(?string $coverpicturefilename): static
    {
        $this->coverpicturefilename = $coverpicturefilename;

        return $this;
    }

    public function getProjectpicture(): ?string
    {
        return $this->projectpicture;
    }

    public function setProjectpicture(string $projectpicture): static
    {
        $this->projectpicture = $projectpicture;

        return $this;
    }

    public function getProjectpicturefilename(): ?string
    {
        return $this->projectpicturefilename;
    }

    public function setProjectpicturefilename(?string $projectpicturefilename): static
    {
        $this->projectpicturefilename = $projectpicturefilename;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

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
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }
}
