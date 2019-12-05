<?php


namespace zukr\work;


use zukr\base\Base;
use zukr\base\helpers\PersonHelper;
use zukr\section\SectionRepository;

/**
 * Class WorkHelper
 *
 * @package      zukr\work
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkHelper
{
    /** @var WorkHelper */
    private static $obj;

    /** @var array */
    private $works;

    /** @var array */
    private $sections;

    /**
     * WorkHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return WorkHelper
     */
    public static function getInstance(): WorkHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @return array
     */
    private function getWorks(): array
    {
        if ($this->works === null) {
            $works = Base::$app->cacheGetOrSet(
                'works_list',
                (new WorkRepository())->getAllWorksAsArray(),
                30);
            $this->works = $works;
        }
        return $this->works;
    }


    /**
     * @return array|mixed
     */
    public function getAllSections()
    {
        if ($this->sections === null) {
            $sections = Base::$app->cacheGetOrSet(
                'section_list',
                (new SectionRepository())->getAllSectionsAsArray(),
                60);
            $this->sections = $sections;
        }
        return $this->sections;
    }


    /**
     * @return array
     */
    public function getAllWorks(): array
    {
        return $this->getWorks();
    }

    /**
     * @return array
     */
    public function getInvitationWorks(): array
    {
        return \array_filter($this->getWorks(), static function ($value) {
            return $value['invitation'] === 1;
        });
    }

    /**
     * @return array
     */
    public function getTesisWorks(): array
    {
        return \array_filter($this->getWorks(), static function ($value) {
            return $value['tesis'] === 1;
        });
    }

    /**
     * @return array
     */
    public function getArrivalWorks(): array
    {
        return \array_filter($this->getWorks(), static function ($value) {
            return $value['arrival'] === 1;
        });
    }

    /**
     * @return array
     */
    public function getIntroductionWorks(): array
    {
        return \array_filter($this->getWorks(), static function ($value) {
            return !empty($value['introduction']);
        });
    }

    /**
     * @return array
     */
    public function getPublicWorks(): array
    {
        return \array_filter($this->getWorks(), static function ($value) {
            return !empty($value['public']);
        });
    }

    /**
     * @return array
     */
    public function getCommentsWorks(): array
    {
        return \array_filter($this->getWorks(), static function ($value) {
            return !empty($value['comments']);
        });
    }

    /**
     * @return array
     */
    public function getOrderByBallsWorks(): array
    {
        $array = $this->getWorks();
        \usort($array, static function ($a, $b) {
            return $b['balls'] <=> $a['balls'];
        });
        return $array;
    }

    /**
     * @param array $autors
     * @param bool  $href
     * @param bool  $showPlace
     * @param bool  $showId
     * @return string
     */
    public static function authorList(array $autors, $href = false, $showPlace = false, $showId = false): string
    {
        $list = [];
        $FROM = $_SESSION['from'] ?? '';
        foreach ($autors as $autor) {
            $item = '';
            $item .= ($href)
                ? "<li title=\"Останні зміни: " . htmlspecialchars($autor['date']) . '" >'
                : "<li title=" . PersonHelper::getFullName($autor) . '">';
            $item .= ($href)
                ? '<a href=action.php?action=autor_edit&id_a='
                . $autor['id'] . '&FROM='
                . $FROM . " title=\"Ред.:" . PersonHelper::getFullName($autor) . '">'
                : '';
            $item .= PersonHelper::getShortName($autor);
            $item .= ($showId) ? '&lt;' . $autor['id'] . '&gt;' : '';

            if ($showPlace && ($autor['place'] !== 'D')) {
                $item .= "(&nbsp;{$autor['place']}&nbsp;)";
            }

            if ($autor['arrival'] == 1) {
                $item .= '<span title="Прибув на конференцію">&nbsp;[&radic;]&nbsp;</span>';
            }
            $item .= ($href) ? '</a>' : '';
            if ($autor['arrival'] !== 1) {
                $item .= ($href) ? ' <a href=action.php?action=work_unlink&id_a=' . $autor['id'] . '&id_w=' . $autor['id_w'] . ' title="Відокремити від роботи"><img src="../images/unlink.png" alt="unlink"></a>' : '';
            }

            $item .= '</li>';
            $list [] = $item;
        }
        return '<ol>' . implode('', $list) . '</ol>';
    }


}