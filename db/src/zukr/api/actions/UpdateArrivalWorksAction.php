<?php


namespace zukr\api\actions;

use zukr\base\Base;

/**
 * Class UpdateArrivalWorksAction
 *
 * Оновлення відміток про участь роботи в конференції
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UpdateArrivalWorksAction implements ApiActionsInterface
{
    /**
     * @var \MysqliDb
     */
    private $db;

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        if ($this->db !== null) {

            $this->db->startTransaction();
            $this->db->rawQuery('
        UPDATE `works` as dest , 
                 (SELECT `works`.`id`,`autors`.`arrival` as arr 
                  FROM `works` 
                  JOIN `wa` on works.id=wa.id_w 
                  JOIN `autors` on wa.id_a=autors.id 
                  WHERE autors.arrival =\'1\' AND works.invitation=\'1\') as src 
                  SET dest.arrival=src.arr 
                  WHERE dest.id=src.id
        ');
            $this->db->commit();
        }
        return $this->db->count;
    }

    /**
     * @inheritDoc
     */
    public function init(array $params = [])
    {
        $this->db = Base::$app->db;
    }
}