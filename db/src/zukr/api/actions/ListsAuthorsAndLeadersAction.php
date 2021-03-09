<?php


namespace zukr\api\actions;


use zukr\author\AuthorRepository;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\helpers\PersonHelper;
use zukr\base\html\Html;
use zukr\leader\LeaderRepository;

/**
 * Class ListsAuthorsAndLeadersAction
 *
 * Додавання усіх відомостей про роботу списки керівників та авторів
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ListsAuthorsAndLeadersAction implements ApiActionsInterface
{
    /**
     * @var int ІД запису університету
     */
    private $id_u;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if (!$this->id_u) {
            return '';
        }
        $listAuthors = (new AuthorRepository())->getAllByUniverId($this->id_u);
        $list['888'] = 'Відсутній у списку';
        foreach ($listAuthors as $author) {
            $list[$author['id']] = PersonHelper::getFullName($author);
        }
        $response [] = Html::select('id_a', 888, $list, ['class' => 'w-50', 'size' => 10, 'id' => 'select-authors']);

        $listLeaders = (new LeaderRepository())->getAllByUniverId($this->id_u);
        unset($list);
        $list['888'] = 'Відсутній у списку';
        foreach ($listLeaders as $leader) {
            $list[$leader['id']] = PersonHelper::getFullName($leader);
        }
        $response [] = Html::select('id_l', 888, $list, ['class' => 'w-50', 'size' => 10, 'id' => 'select-leaders']);

        return \implode('!', $response);

    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function init(array $params = [])
    {
        if (empty($this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_u Must be set');
        }
    }
}