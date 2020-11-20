<?php
declare(strict_types=1);

namespace App\Exception;


use Exception;

final class AuthorAlreadyExistsException extends Exception
{
    /**
     * AuthorAlreadyExistsException constructor.
     */
    public function __construct()
    {
        parent::__construct("Автор с таким ФИО уже существует!");
    }
}