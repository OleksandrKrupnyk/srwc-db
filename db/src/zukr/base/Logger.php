<?php


namespace zukr\base;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Processor\IntrospectionProcessor;
use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{

    private $_logger;

    /**
     * @var Logger
     */
    private static $obj;

    public function __construct()
    {
        $dateFormat = "H:i:s.u";
        // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        $output = "%datetime% > %level_name% > %message% %context%\n";
        // finally, create a formatter
        $formatter = new LineFormatter($output, $dateFormat);

        $stream = new StreamHandler('d:\elm.log', MonologLogger::DEBUG);
        $stream->setFormatter($formatter);

        $logger = new MonologLogger('elm');
        // add records to the log
        $logger->pushProcessor(new IntrospectionProcessor());
        $logger->pushHandler($stream);

        $logger->pushHandler(new BrowserConsoleHandler());
        $this->_logger = $logger;
    }


    /**
     * @return Params
     */
    public static function getInstance(): self
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }


    public function info($message, array $context = []): void
    {
        $this->_logger->info($message, $context);
    }


    /**
     * @inheritDoc
     */
    public function emergency($message, array $context = [])
    {
        $this->_logger->emergency($message, $context);

    }

    /**
     * @inheritDoc
     */
    public function alert($message, array $context = [])
    {
        $this->_logger->alert($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function critical($message, array $context = [])
    {
        $this->_logger->critical($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function error($message, array $context = [])
    {
        $this->_logger->error($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function warning($message, array $context = [])
    {
        $this->_logger->warning($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function notice($message, array $context = [])
    {
        $this->_logger->notice($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function debug($message, array $context = [])
    {
        $this->_logger->debug($message, $context);
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = [])
    {
        $this->_logger->log($level, $message, $context);
    }
}