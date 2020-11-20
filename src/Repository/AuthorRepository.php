<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Author;
use App\Exception\AuthorAlreadyExistsException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * Простое добавление автора в базу. Не более.
     *
     * @param Author $author
     * @throws ORMException
     * @throws AuthorAlreadyExistsException
     */
    public function create(Author $author) {
        // Исключаем дублирование авторов по ФИО (без использования сложных индексов в БД)
        if($this->findOneBy([
            'firstName' => $author->getFirstName(),
            'middleName' => $author->getMiddleName(),
            'lastName' => $author->getLastName(),
        ]) != null) {
            throw new AuthorAlreadyExistsException();
        };

        $this->_em->persist($author);
        $this->_em->flush();
    }
}
