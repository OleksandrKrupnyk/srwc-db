<?php


namespace zukr\base\database;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Processor\IntrospectionProcessor;
use MysqliDb;
use zukr\base\Dir;

/**
 * Class MysqliDbWrapper
 *
 * @package      zukr\base\database
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class MysqliDbWrapper extends MysqliDb
{
    /**
     * @var null|MonologLogger
     */
    private $logger;

    /**
     * @return \mysqli_stmt
     * @throws \Exception
     */
    public function _prepareQuery()
    {
        $r = parent::_prepareQuery();
        $this->getLogger()->info($this->_query);
        return $r;
    }

    /**
     * @return MonologLogger
     */
    public function getLogger(): ?MonologLogger
    {
        $dir = Dir::getInstance();
        try {
            if ($this->logger === null) {
                $dateFormat = "Y-m-d H:i:s.u";
                // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
                $output = "%datetime% > %level_name% > %message% %context%" . PHP_EOL;
                // finally, create a formatter
                $formatter = new LineFormatter($output, $dateFormat);
                $stream = new StreamHandler($this->getFileToLog($dir), MonologLogger::DEBUG);
                $stream->setFormatter($formatter);
                $logger = new MonologLogger('dbase');
                // add records to the log
                $logger->pushProcessor(new IntrospectionProcessor());
                $logger->pushHandler($stream);
                $this->logger = $logger;
            }
        } catch (\Exception $e) {

        }
        return $this->logger;
    }

    /**
     * @param Dir $dir
     * @return string
     */
    protected function getFileToLog(Dir $dir): string
    {
        return \realpath($dir->getTmpDir() . '/db.log');
    }
}