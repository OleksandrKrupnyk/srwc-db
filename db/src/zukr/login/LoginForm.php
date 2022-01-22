<?php


namespace zukr\login;

use zukr\base\Record;

/**
 * Class LoginForm
 *
 * @package zukr\login
 * @author Alex.Krupnik <krupnik_a@ukr.net>
 */
class LoginForm extends Record
{
    /**
     * @var string
     */
    public $userName;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $rememberMe;
    /**
     * @var int
     */
    private $id;
    /**
     * @var \MysqliDb
     */
    private $_db;

    /**
     * LoginForm constructor.
     *
     * @param string $userName
     * @param string $password
     * @param string $rememberMe
     */
    public function __construct(
        string $userName,
        string $password,
        string $rememberMe
    )
    {
        parent::__construct();
        $this->userName = $userName;
        $this->password = $password;
        $this->rememberMe = $rememberMe;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $result = $this->getDb()->where('usr', $this->userName, '=')
            ->where('pass', $this->password)
            ->getOne(self::getTableName(), 'id,usr, pass');
        if ($result !== null) {

            $this->id = (int)($result['id']);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'tz_members';
    }
}