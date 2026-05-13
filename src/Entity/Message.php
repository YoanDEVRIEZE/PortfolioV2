<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'validation.lastname.not_blank')]
    #[Assert\Length(min: 1, max: 20, minMessage: 'validation.lastname.length_min', maxMessage: 'validation.lastname.length_max')]
    private ?string $lastname = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'validation.firstname.not_blank')]
    #[Assert\Length(min: 1, max: 20, minMessage: 'validation.firstname.length_min', maxMessage: 'validation.firstname.length_max')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'validation.email.not_blank')]
    #[Assert\Email(message: 'validation.email.invalid')]
    #[Assert\Length(max: 180, maxMessage: 'validation.email.length_max')]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'validation.content.not_blank')]
    #[Assert\Length(min: 20, max: 5000, minMessage: 'validation.content.length_min', maxMessage: 'validation.content.length_max')]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isread = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createAt): static
    {
        $this->createdAt = $createAt;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isread;
    }

    public function setIsRead(bool $isread): static
    {
        $this->isread = $isread;

        return $this;
    }
}
