<?php


namespace zukr\section;

use zukr\base\RecordHelper;

/**
 * Class SectionHelper
 *
 * @package      zukr\section
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class SectionHelper extends RecordHelper
{

    /** @var SectionHelper */
    private static $obj;

    /** @var array */
    private $sections;

    /** @var  SectionRepository */
    private $sectionRepository;

    /**
     * @return SectionHelper
     */
    public static function getInstance(): SectionHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
        }
        return static::$obj;

    }

    /**
     * @return array|\MysqliDb
     */
    public function getAllSections()
    {
        if ($this->sections === null) {
            $this->sections = $this->getSectionRepository()->getAllSectionsAsArray();
        }
        return $this->sections;
    }

    /**
     * Повертає список секцій індексований за ІД секції
     *
     * @return array Спсиок секцій
     */
    public function getDropdownList(): array
    {
        $list = [];
        foreach ($this->getAllSections() as $id => $section) {
            $list[$id] = $section['section'];
        }
        return $list;
    }

    /**
     * @return SectionRepository
     */
    public function getSectionRepository(): SectionRepository
    {
        if ($this->sectionRepository === null) {
            $this->sectionRepository = new SectionRepository();
        }
        return $this->sectionRepository;
    }
}