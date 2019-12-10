<?php


namespace zukr\section;


class SectionHelper
{

    /** @var SectionHelper */
    private static $obj;

    /** @var array */
    private $sections;

    /** @var  SectionRepository */
    private $sectionRepository;

    /**
     * WorkHelper constructor.
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
            $sections = $this->sectionRepository->getAllSectionsAsArray();
            $this->sections = $sections;
        }
        return $sections;
    }

    /**
     * @return array
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