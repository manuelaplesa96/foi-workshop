<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BorrowedBookRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BorrowedBookRepository::class)]
#[ApiResource(
  operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Patch(
      denormalizationContext: [
        'groups' => ['borrowed_book:update']
      ]
    ),
    new Delete()
  ],

  normalizationContext: [
    'groups' => ['borrowed_book:read']
  ],
  denormalizationContext: [
    'groups' => ['borrowed_book:write']
  ]
)]
class BorrowedBook
{
  #[
    Groups(['borrowed_book:read']),
    ORM\Id,
    ORM\GeneratedValue,
    ORM\Column(name: 'id', type: 'integer')
  ]
  private int $id;

  #[
    Assert\Type('datetime_immutable'),
    Groups(['borrowed_book:read']),
    ORM\Column(name: 'borrowed_at', type: 'datetime_immutable')
  ]
  private DateTimeImmutable $borrowedAt;

  #[
    Assert\Type('boolean'),
    Groups(['borrowed_book:read']),
    ORM\Column(name: 'returned', type: 'boolean')
  ]
  private bool $returned = false;

  #[
    Assert\Type('integer'),
    Assert\Range(min: 0, max: 100),
    Groups(['borrowed_book:read', 'borrowed_book:update']),
    ORM\Column(name: 'percentage_read', type: 'integer')
  ]
  private int $percentageRead = 0;

  #[
    Groups(['borrowed_book:read', 'borrowed_book:write']),
    ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'borrowedBooks'),
    ORM\JoinColumn(nullable: false)
  ]
  private Book $book;

  public function __construct()
  {
    $this->borrowedAt = new DateTimeImmutable();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getBorrowedAt(): DateTimeImmutable
  {
    return $this->borrowedAt;
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
