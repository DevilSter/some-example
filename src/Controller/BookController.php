<?php
declare(strict_types=1);

namespace App\Controller;


use App\Entity\Book;
use App\Form\BookCreateForm;
use App\Form\BookSearchForm;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController
 * @package App\Controller
 *
 */
class BookController extends OurAbstractController
{
    /**
     * @var BookRepository
     */
    private BookRepository $_bookRepository;
    /**
     * @var AuthorRepository
     */
    private AuthorRepository $_authorRepository;

    /**
     * BookController constructor.
     * @param BookRepository $_bookRepository
     * @param AuthorRepository $_authorRepository
     */
    public function __construct(BookRepository $_bookRepository, AuthorRepository $_authorRepository)
    {
        $this->_bookRepository = $_bookRepository;
        $this->_authorRepository = $_authorRepository;
    }

    /**
     * @Route("/book/create", name = "book_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request) : JsonResponse {
        $result = [
            'success' => true
        ];

        // Тут мы работаем без привязки к сущности. Ибо надо создавать ДТО чтобы потом конвертировать
        // ИД авторов в объекты
        $form = $this->createForm(BookCreateForm::class);
        $form->submit($this->getJson($request));

        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var Book $author */
                $bookParams = $form->getData();
                $book = new Book();
                $book->setTitle($bookParams['title']);

                // Ищем и добавляем нужных авторов к книге
                foreach ($bookParams['authors'] as $authorId) {
                    $author = $this->_authorRepository->find($authorId);

                    if($author != null) {
                        $book->addAuthor($author);
                    }
                }
                if (count($book->getAuthors()) == 0) {
                    throw new Exception('Книга не может быть без авторов');
                }
                $this->_bookRepository->create($book);

                return new JsonResponse($result, Response::HTTP_CREATED);
            } catch (Exception $ex) {
                $errors[] = $ex->getMessage();
            }
        } else {
            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }
        }

        $result['success'] = false;
        $result['errors'] = $errors;

        return new JsonResponse($result, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Ищем одну книгу по точному совпадению названия
     *
     * @Route("/book/search", name = "book_search", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchAction(Request $request) : JsonResponse {
        $form = $this->createForm(BookSearchForm::class);
        $form->submit($this->getJson($request));

        $errors = [];
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $searchParams = $form->getData();

                $book = $this->_bookRepository->findOneBy([
                    'title' => $searchParams['title']
                ]);

                if($book == null) {
                    throw new Exception("Книга не найдена");
                }

                return new JsonResponse($book, Response::HTTP_OK);
            } catch (Exception $ex) {
                $errors[] = $ex->getMessage();
            }
        } else {
            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }
        }

        $result['success'] = false;
        $result['errors'] = $errors;

        return new JsonResponse($result, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Информация о книге локализированная
     *
     * @Route("/{_locale<en|ru>}/book/{id}", name = "book_info", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function infoAction(int $id, Request $request): JsonResponse {
        $errors = [];
        try {
            $book = $this->_bookRepository->find($id);

            if($book == null) {
                throw new Exception("Книга не найдена");
            }

            if($request->getLocale() === 'en') {
                $title = transliterator_transliterate('Any-Latin; Latin-ASCII', $book->getTitle());
                $book->setTitle($title);
            }
            return new JsonResponse($book, Response::HTTP_OK);
        } catch(Exception $ex) {
            $errors[] = $ex->getMessage();
        }


        $result['success'] = false;
        $result['errors'] = $errors;

        return new JsonResponse($result, Response::HTTP_BAD_REQUEST);
    }
}