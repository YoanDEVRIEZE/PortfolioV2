<?php

namespace App\Entity;

use App\Enum\CareerStatus;
use App\Repository\CareerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Ignore;

#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CareerRepository::class)]
class Career
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 100)]
    private ?string $position = null;

    #[ORM\Column]
    private ?\DateTime $startdate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $enddate = null;

    #[ORM\Column(enumType: CareerStatus::class)]
    private ?CareerStatus $status = null;

    #[Ignore] 
    #[Vich\UploadableField(mapping: 'img_coverpicture', fileNameProperty: 'coverpicturefilename')]
    #[Assert\File(maxSize: '10240k', mimeTypes: ['image/webp'], maxSizeMessage: 'validation.file.max_size', mimeTypesMessage: 'validation.file.webp_only')]
    private ?File $coverpicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverpicturefilename = null;

    #[Ignore] 
    #[Vich\UploadableField(mapping: 'img_jobpicture', fileNameProperty: 'jobpicturefilename')]
    #[Assert\File(maxSize: '10240k', mimeTypes: ['image/webp'], maxSizeMessage: 'validation.file.max_size', mimeTypesMessage: 'validation.file.webp_only')]
    private ?File $jobpicture = null;

    #[ORM\Column(length: 255)]
    private ?string $jobpicturefilename = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'careers')]
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getStartdate(): ?\DateTime
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTime $startdate): static
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTime
    {
        return $this->enddate;
    }

    public function setEnddate(?\DateTime $enddate): static
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getStatus(): ?CareerStatus
    {
        return $this->status;
    }

    public function setStatus(CareerStatus $status): static
    {
        $this->status = $status;

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

    public function getJobpicture(): ?File
    {
        return $this->jobpicture;
    }

    public function setJobpicture(?File $jobpicture = null): void
    {
        $this->jobpicture = $jobpicture;
    }

    public function getJobpicturefilename(): ?string
    {
        return $this->jobpicturefilename;
    }

    public function setJobpicturefilename(?string $jobpicturefilename): static
    {
        $this->jobpicturefilename = $jobpicturefilename;

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
