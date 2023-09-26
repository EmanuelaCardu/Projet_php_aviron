<?php

namespace App\Entity;

use App\Repository\CourEauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourEauRepository::class)]
class CourEau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    private ?string $distance = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'courEaux')]
    private Collection $User;

    #[ORM\OneToMany(mappedBy: 'courEau', targetEntity: Photo::class)]
    private Collection $Photo;

    public function __construct()
    {
        $this->User = new ArrayCollection();
        $this->Photo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getDistance(): ?string
    {
        return $this->distance;
    }

    public function setDistance(string $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(User $user): static
    {
        if (!$this->User->contains($user)) {
            $this->User->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->User->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhoto(): Collection
    {
        return $this->Photo;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->Photo->contains($photo)) {
            $this->Photo->add($photo);
            $photo->setCourEau($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->Photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCourEau() === $this) {
                $photo->setCourEau(null);
            }
        }

        return $this;
    }
}
