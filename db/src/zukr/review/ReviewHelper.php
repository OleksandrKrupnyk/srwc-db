<?php


namespace zukr\review;


use zukr\base\helpers\ArrayHelper;
use zukr\base\html\Html;
use zukr\leader\LeaderRepository;
use zukr\work\WorkRepository;

/**
 * Class ReviewHelper
 *
 * @package      zukr\review
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class ReviewHelper
{

    /** @var ReviewHelper */
    private static $obj;
    /** @var WorkRepository */
    protected $workRepository;
    /** @var LeaderRepository */
    protected $leaderRepository;
    /** @var ReviewRepository */
    protected $reviewRepository;

    /**
     * @return LeaderRepository
     */
    public function getLeaderRepository(): LeaderRepository
    {
        if ($this->leaderRepository === null) {
            $this->leaderRepository = new LeaderRepository();
        }
        return $this->leaderRepository;
    }

    /**
     * LeaderHelper constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return ReviewHelper
     */
    public static function getInstance(): ReviewHelper
    {
        if (static::$obj === null) {
            static::$obj = new self();
        }
        return static::$obj;

    }

    /**
     * @param array $work
     * @return string
     */
    public function getWorkDescription(array $work): string
    {
        $strArray = [];
        $strArray[] = $work['introduction'] ? "<strong>Впровадженння:</strong>{$work['introduction']}" : '';
        $strArray[] = $work['public'] ? "<strong>Результати опубліковано:</strong>{$work['public']}" : '';
        $strArray[] = $work['comments'] ? "<strong>Коментар/зауваження до матеріалів:</strong>{$work['comments']}" : '';
        return count($strArray) < 1
            ? "<strong>Увага! Без публікації та впровадження. Зауваження з боку офрмлення документів відсутні.</strong>"
            : implode("<br>", array_filter($strArray));
    }


    public function getQualities()
    {
        $description = $this->getQualityParamDescription();
        return [
            'actual' => ['max' => 10, 'title' => 'Актуальність проблеми'],
            'original' => ['max' => 15, 'title' => 'Новизна та оригінальність ідей'],
            'methods' => ['max' => 15, 'title' => 'Використані методи дослідження'],
            'theoretical' => ['max' => 10, 'title' => 'Теоретичні наукові результати'],
            'practical' => ['max' => 20, 'title' => 'Практична направленість результатів<br>(документальне підтвердження впровадження результатів роботи)'],
            'literature' => ['max' => 5, 'title' => 'Рівень використання наукової літератури та інших джерел інформації'],
            'selfcontained' => ['max' => 10, 'title' => 'Ступінь самостійності роботи'],
            'design' => ['max' => 5, 'title' => 'Якість оформлення'],
            'publication' => ['max' => 10, 'title' => 'Наукові публікації'],
            'government' => [
                'max' => 10,
                'title' => 'Відповідність роботи Державній програмі<br>пріоритетних напрямків інноваційної діяльності',
                'description' => '<details><summary>Довідка</summary>' . $description . '</details>',
            ],
            'tendentious' => ['max' => 10, 'title' => 'Відповідність роботи сучасним світовим тенденціям розвитку<br> електроенергетики, електротехніки та
                електромеханіки'],
        ];
    }

    /**
     * @return string
     */
    private function getQualityParamDescription()
    {
        $intro = ' До пріоритетів віднесено: (<a href="https://ligazakon.net/document/view/kp161056">постановою КМУ від
                        28.12.2016 р. № 1056.</a>)';
        $list = [
            'освоєння нових технологій транспортування енергії, упровадження енергоефективних,
                            ресурсозберігаючих технологій, освоєння альтернативних джерел енергії;',
            'освоєння нових технологій розвитку транспортної системи, ракетно-космічної галузі, авіа- і
                            суднобудування, озброєння та військової техніки;',
            'освоєння нових технологій виробництва матеріалів, їх оброблення і з\'єднання, створення
                            індустрії наноматеріалів та нанотехнологій',
            'технологічне оновлення та розвиток агропромислового комплексу',
            'упровадження нових технологій та обладнання для якісного медичного обслуговування,
                            лікування,
                            фармацевтики',
            'застосування технологій більш чистого виробництва та охорони навколишнього природного
                            середовища',
            'розвиток інформаційних і комунікаційних технологій, робототехніки.'
        ];
        $olList = Html::ol($list);
        return Html::tag('div', $intro . $olList, ['class' => 'help-full',]);
    }

    /**
     * @return array
     */
    public function getWorksWithoutFullReviews(): array
    {
        $works = $this->getWorksRepository()
            ->getWorksNotFullReviews();;
        $items = ArrayHelper::map($works, 'id', 'title');
        $options = ArrayHelper::map($works, 'data-univer-id', 'id_u', 'id');
        return [$items, $options];
    }

    /**
     * @return string
     */
    public function registerJS()
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'review.js';
        $fileContent = \file_exists($filename) && \is_file($filename)
            ? '<script>' . \file_get_contents($filename) . '</script>'
            : '';
        return $fileContent;
    }

    /**
     * @return WorkRepository
     */
    public function getWorksRepository(): WorkRepository
    {
        if ($this->workRepository === null) {
            $this->workRepository = new WorkRepository();
        }
        return $this->workRepository;
    }

    /**
     * @param int $workId
     * @param int $univerId
     * @return array
     */
    public function getListReviewers(int $workId, int $univerId): array
    {
        $reviewers = $this->getLeaderRepository()->getListAvailableReviewersForWork($workId, $univerId);

        return $this->getListDropDown($reviewers);

    }

    /**
     * @param int $workId
     * @param int $univerId
     * @param int $currentRevieverId
     * @return array
     */
    public function getListEditableReviewers(int $workId, int $univerId, int $currentRevieverId): array
    {
        $count = $this->getReviewRepository()->getCountOfReviewByWorkId($workId);
        if ($count !== null && $count === 1) {
            $reviewers = $this->getLeaderRepository()->getListAvailableEditableReviewersForWorkFirstReview($univerId);
        } else {
            $reviewers = $this->getLeaderRepository()->getListAvailableEditableReviewersForWorkOneReviewIsExist($workId, $univerId, $currentRevieverId);
        }
        return $this->getListDropDown($reviewers);

    }

    /**
     * @return ReviewRepository
     */
    public function getReviewRepository(): ReviewRepository
    {
        if ($this->reviewRepository === null) {
            $this->reviewRepository = new ReviewRepository();
        }
        return $this->reviewRepository;
    }

    /**
     * @param array $reviewers
     * @return array
     */
    protected function getListDropDown(array $reviewers): array
    {
        if (empty($reviewers)) {
            return [];
        }
        $list = [];
        foreach ($reviewers as $r) {
            $id = $r['id'];
            unset($r['id']);
            $list[$id] = implode(' ', $r);
        }
        return $list;
    }

    /**
     * @param int $workId
     * @return int|null
     */
    public function getCountOfReviewByWorkId(int $workId): ?int
    {
        return $this->getReviewRepository()->getCountOfReviewByWorkId($workId);
    }
}