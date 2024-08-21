<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\LibraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LibraryRepository::class)]
#[ApiResource(
  operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Patch(),
    new Delete()
  ],
  normalizationContext: [
    'groups' => ['library:read']
  ],
  denormalizationContext: [
    'groups' => ['library:write']
  ]
)]
class Library
{
  #[
    Groups(['library:read']),
    ORM\Id,
    ORM\GeneratedValue,
    ORM\Column(name: 'id', type: 'integer')
  ]
  private int $id;

  #[
    Assert\NotBlank,
    Assert\Type('string'),
    Groups(['library:read', 'library:write']),
    ORM\Column(name: 'name', type: 'string')
  ]
  private string $name;

  #[
    Assert\NotBlank,
    Assert\Type('string'),
    Groups(['library:read', 'library:write']),
    ORM\Column(name: 'location', type: 'string')
  ]
  private string $location;

  /**
   * @var Collection<int, Book>
   */
  #[
    Groups(['library:read']),
    ORM\OneToMany(targetEntity: Book::class, mappedBy: 'library', orphanRemoval: true)
  ]
  private Collection $books;

  public function __construct()
  {
    $this->books = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
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
