<?php


namespace zukr\api\actions;

/**
 * Trait ApiMessageTrait
 *
 * @package zukr\api\actions
 */
trait ApiMessageTrait
{
    /**
     * @var string
     */
    private $message = 'Значення не змінено';
    /**
     * @var string
     */
    private $type = 'error';

    /**
     * @return array
     */
    private function changeMessage($message = null, $type = null): void
    {
        $this->message = $message ?? 'Значення змінено';
        $this->type = $type ?? 'success';
    }

    /**
     * @return string
     */
    private function getMessage(): string
    {
        return \json_encode(['message' => $this->message, 'type' => $this->type]);
    }
}