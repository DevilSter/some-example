<?php
declare(strict_types=1);

namespace App\DataFixtures;


use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    const AUTHOR_REFERENCE = "author-";

    // С какого ИД стартует запись в БД (по идее они все по порядку, поэтому для скорости так)
    static int $startId = 0;
    static int $idCount = 0;

    public function load(ObjectManager $manager)
    {
        echo ">>> Начинаем создавать авторов. Они могут существовать без книг. \n";

        $authorsArray = file(__DIR__."/helpers/example_authors.txt");
        $authorsArray = array_unique($authorsArray);

        foreach ($authorsArray as $authorStr) {
            $fio = explode(' ', $authorStr);

            $author = new Author();
            if(count($fio) === 3) {
                $author->setFirstName($fio[2]);
                $author->setMiddleName($fio[1]);
                $author->setLastName($fio[0]);
            } else if(count($fio) === 2) {
                $author->setFirstName($fio[1]);
                $author->setLastName($fio[0]);
            } else {
                continue;
            }

            $manager->persist($author);

            $this->addReference(self::AUTHOR_REFERENCE.$author->getId(), $author);

            if(self::$startId == 0 && $author->getId()) self::$startId = $author->getId();
        }

        $manager->flush();
        self::$idCount = count($authorsArray);

        echo ">>> Авторы созданы. В кол-ве ".self::$idCount." человек. \n";
    }
}