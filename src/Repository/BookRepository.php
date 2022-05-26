<?php

namespace App\Repository;

use App\Entity\Book;
use App\Model\Book\BookRepositoryCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $book): Book
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
        return $book;
    }

    public function reload(Book $book): Book
    {
        $this->getEntityManager()->refresh($book);
        return $book;
    }

    public function delete(Book $book): void
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }

    public function findByCriteria(BookRepositoryCriteria $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->orderBy('b.title', 'DESC');

        if ($criteria->authorId !== null) {
            $queryBuilder
                ->andWhere(':authorId MEMBER OF b.authors')
                ->setParameter('authorId', $criteria->authorId);
        }

        if ($criteria->categoryId !== null) {
            $queryBuilder
                ->andWhere(':categoryId MEMBER OF b.categories')
                ->setParameter('categoryId', $criteria->categoryId);
        }


        $queryBuilder->setMaxResults($criteria->itemsPerPage);
        $queryBuilder->setFirstResult(($criteria->page - 1) * $criteria->itemsPerPage);

        $paginator = new Paginator($queryBuilder->getQuery());
        return [
            'total' => \count($paginator),
            'itemsPerPage' => $criteria->itemsPerPage,
            'page' => $criteria->page,
            'data' => iterator_to_array($paginator->getIterator())
        ];
    }
}
