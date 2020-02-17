<?php


namespace zukr\api\actions;

use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\helpers\ArrayHelper;
use zukr\base\html\Html;
use zukr\work\WorkHelper;

/**
 * Class SelectWorks
 *
 * Список робіт універсиету у вигляді випадаючого списку
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class SelectWorksAction implements ApiActionsInterface
{

    /**
     * @var int
     */
    public $id_u;
    /**
     * @var int
     */
    public $id_w;

    /**
     * @param array $params
     * @throws InvalidArgumentException
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
     * @return string
     */
    public function execute()
    {
        $wh = WorkHelper::getInstance();
        $works = $wh->getWorksByUniverId($this->id_u);
        $works = ArrayHelper::map($works, 'id', 'title');
        return Html::select('id_w', $this->id_w, $works,
            [
                'id' => 'selwork',
                'required' => true, 'prompt' => 'Оберіть', 'class' => 'w-100', 'size' => 10
            ]);
    }

}