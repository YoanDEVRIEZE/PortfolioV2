<?php

namespace App\Entity;

use App\Repository\SiteParameterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: SiteParameterRepository::class)]
class SiteParameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'validation.title.not_blank')]
    #[Assert\Length(min: 1, max: 100, minMessage: 'validation.title.length_min', maxMessage: 'validation.title.length_max')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'validation.description.not_blank')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'validation.description.length_min', maxMessage: 'validation.description.length_max')]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?array $keyword = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'validation.media_description.not_blank')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'validation.media_description.length_min', maxMessage: 'validation.media_description.length_max')]
    private ?string $mediadescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(requireTld: true, message: 'validation.url.invalid')]
    private ?string $urlsite = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function getKeyword(): ?array
    {
        return $this->keyword;
    }

    public function setKeyword(?array $keyword): static
    {
        $this->keyword = $keyword;

        return $this;
    }

    public function getMediadescription(): ?string
    {
        return $this->mediadescription;
    }

    public function setMediadescription(string $mediadescription): static
    {
        $this->mediadescription = $mediadescription;

        return $this;
    }

    public function getUrlsite(): ?string
    {
        return $this->urlsite;
    }

    public function setUrlsite(?string $urlsite): static
    {
        $this->urlsite = $urlsite;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreateAt(): static
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
}
