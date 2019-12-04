<?php


namespace zukr\base;

/**
 * Interface BaseRepositoryInterface
 *
 * @package zukr\base
 */
interface BaseRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param $id
     * @return array
     */
    public function getById($id);

}