<?php

namespace App\Entity;

use App\Repository\SiteParameterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteParameterRepository::class)]
class SiteParameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Title should not be blank.')]
    #[Assert\Length(min: 1, max: 100, minMessage: 'Title must be at least {{ limit }} characters long.', maxMessage: 'Title cannot be longer than {{ limit }} characters.')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description should not be blank.')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Description must be at least {{ limit }} characters long.', maxMessage: 'Description cannot be longer than {{ limit }} characters.')]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?array $keyword = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Media description should not be blank.')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Media description must be at least {{ limit }} characters long.', maxMessage: 'Media description cannot be longer than {{ limit }} characters.')]
    private ?string $mediadescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(requireTld: true, message: "The URL '{{ value }}' is not a valid URL.")]
    private ?string $urlsite = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

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
}
