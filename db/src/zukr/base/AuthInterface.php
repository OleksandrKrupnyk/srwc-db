<?php


namespace zukr\base;

/**
 * Interface AuthInterface
 *
 * @package zukr\base
 */
interface AuthInterface
{
    /**
     * @return bool
     */
    public function isGuest(): bool;

    /**
     * @return bool
     */
    public function isAdmin(): bool;

    /*
     *
     */
    public function getLogin(): string;
}