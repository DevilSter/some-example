<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorCreateForm;
use App\Repository\AuthorRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController
 * @package App\Controller
 *
 * @Route("/author", name="author_")
 */
class AuthorController extends OurAbstractController
{
    /**
     * @var AuthorRepository
     */
    private AuthorRepository $_authorRepository;

    /**
     * AuthorController constructor.
     * @param AuthorRepository $_authorRepository
     */
    public function __construct(AuthorRepository $_authorRepository)
    {
        $this->_authorRepository = $_authorRepository;
    }

    /**
     * @Route("/{page}/{limit}", name = "list", methods={"GET"},requirements={"page"="\d+", "limit"="\d+"})
     *
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     */
    public function listAction(int $page = 1, int $limit = 20): JsonResponse {
        $page = abs($page);
        $offset = ($page - 1) * $limit;
        $result = $this->_authorRepository->findBy([], null, $limit, $offset);

        return new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * @Route("/create", name = "create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $result = [
            'success' => true
        ];

        $form = $this->createForm(AuthorCreateForm::class, new Author());
        $form->submit($this->getJson($request));

        $errors = [];

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var Author $author */
                $author = $form->getData();
                $this->_authorRepository->create($author);

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
}