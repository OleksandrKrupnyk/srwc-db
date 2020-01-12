<?php


namespace zukr\api\actions;

use zukr\base\helpers\ArrayHelper;
use zukr\base\html\Html;
use zukr\work\WorkHelper;

/**
 * Class SelectWorks
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
     */
    public function init(array $params = [])
    {
        $this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT);
        $this->id_w = \filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
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