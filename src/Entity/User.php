<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(message: 'validation.email.invalid')]
    #[Assert\NotBlank(message: 'validation.email.not_blank')]
    #[Assert\Length(max: 180, maxMessage: 'validation.email.length_max')]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'validation.password.not_blank')]
    #[Assert\Length(min: 6, max: 255, minMessage: 'validation.password.length_min', maxMessage: 'validation.password.length_max')]
    private ?string $password = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(min: 5, max: 50, minMessage: 'validation.lastname.length_min', maxMessage: 'validation.lastname.length_max')]
    private ?string $lastname = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(min: 2, max: 50, minMessage: 'validation.firstname.length_min', maxMessage: 'validation.firstname.length_max')]
    private ?string $firstname = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Length(min: 10, max: 10, minMessage: 'validation.phone.length_exact', maxMessage: 'validation.phone.length_exact')]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(requireTld: true, message: 'validation.url.invalid')]
    private ?string $linkgithub = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(requireTld: true, message: 'validation.url.invalid')]
    private ?string $linklinkedin = null;

    #[Ignore] 
    #[Vich\UploadableField(mapping: 'cv_user', fileNameProperty: 'cvfilename')]
    #[Assert\File(maxSize: '10240k', mimeTypes: ['application/pdf'], maxSizeMessage: 'validation.file.max_size', mimeTypesMessage: 'validation.file.pdf_only')]
    private ?File $cv = null;

    #[Ignore] 
    #[Vich\UploadableField(mapping: 'img_user', fileNameProperty: 'imgfilename')]
    #[Assert\File(maxSize: '10240k', mimeTypes: ['image/webp'], maxSizeMessage: 'validation.file.max_size', mimeTypesMessage: 'validation.file.webp_only')]
    private ?File $img = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cvfilename = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgfilename = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'password' => $this->password ? hash('crc32c', $this->password) : null,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'phone' => $this->phone,
            'linkgithub' => $this->linkgithub,
            'linklinkedin' => $this->linklinkedin,
            'cvfilename' => $this->cvfilename,
            'imgfilename' => $this->imgfilename,
            'createdAt' => $this->createdAt,
            'updateAt' => $this->updatedAt
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->roles = $data['roles'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->lastname = $data['lastname'] ?? null;
        $this->firstname = $data['firstname'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->linkgithub = $data['linkgithub'] ?? null;
        $this->linklinkedin = $data['linklinkedin'] ?? null;
        $this->cvfilename = $data['cvfilename'] ?? null;
        $this->imgfilename = $data['imgfilename'] ?? null;
        $this->createdAt = $data['createdAt'] ?? null;
        $this->updatedAt = $data['updateAt'] ?? null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLinkgithub(): ?string
    {
        return $this->linkgithub;
    }

    public function setLinkgithub(?string $linkgithub): static
    {
        $this->linkgithub = $linkgithub;

        return $this;
    }

    public function getLinklinkedin(): ?string
    {
        return $this->linklinkedin;
    }

    public function setLinklinkedin(?string $linklinkedin): static
    {
        $this->linklinkedin = $linklinkedin;

        return $this;
    }

    public function getCv(): ?File
    {
        return $this->cv;
    }

    public function setCv(?File $cv = null): void
    {
        $this->cv = $cv;

        if($cv) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImg(): ?File
    {
        return $this->img;
    }

    public function setImg(?File $img = null): void
    {
        $this->img = $img;
        
        if($img) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCvfilename(): ?string
    {
        return $this->cvfilename;
    }

    public function setCvfilename(?string $cvfilename): static
    {
        $this->cvfilename = $cvfilename;

        return $this;
    }

    public function getImgfilename(): ?string
    {
        return $this->imgfilename;
    }

    public function setImgfilename(?string $imgfilename): static
    {
        $this->imgfilename = $imgfilename;

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
}
