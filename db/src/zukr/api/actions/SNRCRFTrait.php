<?php


namespace zukr\api\actions;

use zukr\base\Base;
use zukr\base\exceptions\InvalidArgumentException;

/**
 * Trait SNRCRFTrait
 *
 * @package zukr\api\actions
 */
trait SNRCRFTrait
{
    /**
     * @var string
     */
    private $_snrcrf;

    /**
     * Присвоює значення  токену полю класу отримуючи значення з масиву $_POST['_SNRCRF']
     *
     * @throws InvalidArgumentException
     */
    protected function setSnrcrf(): void
    {
        if (empty($this->_snrcrf = \filter_input(INPUT_POST, '_SNRCRF', FILTER_SANITIZE_STRING))) {
            throw new InvalidArgumentException('_SNRCRF Must be set');

        }
    }

    /**
     * Перевіряє правильність токену
     *
     * @param string $value Значення для перевірки
     * @return bool Результат перевірки
     */
    protected function isValidSNRCRF(string $value): bool
    {
        return $this->_snrcrf === $value;
    }

    /**
     * Отримуэ значення токену з сесії
     *
     * @return string Значення токену
     */
    protected function getSnrcrfFromSeesion()
    {
        return Base::getSNRCRF();
    }
}