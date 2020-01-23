<?php


namespace zukr\univer;

use zukr\base\Base;
use zukr\base\helpers\ArrayHelper;

/**
 * Class UniverHelper
 *
 * @package      zukr\univer
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class UniverHelper
{

    /**
     * @var UniverHelper
     */
    private static $obj;

    /**
     * @var array
     */
    private $univers;
    /**
     * @var UniverRepository
     */
    private $univerRepository;

    /**
     * @return UniverHelper
     */
    public static function getInstance(): UniverHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }


    /**
     * @param array $univers
     * @return array
     */
    public function getDropDownListShotFull(array $univers): array
    {
        $list = [];
        foreach ($univers as $key => $u) {
            $list [$key] = '(' . $u['univer'] . ') ' . $u['univerfull'];
        }
        ArrayHelper::asort($list);
        return $list;
    }

    /**
     * @param array $univers
     * @return array
     */
    public function getDropDownListShot(array $univers): array
    {
        $list = [];
        foreach ($univers as $key => $u) {
            $list [$key] = $u['univer'];
        }
        ArrayHelper::asort($list);
        return $list;
    }

    /**
     * @return array
     */
    public function getAllUniversFromDB(): array
    {
        return $this->getUniverRepository()->getAllUniversAsArrayFromDB();
    }

    /**
     * @return array|mixed
     */
    protected function getUnivers(): array
    {
        if ($this->univers === null) {
            $univers = Base::$app->cacheGetOrSet(static::class, $this->getAllUniversFromDB(), 360);
            $this->univers = $univers;
        }
        return $this->univers;
    }

    /**
     * @return array Список запрошених університетів + ДДТУ
     */
    public function getInvitedUniversAndDSTU(): array
    {
        return \array_filter($this->getUnivers(),
            static function ($univer) {
                return (int)$univer['invite'] === 1 || $univer['id'] === 1;
            });
    }

    /**
     * @return array|\MysqliDb
     */
    public function getInvitedDropdownList(): array
    {
        return $this->getDropDownListShotFull(
            $this->getInvitedUniversAndDSTU()
        );

    }

    /**
     * Список універистетів, що подали роботи і хоча б одна робота була запрошена (окрім ДДТУ)
     *
     *
     * @return array Список університетів
     */
    public function getInvitedDropdownListWithoutDSTU(): array
    {
        return $this->getDropDownListFull(
            \array_filter($this->getUniverRepository()->getUniversWhoSentWorks(), static function ($v) {
                return (string)$v['id'] !== '1';
            }));
    }

    /**
     * @param array $univers
     * @return array
     */
    public function getDropDownListFull(array $univers): array
    {
        $list = [];
        foreach ($univers as $key => $u) {
            $list [$key] = $u['univerfull'];
        }
        ArrayHelper::asort($list);
        return $list;
    }

    /**
     * @param array $univerIds
     * @return array
     */
    public function getTakePartUniversDropDownList(array $univerIds): array
    {
        $list = [];
        $invitedUnivers = $this->getInvitedUniversAndDSTU();
        foreach ($univerIds as $univerId) {
            $list[$univerId] = $invitedUnivers[$univerId];
        }
        return $list;

    }

    /**
     * @return string
     */
    public function registerJS(): string
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'univer.js';
        return \file_exists($filename) && \is_file($filename)
            ? '<script>' . \file_get_contents($filename) . '</script>'
            : '';
    }

    /**
     * @return UniverRepository
     */
    public function getUniverRepository(): UniverRepository
    {
        if ($this->univerRepository === null) {
            $this->univerRepository = new UniverRepository();
        }
        return $this->univerRepository;
    }

    /**
     * @return array|mixed
     */
    public function getUniversIdWhoSendWork()
    {
        return $this->getUniverRepository()->getUniversIdWhoSendWork();
    }

    /**
     * Повертає дані університету
     *
     * @param int|string $id ІД запис університету
     * @return array|null Дані університету
     */
    public function getUniverById($id): ?array
    {
        return $this->getUniverRepository()->getById($id);
    }

    /**
     * @return array Список посад керівника ВНЗ
     */
    public function getPositionList(): array
    {
        return [
            'Ректору' => 'Ректору',
            'В.о.ректора' => 'В.о.ректора',
            'Директору' => 'Директору',
            'Начальнику інституту' => 'Начальнику інституту',
            'Начальнику академії' => 'Начальнику академії',
            'Начальнику військового інституту' => 'Начальнику військового інституту'
        ];
    }

    /**
     * @return array Список запрощених унівесритетів
     */
    public function getInvited(): array
    {
        return $this->getUniverRepository()->getInvited();
    }
}