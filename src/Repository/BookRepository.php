<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }


    /**
     * Простое добавление книги в базу. Не более.
     *
     * @param Book $book
     * @throws BookAlreadyExistsException
     * @throws ORMException
     */
    public function create(Book $book) {
        // Исключаем дублирование авторов по ФИО (без использования сложных индексов в БД)
        if($this->findOneBy([
                'title' => $book->getTitle(),
            ]) != null) {
            throw new BookAlreadyExistsException();
        };

        $this->_em->persist($book);
        $this->_em->flush();
    }

    /**
     * @param string $expression
     * @return array
     */
    public function findLike(string $expression) : array {
        $qb = $this->createQueryBuilder('p')
            ->where('p.title LIKE :par')
            ->setParameter("par", '%'.addcslashes($expression, '%_').'%');

        $query = $qb->getQuery();

        return $query->execute();
    }
}
