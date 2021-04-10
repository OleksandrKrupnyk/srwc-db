<?php


namespace zukr\template;


use zukr\base\AbstractRepository;
use zukr\base\Base;

/**
 * Class TemplateRepository
 *
 * @package zukr\template
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class TemplateRepository extends AbstractRepository
{
    /**
     * @var Template
     */
    protected $model;
    /**
     * @var string
     */
    protected $__className = Template::class;

    /**
     *
     * @return array|Template[]
     */
    public function findTemplateFromDB()
    {
        $data = $this->getTemplatesFormDB();
        $list = [];
        foreach ($data as $item) {
            $model = clone $this->model;
            $model->load($item,false);
            $list[] = $model;
        }
        return $list;
    }


    /**
     * Повертає дані по усім блокам
     *
     * @return array|\MysqliDb
     */
    public function getTemplatesFormDB()
    {
        $table = $this->model::getTableName();
        try {
            return Template::find()
                ->map('id')
                ->get($table);
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return [];
        }
    }
}