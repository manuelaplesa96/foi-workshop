<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\UniqueConstraint(name: 'bookTitleByAuthor', columns: ['title', 'author'])]
#[ApiResource(
  operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Patch(),
    new Delete()
  ],

  normalizationContext: [
    'groups' => ['book:read']
  ],
  denormalizationContext: [
    'groups' => ['book:write']
  ]
)]
class Book
{
  #[
    Groups(['book:read']),
    ORM\Id,
    ORM\GeneratedValue,
    ORM\Column(name: 'id', type: 'integer')
  ]
  private int $id;

  #[
    Assert\NotBlank,
    Assert\Type('string'),
    Groups(['book:read', 'book:write']),
    ORM\Column(name: 'title', type: 'string')
  ]
  private string $title;

  #[
    Assert\NotBlank,
    Assert\Type('string'),
    Groups(['book:read', 'book:write']),
    ORM\Column(name: 'author', type: 'string')
  ]
  private string $author;

  /**
   * @var Collection<int, BorrowedBook>
   */
  #[
    Groups(['book:read']),
    ORM\OneToMany(targetEntity: BorrowedBook::class, mappedBy: 'book', orphanRemoval: true)
  ]
  private Collection $borrowedBooks;

  #[
    Groups(['book:read', 'book:write']),
    ORM\ManyToOne(targetEntity: Library::class, inversedBy: 'books'),
    ORM\JoinColumn
  ]
  private ?Library $library = null;

  public function __construct()
  {
    $this->borrowedBooks = new ArrayCollection();
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setTitle(string $title): static
  {
    $this->title = $title;

    return $this;
  }

  public function getAuthor(): string
  {
    return $this->author;
  }

  public function setAuthor(string $author): static
  {
    $this->author = $author;

    return $this;
  }

  /**
   * @return Collection<int, BorrowedBook>
   */
  public function getBorrowedBooks(): Collection
  {
    return $this->borrowedBooks;
  }

  public function addBorrowedBook(BorrowedBook $borrowedBook): static
  {
    if (!$this->borrowedBooks->contains($borrowedBook)) {
      $this->borrowedBooks->add($borrowedBook);
      $borrowedBook->setBook($this);
    }

    return $this;
  }

  public function getLibrary(): ?Library
  {
    return $this->library;
  }

  public function setLibrary(?Library $library): static
  {
    $this->library = $library;

    return $this;
  }
}
