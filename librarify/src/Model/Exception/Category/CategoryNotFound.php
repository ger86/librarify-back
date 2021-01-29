<?php

namespace App\Model\Exception\Category;

use Exception;

class CategoryNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Category not found');
    }
}
