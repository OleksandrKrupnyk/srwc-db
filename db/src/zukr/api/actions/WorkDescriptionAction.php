<?php


namespace zukr\api\actions;


use zukr\work\WorkRepository;

/**
 * Class WorkDescriptionAction
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class WorkDescriptionAction implements ApiActionsInterface
{

    /** @var int */
    public $id_u;
    /** @var */
    public $id_w;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $work = (new WorkRepository())->getById($this->id_w);
        $strArray = [];
        if ($work['introduction'] <> '') {
            $strArray[] = "<strong>Впровадженння:</strong>{$work['introduction']}.";
        }
        if ($work['public'] <> '') {
            $strArray[] = "<strong>Результати опубліковано:</strong>{$work['public']}.";
        }
        if ($work['comments'] <> '') {
            $strArray[] = "<strong>Коментар/зауваження до матеріалів:</strong>{$work['comments']}.";
        }
        if (count($strArray) < 1) {
            $str = "<strong>Увага! Без публікації та впровадження. Зауваження з боку офрмлення документів відсутні.</strong>";
        } elseif (count($strArray) == 1) {
            $str = $strArray[0];
        } else {
            $str = implode("<br>", $strArray);
        }
        return $str;
    }

    /**
     * @param array $params
     */
    public function init(array $params = []):void
    {
        $this->id_u = \filter_input(INPUT_POST, 'id_u', FILTER_VALIDATE_INT);
        $this->id_w = \filter_input(INPUT_POST, 'id_w', FILTER_VALIDATE_INT);
    }
}