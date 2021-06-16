<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $author): Author
    {
        $this->getEntityManager()->persist($author);
        $this->getEntityManager()->flush();
        return $author;
    }

    public function reload(Author $author): Author
    {
        $this->getEntityManager()->refresh($author);
        return $author;
    }

    public function delete(Author $author)
    {
        $this->getEntityManager()->remove($author);
        $this->getEntityManager()->flush();
    }
}
