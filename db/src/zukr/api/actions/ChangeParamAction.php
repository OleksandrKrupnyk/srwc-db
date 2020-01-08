<?php


namespace zukr\api\actions;

use zukr\base\Base;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\exceptions\NullReturnedException;
use zukr\base\Params;
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
    use SNRCRFTrait;

    /**
     * @var string Назва параметру
     */
    private $param;
    /** @var string Значення параметру */
    private $value;


    /**
     * @inheritDoc
     */
    public function execute()
    {
        $message = 'Значення не змінено';
        $type = 'error';
        $snrcrf = Base::getSNRCRF();
        if (\in_array($this->param, Params::PARAMS) && $this->isValidSNRCRF($snrcrf)) {
            /** @var Setting $setting */
            $setting = (new SettingRepository())->findById($this->param);
            if ($setting === null) {
                throw new NullReturnedException('$setting Return value is null');
            }
            $setting->value = $this->value;
            $save = $setting->save();
            if ($save) {
                $message = 'Значення змінено';
                $type = 'success';
            }
        }
        return \json_encode(\compact('message', 'type'));
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function init(array $params = [])
    {
        $this->setSnrcrf();

        if (empty($this->param = \filter_input(INPUT_POST, 'param', FILTER_SANITIZE_STRING))) {
            throw new InvalidArgumentException('param Must be set');

        };

        if (
            ($this->value = \filter_input(INPUT_POST, 'value', FILTER_VALIDATE_INT)) === null

        ) {
            throw new InvalidArgumentException('value Must be set');
        }
    }
}