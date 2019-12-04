<?php


namespace zukr\base;

/**
 * Interface ExceptionInterface
 *
 *
 * @package zukr\base
 */
interface ExceptionInterface
{
    /**
     * Повретає назву виключення
     *
     * @return string Назва виключення
     */
    public function getName(): string;
}