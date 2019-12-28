<?php


namespace zukr\section;

/**
 * Class SectionHelper
 *
 * @package      zukr\section
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class SectionHelper
{

    /** @var SectionHelper */
    private static $obj;

    /** @var array */
    private $sections;

    /** @var  SectionRepository */
    private $sectionRepository;

    /**
     * SectionHelper constructor.
     */
    private function __construct()
    {
        $this->sectionRepository = new SectionRepository();
    }

    /**
     * @return SectionHelper
     */
    public static function getInstance(): SectionHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @return array|\MysqliDb
     */
    public function getAllSections()
    {
        if ($this->sections === null) {
            $this->sections = $this->sectionRepository->getAllSectionsAsArray();
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

}