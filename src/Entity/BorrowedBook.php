<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BorrowedBookRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowedBookRepository::class)]
#[ApiResource]
class BorrowedBook
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(name: 'id', type: 'integer')]
  private int $id;

  #[ORM\Column(name: 'uuid', type: 'uuid', unique: true)]
  private string $uuid;

  #[ORM\Column]
  private DateTimeImmutable $borrowedAt;

  #[ORM\Column(name: 'returned', type: 'boolean')]
  private bool $returned = false;

  #[ORM\Column(name: 'percentage_read', type: 'integer')]
  private int $percentageRead = 0;

  #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'borrowedBooks')]
  #[ORM\JoinColumn(nullable: false)]
  private Book $book;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getBorrowedAt(): DateTimeImmutable
  {
    return $this->borrowedAt;
  }

  public function setBorrowedAt(DateTimeImmutable $borrowedAt): static
  {
    $this->borrowedAt = $borrowedAt;

    return $this;
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

  public function isReturned(): bool
  {
    return $this->returned;
  }

  public function setReturned(bool $returned): static
  {
    $this->returned = $returned;

    return $this;
  }

  public function getPercentageRead(): int
  {
    return $this->percentageRead;
  }

  public function setPercentageRead(int $percentageRead): static
  {
    $this->percentageRead = $percentageRead;

    return $this;
  }

  public function getBook(): Book
  {
    return $this->book;
  }

  public function setBook(Book $book): static
  {
    $this->book = $book;

    return $this;
  }
}
