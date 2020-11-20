<?php
declare(strict_types=1);

namespace App\DataFixtures;


use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $count = 0;
        echo ">>> Начинаем создавать книги. Им нужны авторы. \n";

        $booksArray = file(__DIR__."/helpers/example_books2.txt");
        $booksArray = array_unique($booksArray);

        foreach ($booksArray as $bookTitle) {
            $bookTitle = str_replace("\n", '', $bookTitle);
            $book = new Book();
            $book->setTitle($bookTitle);

            $authors = $this->getAuthorRefs();

            // Если авторов не набралось - кидаем эксепшн. Что-то не то значит. А книга без автора не может быть
            if(count($authors) === 0) throw new Exception("Что-то не могу выбрать авторов");

            foreach ($authors as $author) {
                $book->addAuthor($author);
            }

            $manager->persist($book);

            $count++;
        }

        $manager->flush();

        echo ">>> Книги созданы. В кол-ве ${count} штук. \n";
    }

    public function getDependencies()
    {
        return [
          AuthorFixtures::class
        ];
    }

    /**
     * Получаем случайный список авторов для книги
     *
     * @return array
     */
    private function getAuthorRefs() : array {
        $resultRefs = [];

        // У книги может быть от одного до 4 авторов
        $authorCount = intval(rand(1, 4));

        // Пытаемся вытащить из рефов авторов. Ограничиваем кол-во попыток чтобы не зациклится
        $startId = AuthorFixtures::$startId;
        $maxId = AuthorFixtures::$startId + AuthorFixtures::$idCount - 1;
        $tries = 0;
        while(count($resultRefs) < $authorCount && $tries++ < 10) {
            $id = intval(rand($startId, $maxId));
            // Исключаем дубли
            if(isset($resultRefs[$id])) continue;

            if($this->hasReference(AuthorFixtures::AUTHOR_REFERENCE.$id)) {
                $resultRefs[$id] = $this->getReference(AuthorFixtures::AUTHOR_REFERENCE.$id);
            }
        }

        return $resultRefs;
    }
}