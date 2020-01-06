<?php


namespace zukr\section;


use zukr\base\AbstractRepository;
use zukr\base\Base;

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
    /**
     * @var string
     */
    protected $__className = Section::class;

    /**
     * @return array|\MysqliDb
     */
    public function getAllSectionsAsArray(): array
    {
        try {
            return $this->model::find()
                ->map('id')
                ->get($this->model::getTableName());
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }

    /**
     * @return array
     */
    public function getSectionAndCountRooms(): array
    {
        try {
            return $this->model::find()
                ->rawQuery("
SELECT  sections.*,count
FROM (SELECT id_sec, COUNT(id_sec) as count
      FROM works
      WHERE invitation = '1'
        AND arrival = '1'
      GROUP BY id_sec) as w
         LEFT JOIN sections ON w.id_sec = sections.id;");
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}