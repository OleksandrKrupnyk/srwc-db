<?php


namespace zukr\base\exceptions;

use zukr\base\ExceptionInterface;

/**
 * Class NullReturnedException
 *
 * @package      zukr\base\exceptions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class NullReturnedException extends \Exception implements ExceptionInterface
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'NullReturnedException';
    }

}