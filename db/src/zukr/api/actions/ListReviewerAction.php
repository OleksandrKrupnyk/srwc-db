<?php


namespace zukr\api\actions;

use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\html\Html;
use zukr\review\ReviewHelper;

/**
 * Class ListReviewerAction
 *
 * Список рецензентів в розділі створення рецензії
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ListReviewerAction implements ApiActionsInterface
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
     * @inheritDoc
     */
    public function execute()
    {
        $rh = ReviewHelper::getInstance();
        return Html::select('Review[review1]', null,
            $rh->getListReviewers($this->id_w, $this->id_u),
            [
                'required' => true, 'class' => 'w-100', 'id' => 'review-review1'
            ]);
    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {

        if (empty($this->id_w = \filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_w Must be set');
        }
        if (empty($this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id_u Must be set');
        }
    }
}