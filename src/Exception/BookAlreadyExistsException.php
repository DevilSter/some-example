<?php
declare(strict_types=1);

namespace App\Exception;


use Exception;

final class BookAlreadyExistsException extends Exception
{
    /**
     * AuthorAlreadyExistsException constructor.
     */
    public function __construct()
    {
        parent::__construct("The book already exists!");
    }
}