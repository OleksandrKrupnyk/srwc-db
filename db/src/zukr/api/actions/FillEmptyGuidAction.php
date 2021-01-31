<?php


namespace zukr\api\actions;

use League\MimeTypeDetection\ExtensionMimeTypeDetector;
use Ramsey\Uuid\Uuid;
use zukr\base\exceptions\InvalidArgumentException;
use zukr\base\exceptions\NullReturnedException;
use zukr\base\Params;
use zukr\file\File;
use zukr\log\Log;
use zukr\setting\Setting;
use zukr\setting\SettingRepository;

/**
 * Class FillEmptyGuidAction
 *
 * Зміна налаштувань системи (заповнення полів пустих полів)
 *
 * @package      zukr\api\actions
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class FillEmptyGuidAction implements ApiActionsInterface
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
     * @var string Назва дії
     */
    private $action;

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
            $setting->action = $this->action;
            $save = $setting->save();
            $sheep = new File();
            $allFiles = $sheep->getDb()->where('guid', '')->get($sheep::getTableName());

            foreach ($allFiles as $fileData) {
                $file = clone $sheep;
                $file->load($fileData, false);
                $file->guid = Uuid::uuid4()->toString();
                $file->save();
            }
            $detector = new ExtensionMimeTypeDetector();
            $allFiles = $sheep->getDb()->where('mime_type', '')->get($sheep::getTableName());
            foreach ($allFiles as $fileData) {
                $file = clone $sheep;
                $file->load($fileData, false);
                $file->mime_type = $detector->detectMimeTypeFromPath($file->file);
                $file->save();
            }


            if ($save) {
                Log::getInstance()->logAction(
                    $this->action,
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

        if (empty($this->value = \filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING))) {
            $this->value = '';
        }

        if (empty($this->action = \filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING))) {
            throw new InvalidArgumentException('action Must be set');
        }

    }
}