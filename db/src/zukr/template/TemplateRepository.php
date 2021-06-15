<?php


namespace zukr\template;


use zukr\base\AbstractRepository;
use zukr\base\Base;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\Record;

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
            $model->load($item, false);
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

    /**
     * @param string $blockName Назва блоку
     * @return ?Template
     */
    public function getLastVersionPublishedBlock(string $blockName)
    {
        try {
            $table = $this->model::getTableName();
            $model = clone $this->model;
            $data = Template::find()
                ->where('name', $blockName)
                ->orderBy('version')
                ->getOne($table);
            if (empty($data)) {
                throw new InvalidArgumentException('Empty data for model ' . $this->__className);
            }
            $model->load($data, false);
            if ($model->published === Record::KEY_OFF) {
                $model->content = '';
                Base::$log->warning('Template ' . $model->name . ' (v.' . $model->version . ') is unpublished');
            }
            return $model;
        } catch (\Exception $e) {
            Base::$log->error($e->getMessage());
            return null;
        }
    }
}