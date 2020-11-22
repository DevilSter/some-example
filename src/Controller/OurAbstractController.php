<?php
declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class OurAbstractController extends AbstractController
{
    const DEFAULT_PAGE_LIMIT = 20;

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getJson(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }

        return $data;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     */
    protected function getPageLimitOffset(int $page, int $limit) : array {
        // Эксепшены не бросаем - применяем дефолты
        $page = ($page > 0) ? $page : 1;
        $limit = ($limit > 0) ? $limit : OurAbstractController::DEFAULT_PAGE_LIMIT;

        return [
            ($page - 1) * $limit,
            $limit
        ];
    }
}