<?php

namespace App\Entity;

use App\Repository\CareerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $coverpicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverpicturefilename = null;

    #[ORM\Column(length: 255)]
    private ?string $jobpicture = null;

    #[ORM\Column(length: 255)]
    private ?string $jobpicturefilename = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getJobpicture(): ?string
    {
        return $this->jobpicture;
    }

    public function setJobpicture(string $jobpicture): static
    {
        $this->jobpicture = $jobpicture;

        return $this;
    }

    public function getJobpicturefilename(): ?string
    {
        return $this->jobpicturefilename;
    }

    public function setJobpicturefilename(string $jobpicturefilename): static
    {
        $this->jobpicturefilename = $jobpicturefilename;

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
