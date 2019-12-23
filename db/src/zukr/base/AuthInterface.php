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

    /**
     *
     */
    public function getProfile(): array;

    /**
     * Чи є користувач рецензентом
     *
     * @return bool Результат перевірки
     */
    public function isReview(): bool;

}