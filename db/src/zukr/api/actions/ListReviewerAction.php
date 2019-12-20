<?php


namespace zukr\api\actions;

use zukr\base\html\Html;
use zukr\review\ReviewHelper;

/**
 * Class ListReviewerAction
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ListReviewerAction implements ApiActionsInterface
{

    public $id_u;
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
        // TODO: Implement execute() method.
    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {

        $this->id_w = filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
        $this->id_u = filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT);
    }
}