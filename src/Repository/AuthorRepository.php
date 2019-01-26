<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @return Author[] Returns an array of Author with Book objects
     */

    public function findManyJoinedToBooks()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.books', 'a')
            ->addSelect('a')
            ->getQuery()
            ->getResult();
    }
}
