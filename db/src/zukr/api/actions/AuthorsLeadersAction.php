<?php


namespace zukr\api\actions;

use zukr\author\AuthorHelper;
use zukr\base\Base;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\helpers\ArrayHelper;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\base\Record;
use zukr\leader\LeaderHelper;
use zukr\workauthor\WorkAuthorHelper;
use zukr\workleader\WorkLeaderHelper;

/**
 * Class AuthorsLeaders
 *
 * Зв'язування роботи автора та керівника
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
     * @var WorkLeaderHelper
     */
    private $workLeaderHelper;
    /**
     * @var WorkAuthorHelper
     */
    private $workAuthorHelper;

    /**
     * @var array
     */
    private $listLeadersAreLinkToWork;
    /**
     * @var array
     */
    private $listAutorsAreLinkToWork;

    public function __construct()
    {
        $this->workLeaderHelper = WorkLeaderHelper::getInstance();
        $this->workAuthorHelper = WorkAuthorHelper::getInstance();

    }

    /**
     * @param array $params
     */
    public function init(array $params = [])
    {
        if (empty($this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_u Must be set');
        }
        if (empty($this->id_w = \filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_w Must be set');
        }
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $response = [];
        // Узнать количесво руководителей в работе
        $leadersIds = $this->getLeadersIds($this->id_w);
        $countLeaders = \count($leadersIds);

        $authorsIds = $this->getAuthorsIds($this->id_w);
        $countAutors = \count($authorsIds);

        $response ['linkedLeaders'] = $countLeaders === 0
            ? $response ['linkedLeaders'] = '<span class="info">Відсутні</span>'
            : $response ['linkedLeaders'] = $this->getListAreLinkedLeaders();
        $response ['listLeaders'] = $countLeaders < Base::$param->N_LEADERS
            ? $response ['listLeaders'] = $this->getLeaders($leadersIds)
                . Html::a('Створити', 'action.php?action=leader_add&id_u=' . $this->id_u . '&id_w=' . $this->id_w, [
                    'class' => 'btn',
                    'title' => "Внесення в базу даних керівника"
                ])
            : $response ['listLeaders'] = '<span class="info">Досить</span>';

        $response ['linkedAuthors'] = $countAutors === 0
            ? $response ['linkedAuthors'] = '<span class="info">Відсутні</span>'
            : $response ['linkedAuthors'] = $this->getListAreLinkedAuthors();
        $list = '';
        for ($counter = $countAutors; $counter < Base::$param->N_AUTORS; $counter++) {
            $list .= $this->getAuthors($authorsIds) . '<br>';
        }
        if (!empty($list)) {
            $list .= Html::a('Створити', 'action.php?action=author_add&id_u=' . $this->id_u, [
                'class' => 'btn',
                'title' => "Внесення в базу даних автора"
            ]);
            $response ['listAuthors'] = $list;
        } else {
            $response ['listAuthors'] = '<span class="info">Досить</span>';
        }
        return \json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Список ІД записів керівників звязаних з роботою
     *
     * @param int $id_w ІД запису роботи
     * @return array Список ІД записів крівників
     */
    protected function getLeadersIds(int $id_w): array
    {
        $this->listLeadersAreLinkToWork =
            $this->workLeaderHelper
                ->getWorkLeaderRepository()
                ->getAllLeadersOfWorkByWorkId($id_w);
        if (!empty($this->listLeadersAreLinkToWork)) {
            return \array_column($this->listLeadersAreLinkToWork, 'id');
        }
        return [];
    }

    /**
     * Список ІД записів авторів звязаних з роботою
     *
     * @param int $id_w ІД запису роботи
     * @return array Список ІД записів крівників
     */
    protected function getAuthorsIds(int $id_w): array
    {
        $this->listAutorsAreLinkToWork =
            $this->workAuthorHelper->getWorkAuthorRepository()
                ->getAllAuthorsOfWorkByWorkId($id_w);
        if (!empty($this->listAutorsAreLinkToWork)) {
            return \array_column($this->listAutorsAreLinkToWork, 'id');
        }
        return [];
    }

    /**
     * @return string
     */
    private function getAuthors(array $listIds)
    {

        $ah = AuthorHelper::getInstance();
        $autorsByUniver =
            $ah->getAllAuthorsByUniverId($this->id_u);
        $list = [];
        foreach ($autorsByUniver as $author) {
            if (\in_array($author['id'], $listIds)) {
                continue;
            }
            $list[$author['id']] = PersonHelper::getFullName($author);
        }
        $list[-1] = '-Відсутній у списку-';
        ArrayHelper::asort($list);
        return Html::select('authors[]', -1, $list,
            [
                'id' => 'select-link-author',
                'data-placeholder' => 'Оберіть'
            ]);

    }

    /**
     * @param array $listIds
     * @return string
     */
    private function getLeaders(array $listIds)
    {
        $lh = LeaderHelper::getInstance();
        $leadersByUniver =
            $lh->getAllLeadersByUniverId($this->id_u);
        $list = [];
        foreach ($leadersByUniver as $leaders) {
            if (\in_array($leaders['id'], $listIds)) {
                continue;
            }
            $list[$leaders['id']] = PersonHelper::getFullName($leaders);
        }
        $list[-1] = '-Відсутній у списку-';
        ArrayHelper::asort($list);
        return Html::select('leaders[]', -1, $list,
            [
                'id' => 'select-link-leader',
                'data-placeholder' => 'Оберіть'
            ]);
    }

    /**
     * @return string
     */
    private function getListAreLinkedLeaders()
    {
        $list = [];
        foreach ($this->listLeadersAreLinkToWork as $leader) {
            $content = '';
            if ((int)$leader['arrival'] === Record::KEY_ON) {
                $content .= '<span title="Прибув на конференцію">&nbsp;[&radic;]&nbsp;</span>';
            } else {
                $img = '<img src="../images/unlink.png" alt="unlink">';
                $content .= Html::a($img, "action.php?action=work_unlink&id_l=" . $leader['id'] . "&id_w=" . $this->id_w . "", [
                    'title' => 'Відокремити від роботи'
                ]);
            }
            $href = 'action.php?action=leader_edit&id_l=' . $leader['id'];
            $item = Html::a(PersonHelper::getShortName($leader), $href, [
                    'title' => 'Ред.:' . PersonHelper::getShortName($leader)
                ]) . $content;

            $list[] = '<li>' . $item . '</li>';
        }
        return '<ol>' . \implode('', $list) . '</ol>';
    }

    /**
     * @return string
     */
    private function getListAreLinkedAuthors()
    {
        $list = [];
        foreach ($this->listAutorsAreLinkToWork as $author) {
            $content = '';
            if ((int)$author['arrival'] === Record::KEY_ON) {
                $content .= '<span title="Прибув на конференцію">&nbsp;[&radic;]&nbsp;</span>';
            } else {
                $img = '<img src="../images/unlink.png" alt="unlink">';
                $content .= Html::a($img, "action.php?action=work_unlink&id_a=" . $author['id'] . "&id_w=" . $this->id_w . "", [
                    'title' => 'Відокремити від роботи'
                ]);
            }
            $href = 'action.php?action=author_edit&id_a=' . $author['id'];
            $item = Html::a(PersonHelper::getShortName($author), $href, [
                    'title' => 'Ред.:' . PersonHelper::getShortName($author)
                ]) . $content;

            $list[] = '<li>' . $item . '</li>';
        }
        return '<ol>' . \implode('', $list) . '</ol>';
    }


}