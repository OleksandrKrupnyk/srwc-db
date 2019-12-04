<?php


namespace zukr\section;


use zukr\base\AbstractRepository;

/**
 * Class SectionRepository
 *
 * @method getSection()
 * @package      zukr\section
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class SectionRepository extends AbstractRepository
{

    protected $__className = Section::class;


    public function getAllSectionsAsArray()
    {
        return $this->model::find()
            ->map('id')
            ->get($this->model::getTableName(), null, '*');
    }
}