<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->seedBooks($manager);
        $manager->flush();

        $this->seedAuthors($manager);
        $manager->flush();
    }


    /**
     * @param ObjectManager $manager
     */
    private function seedBooks(&$manager) : void
    {
        $books = [
            [
                'name' => 'Изучаем Python 4-е издание',
                'year' => 2010,
                'isbn' => '978-5-93286-159-2',
                'pages_count' => 1280,
            ],
            [
                'name' => 'Git для профессионального программиста',
                'year' => 2017,
                'isbn' => '978-5-496-01763-3',
                'pages_count' => 496,
            ],
            [
                'name' => 'Mastering Symfony',
                'year' => 2016,
                'isbn' => '978-1784390310',
                'pages_count' => 290,
            ],
            [
                'name' => 'Как пройти собеседование в ИТМО',
                'year' => 2019,
                'isbn' => '978-1784390315',
                'pages_count' => 1890,
            ],
        ];

        foreach ($books as $item) {
            $book = new Book();
            $book->setName($item['name']);
            $book->setYear($item['year']);
            $book->setIsbn($item['isbn']);
            $book->setPagesCount($item['pages_count']);
            $manager->persist($book);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    private function seedAuthors(&$manager) : void
    {
        $authors = [
            [
                'last_name' => 'Лутц',
                'first_name' => 'Марк',
                'middle_name' => null,
                'associated' => ['978-5-93286-159-2'],
            ],
            [
                'last_name' => 'Чакон',
                'first_name' => 'Скотт',
                'middle_name' => null,
                'associated' => ['978-5-496-01763-3'],
            ],
            [
                'last_name' => 'Штрауб',
                'first_name' => 'Бен',
                'middle_name' => null,
                'associated' => ['978-5-496-01763-3'],
            ],
            [
                'last_name' => 'Salehi',
                'first_name' => 'Sohali',
                'middle_name' => null,
                'associated' => ['978-1784390310'],
            ],
            [
                'last_name' => 'Титов',
                'first_name' => 'Сергей',
                'middle_name' => 'Антонович',
                'associated' => ['978-1784390315'],
            ]
        ];

        foreach ($authors as $item) {
            $author = new Author();
            $author->setLastName($item['last_name']);
            $author->setFirstName($item['first_name']);
            $author->setMiddleName($item['middle_name']);

            foreach ($item['associated'] as $assoc) {
                $author->addBook($manager->getRepository(Book::class)->findOneBy(['isbn' => $assoc]));
            }

            $manager->persist($author);
        }
    }
}
