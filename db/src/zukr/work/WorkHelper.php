<?php


namespace zukr\work;


use zukr\base\Base;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\base\RecordHelper;

/**
 * Class WorkHelper
 *
 * @package      zukr\work
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkHelper extends RecordHelper
{
    /**
     * @var WorkHelper
     */
    private static $obj;

    /**
     * @var array
     */
    private $works;

    /**
     * @return WorkHelper
     */
    public static function getInstance(): WorkHelper
    {
        if (static::$obj === null) {
            static::$obj = new static();
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
                static function () {
                    return (new WorkRepository())->getAllWorksAsArray();
                },
                3600);
            $this->works = $works;
        }
        return $this->works;
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
     * @param bool  $showPlace
     * @param bool  $showId
     * @return string
     */
    public static function authorList(array $autors, bool $showPlace = false, $showId = false): string
    {
        $list = [];
        foreach ($autors as $autor) {
            $item = '';
            $item .= '<a href=action.php?action=author_edit&id_a='
                . $autor['id'] . " title=\"Ред.:" . PersonHelper::getFullName($autor) . '">';
            $item .= PersonHelper::getShortName($autor);
            $item .= $showId ? '&lt;' . $autor['id'] . '&gt;' : '';

            if ($showPlace && ($autor['place'] !== 'D')) {
                $item .= '(&nbsp;' . $autor['place'] . '&nbsp;)';
            }

            if ($autor['arrival'] == 1) {
                $item .= '<span title="Прибув на конференцію">&nbsp;[&radic;]&nbsp;</span>';
            }
            $item .= '</a>';
            if ($autor['arrival'] !== 1) {
                $item .= ' <a href=action.php?action=work_unlink&id_a=' . $autor['id'] . '&id_w=' . $autor['id_w'] . ' title="Відокремити від роботи"><img src="../images/unlink.png" alt="unlink"></a>';
            }

            $list [] = '<li title="Останні зміни: ' . \htmlspecialchars($autor['date']) . '" >' . $item . '</li>';
        }
        return '<ol>' . \implode('', $list) . '</ol>';
    }

    /**
     * @param array $leaders
     * @param bool  $showId
     * @return string
     */
    public static function leaderList(array $leaders, bool $showId = false): string
    {
        $list = [];
        foreach ($leaders as $leader) {
            $item = '';
            $item .= '<a href=action.php?action=leader_edit&id_l=' . $leader['id'] . ' title="Ред.:' . PersonHelper::getFullName($leader) . '">';
            $item .= PersonHelper::getShortName($leader);
            $item .= $showId ? '<' . $leader['id'] . '>' : '';

            if ($leader['arrival'] === '1') {
                $item .= '<span title="Прибув на конференцію">&nbsp;[&radic;]&nbsp;</span>';
            }
            $item .= '</a>';

            if ($leader['arrival'] !== '1') {
                $item .= Html::tag('a', '<img src="../images/unlink.png" alt="unlink">',
                    [
                        'href' => 'action.php?action=work_unlink&id_l=' . $leader['id'] . "&id_w=" . $leader['id_w'],
                        'title' => 'Відокремити від роботи',
                        'class' => 'unlink_person'
                    ]);
            }
            $list [] = '<li title="Останні зміни: ' . \htmlspecialchars($leader['date']) . '" >' . $item . '</li>' . PHP_EOL;
        }
        return '<ol>' . \implode(PHP_EOL, $list) . '</ol>';
    }

    /**
     * @return array
     */
    public function getTakePartUniversIds(): array
    {
        return
            \array_values(
                \array_unique(
                    \array_map(static function ($value) {
                        return $value['id_u'];
                    }, $this->getWorks()
                    )
                )
            );
    }

    /**
     * @param int $univerId
     * @return array
     */
    public function getWorksByUniverId(int $univerId): array
    {
        $array = $this->getWorks();
        $array = \array_filter($array, static function ($work) use ($univerId) {
            return $work['id_u'] === $univerId;
        });

        return $array;
    }
}