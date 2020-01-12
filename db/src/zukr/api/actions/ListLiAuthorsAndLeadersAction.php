<?php


namespace zukr\api\actions;

use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\leader\LeaderRepository;
use zukr\workauthor\WorkAuthorRepository;

/**
 * Class ListLiAuthorsAndLeadersAction
 *
 * Список авторів та пердставників ВНЗ під час реєстрації
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ListLiAuthorsAndLeadersAction implements ApiActionsInterface
{

    /**
     * @var int ІД запису унівресритету
     */
    private $id_u;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $wah = new WorkAuthorRepository();
        $authors = $wah->getInvitationAuthorsByUniverId($this->id_u);
        $list = [];
        foreach ($authors as $a) {
            $list[] = Html::tag('li', PersonHelper::getFullName($a) . ', (№' . $a['id'] . ')',
                [
                    'data-key' => $a['id'],
                    'data-person' => 'author',
                    'class' => (int)$a['arrival'] === 1 ? 'option-arrival pointer' : 'pointer',
                    'title' => 'Подвійний клік для зміни/+Ctrl'
                ]
            );
        }
        $authorsList = Html::tag('ol', \implode('', $list));

        $wlh = new LeaderRepository();
        $leaders = $wlh->getAllByUniverId($this->id_u);

        $list = [];
        foreach ($leaders as $l) {
            $list[] = Html::tag('li', PersonHelper::getFullName($l),
                [
                    'data-key' => $l['id'],
                    'data-person' => 'leader',
                    'class' => (int)$l['arrival'] === 1 ? 'option-arrival pointer ' : 'pointer',
                    'title' => 'Подвійний клік для зміни/+Ctrl'
                ]);
        }
        $leadersList = Html::tag('ol', \implode('', $list));

        return $authorsList . '!' . $leadersList;
    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {
        if (empty($this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_u Must be set');
        }
    }
}