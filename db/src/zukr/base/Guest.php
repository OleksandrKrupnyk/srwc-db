<?php


namespace zukr\base;

/**
 * Class Guest
 *
 * @package      zukr\base
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class Guest implements AuthInterface
{
    /**
     * @return bool
     */
    public function isGuest(): bool
    {
        return true;

    }

    /**
     * @inheritDoc
     */
    public function isAdmin(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return 'guest';
    }

    /**
     * @inheritDoc
     */
    public function getProfile(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function isReview(): bool
    {
        return false;
    }
}