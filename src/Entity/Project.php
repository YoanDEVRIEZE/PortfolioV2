<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'validation.title.not_blank')]
    #[Assert\Length(min: 1, max: 100, minMessage: 'validation.title.length_min', maxMessage: 'validation.title.length_max')]
    private ?string $title = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'validation.description.not_blank')]
    #[Assert\Length(min: 1, max: 150, minMessage: 'validation.description.length_min', maxMessage: 'validation.description.length_max')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'validation.content.not_blank')]
    #[Assert\Length(min: 20, max: 5000, minMessage: 'validation.content.length_min', maxMessage: 'validation.content.length_max')]
    private ?string $content = null;

    #[Ignore]
    #[Vich\UploadableField(mapping: 'img_project_coverpicture', fileNameProperty: 'coverpicturefilename')]
    #[Assert\File(maxSize: '10240k', mimeTypes: ['image/webp'], maxSizeMessage: 'validation.file.max_size', mimeTypesMessage: 'validation.file.webp_only')]
    private ?File $coverpicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverpicturefilename = null;

    #[Ignore]
    #[Vich\UploadableField(mapping: 'img_project_picture', fileNameProperty: 'projectpicturefilename')]
    #[Assert\File(maxSize: '10240k', mimeTypes: ['image/webp'], maxSizeMessage: 'validation.file.max_size', mimeTypesMessage: 'validation.file.webp_only')]
    private ?File $projectpicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $projectpicturefilename = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(requireTld: true, message: 'validation.url.invalid')]
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

    public function getCoverpicture(): ?File
    {
        return $this->coverpicture;
    }

    public function setCoverpicture(?File $coverpicture = null): void
    {
        $this->coverpicture = $coverpicture;
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

    public function getProjectpicture(): ?File
    {
        return $this->projectpicture;
    }

    public function setProjectpicture(?File $projectpicture = null): void
    {
        $this->projectpicture = $projectpicture;
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

    #[ORM\PrePersist]
    public function setCreateAt(): static
    {
        $this->createAt = new \DateTimeImmutable();

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    #[ORM\PreUpdate]
    public function setUpdateAt(): static
    {
        $this->updateAt = new \DateTimeImmutable();

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
