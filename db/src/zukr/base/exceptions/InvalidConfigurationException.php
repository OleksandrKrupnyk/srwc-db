<?php


namespace zukr\base\exceptions;

use zukr\base\ExceptionInterface;

/**
 * Class InvalidConfigurationException
 *
 * @package      zukr\base\exceptions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class InvalidConfigurationException extends \Exception implements ExceptionInterface
{

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'InvalidConfigurationException';
    }

}