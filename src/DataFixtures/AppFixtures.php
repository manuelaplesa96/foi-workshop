<?php

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\BorrowedBookFactory;
use App\Factory\LibraryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    LibraryFactory::createMany(2);
    BookFactory::createMany(5, static function () {
      return ['library' => LibraryFactory::random()];
    });
    BorrowedBookFactory::createMany(10, static function () {
      return ['book' => BookFactory::random()];
    });
  }
}
