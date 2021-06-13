<?php


namespace zukr\template;


use zukr\base\Base;
use zukr\base\exceptions\InvalidArgumentException;

/**
 * Class TemplateService
 *
 * @package zukr\template
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class TemplateService
{
    /**
     * @var TemplateRepository
     */
    private $templateRepository;

    public function __construct()
    {
        $this->templateRepository = new TemplateRepository();
    }


    /**
     * @param string $blockName
     */
    public function getBlockByName(string $blockName)
    {

        try {
            if (!\in_array($blockName, TemplateNameDictionary::getAll())) {
                throw new InvalidArgumentException('Wrong block name. There is no block page with name: ' . $blockName);
            }
            $blockPage = $this->templateRepository->getLastVersionPublishedBlock($blockName);
            if ($blockPage === null) {
                throw new InvalidArgumentException('Block page not found with name: ' . $blockName);

            }
            return $blockPage->content;
        } catch (\Throwable $e) {
            Base::$log->error($e->getMessage());
            return '';
        }
    }
}