<?php


namespace zukr\api\actions;

use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\exceptions\NullReturnedException;
use zukr\base\Params;
use zukr\log\Log;
use zukr\setting\Setting;
use zukr\setting\SettingRepository;

/**
 * Class ChangeParamAction
 *
 * Зміна налаштувань системи
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ChangeParamAction implements ApiActionsInterface
{
    use SNRCRFTrait, ApiMessageTrait;

    /**
     * @var string Назва параметру
     */
    private $param;
    /**
     * @var string Значення параметру
     */
    private $value;
    /**
     * @var string
     */
    private $typeParam;


    /**
     * @inheritDoc
     */
    public function execute()
    {

        $snrcrf = $this->getSnrcrfFromSeesion();
        if (\in_array($this->param, Params::PARAMS) && $this->isValidSNRCRF($snrcrf)) {
            /** @var Setting $setting */
            $setting = (new SettingRepository())->findById($this->param);
            if ($setting === null) {
                throw new NullReturnedException('$setting Return value is null');
            }
            $setting->value = $this->value;
            $save = $setting->save();
            if ($save) {
                Log::getInstance()->logAction(
                    'change-param',
                    $setting::getTableName(),
                    $setting->parametr . '=' . $setting->value);
                $this->changeMessage();
            }
        }
        return $this->getMessage();
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function init(array $params = [])
    {
        $this->setSnrcrf();

        if (empty($this->typeParam = \filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING))) {
            throw new InvalidArgumentException('typeParam Must be set');
        }

        if (!\in_array($this->typeParam, Setting::TYPES, true)) {
            throw new InvalidArgumentException('typeParam Wrong value');

        }

        if (empty($this->param = \filter_input(INPUT_POST, 'param', FILTER_SANITIZE_STRING))) {
            throw new InvalidArgumentException('param Must be set');
        }
        if ($this->typeParam === Setting::BOOL) {

            if (($this->value = \filter_input(INPUT_POST, 'value', FILTER_VALIDATE_INT)) === null) {
                throw new InvalidArgumentException('value (bool/int) Must be set');
            }
        } else {
            if (empty($this->value = \filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING))) {
                throw new InvalidArgumentException('value (string) Must be set');
            }
        }
    }
}