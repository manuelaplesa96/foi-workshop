<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource]
class Book
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(name: 'id', type: 'integer')]
  private int $id;

  #[ORM\Column(name: 'uuid', type: 'uuid', unique: true)]
  private string $uuid;

  #[ORM\Column(name: 'title', type: 'string')]
  private string $title;

  #[ORM\Column(name: 'author', type: 'string')]
  private string $author;

  /**
   * @var Collection<int, BorrowedBook>
   */
  #[ORM\OneToMany(targetEntity: BorrowedBook::class, mappedBy: 'book', orphanRemoval: true)]
  private Collection $borrowedBooks;

  #[ORM\ManyToOne(targetEntity: Library::class, inversedBy: 'books')]
  #[ORM\JoinColumn()]
  private ?Library $library = null;

  public function __construct()
  {
    $this->borrowedBooks = new ArrayCollection();
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getUuid(): string
  {
    return $this->uuid;
  }

  public function setUuid(string $uuid): static
  {
    $this->uuid = $uuid;

    return $this;
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
