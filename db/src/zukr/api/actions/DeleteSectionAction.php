<?php


namespace zukr\api\actions;

use zukr\base\exceptions\InvalidArgumentException;
use zukr\log\Log;
use zukr\section\Section;
use zukr\work\WorkRepository;

/**
 * Class DeleteSectionAction
 *
 * Видалення секції
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class DeleteSectionAction implements ApiActionsInterface
{
    use ApiMessageTrait;
    /**
     * @var int ІД секції
     */
    protected $id;

    /**
     * @inheritDoc
     * @return string
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $wh = (new WorkRepository())->useSectionId($this->id);
            if (!empty($wh)) {
                throw new \Exception("Секція використовується");
            }
            $section = new Section();
            $sectionQuery = $section->getDb();
            $sectionQuery->startTransaction();
            $sectionQuery->where('id', $this->id);
            $delete = $section->delete($sectionQuery);
            $log = Log::getInstance();
            $log->logAction('delete_section', $section::getTableName(), $this->id);
            ($delete) ? $sectionQuery->commit() : $sectionQuery->rollback();
            $this->changeMessage('Запис виделено');

        } catch (\Exception $e) {
            if (isset($sectionQuery) && $sectionQuery !== null) {
                $sectionQuery->rollback();
            }
            $this->changeMessage($e->getMessage(), 'error');
        }
        return $this->getMessage();
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function init(array $params = [])
    {
        if (empty($this->id = \filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT))) {
            throw new InvalidArgumentException('id Must be set');

        };
    }
}