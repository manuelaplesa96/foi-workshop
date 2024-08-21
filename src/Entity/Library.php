<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LibraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryRepository::class)]
#[ApiResource]
class Library
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(name: 'id', type: 'integer')]
  private int $id;

  #[ORM\Column(name: 'uuid', type: 'uuid', unique: true)]
  private string $uuid;

  #[ORM\Column(name: 'name', type: 'string')]
  private string $name;

  #[ORM\Column(name: 'location', type: 'string')]
  private string $location;

  /**
   * @var Collection<int, Book>
   */
  #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'library', orphanRemoval: true)]
  private Collection $books;

  public function __construct()
  {
    $this->books = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getUuid(): ?string
  {
    return $this->uuid;
  }

  public function setUuid(string $uuid): static
  {
    $this->uuid = $uuid;

    return $this;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getLocation(): ?string
  {
    return $this->location;
  }

  public function setLocation(string $location): static
  {
    $this->location = $location;

    return $this;
  }

  /**
   * @return Collection<int, Book>
   */
  public function getBooks(): Collection
  {
    return $this->books;
  }

  public function addBook(Book $book): static
  {
    if (!$this->books->contains($book)) {
      $this->books->add($book);
      $book->setLibrary($this);
    }

    return $this;
  }

  public function removeBook(Book $book): static
  {
    if ($this->books->removeElement($book)) {
      // set the owning side to null (unless already changed)
      if ($book->getLibrary() === $this) {
        $book->setLibrary(null);
      }
    }

    return $this;
  }
}
