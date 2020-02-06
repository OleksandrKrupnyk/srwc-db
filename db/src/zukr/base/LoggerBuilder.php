<?php


namespace zukr\base;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Processor\IntrospectionProcessor;

/**
 * Class Logger
 *
 * @package      zukr\base
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class LoggerBuilder
{
    /**
     * @var null|MonologLogger
     */
    private $_logger = null;

    /**
     * @var LoggerBuilder
     */
    private static $obj;

    /**
     * LoggerBuilder constructor.
     */
    private function __construct()
    {

    }


    /**
     * @return LoggerBuilder
     * @throws \Exception
     */
    public static function getInstance(): self
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    /**
     * @param $logFile
     * @return MonologLogger
     * @throws \Exception
     */
    public function getLogger($logFile): MonologLogger
    {
        if ($this->_logger === null) {
            $dateFormat = 'Y-m-d H:i:s.u';
            // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
            $output = "%datetime% > %level_name% > %message% %context%" . PHP_EOL;
            // finally, create a formatter
            $formatter = new LineFormatter($output, $dateFormat);
            $stream = new StreamHandler($logFile, MonologLogger::DEBUG);
            $stream->setFormatter($formatter);

            $logger = new MonologLogger('elm');
            // add records to the log
            $logger->pushProcessor(new IntrospectionProcessor());
            $logger->pushHandler($stream);

            $logger->pushHandler(new BrowserConsoleHandler());
            $this->_logger = $logger;
        }
        return $this->_logger;
    }
}