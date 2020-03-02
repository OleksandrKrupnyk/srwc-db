<?php

namespace zukr\base\exceptions;

use zukr\base\ExceptionInterface;

/**
 * Class NoLogFileException
 *
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class NoLogFileException extends \Exception implements ExceptionInterface
{

    /**
     * @inheritDoc
     */
    public
    function getName(): string
    {
        return 'NoLogFileException';
    }
}