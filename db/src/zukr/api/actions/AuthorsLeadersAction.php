<?php


namespace zukr\api\actions;

use zukr\author\AuthorHelper;
use zukr\base\helpers\ArrayHelper;
use zukr\base\html\Html;
use zukr\leader\LeaderHelper;
use zukr\workauthor\WorkAuthorHelper;
use zukr\workleader\WorkLeaderHelper;

/**
 * Class AuthorsLeaders
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class AuthorsLeadersAction implements ApiActionsInterface
{

    /**
     * @var int
     */
    public $id_u;
    /**
     * @var
     */
    public $id_w;

    /**
     * @param array $params
     */
    public function init(array $params = [])
    {
        $this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT);
        $this->id_w = \filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $authors = $this->getAuthors();
        $leaders = $this->getLeaders();
        return \json_encode(\compact('authors', 'leaders'), JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return string
     */
    private function getAuthors()
    {

        $ah = AuthorHelper::getInstance();
        $autorsByUniver =
            $ah->getAllAuthorsByUniverId($this->id_u);
        $list = [];
        foreach ($autorsByUniver as $author) {
            $list[$author['id']] = $author['suname'] . ' ' . $author['name'] . ' ' . $author['lname'];
        }
        ArrayHelper::asort($list);

        $wah = WorkAuthorHelper::getInstance();
        $autors = $wah->getIdsAuthorsOfWorkByWorkId($this->id_w);
        return Html::select('authors', $autors, $list,
            [
                'id' => 'select-link-author',
                'required' => true,
                'data-placeholder' => 'Оберіть'
            ]);

    }


    private function getLeaders()
    {
        $lh = LeaderHelper::getInstance();
        $leadersByUniver =
            $lh->getAllLeadersByUniverId($this->id_u);
        $list = [];
        foreach ($leadersByUniver as $author) {
            $list[$author['id']] = $author['suname'] . ' ' . $author['name'] . ' ' . $author['lname'];
        }
        ArrayHelper::asort($list);

        $wlh = WorkLeaderHelper::getInstance();
        $leaders = $wlh->getIdsLeadersOfWorkByWorkId($this->id_w);

        return Html::select('leaders', $leaders, $list,
            [
                'id' => 'select-link-leader',
                'required' => true,
                'data-placeholder' => 'Оберіть'
            ]);
    }


}