<?php


namespace zukr\base;

use zukr\user\User;
use zukr\user\UserRepository;

/**
 * Class LoginUser
 *
 * @package      zukr\base
 * @author       Alex.Krupnik <krupnik_a@ukr.net>
 * @copyright (c), Thread
 */
class LoginUser
{

    /** @var LoginUser */
    private static $obj;

    /**
     * @var AuthInterface
     */
    private $user;
    /**
     * @var int
     */
    private $user_id;


    private function __construct()
    {
        $this->user_id = $_SESSION['user_id'] ?? 0;
    }

    /**
     * @return LoginUser
     */
    public static function getInstance()
    {
        if (self::$obj === null) {
            self::$obj = new self();
        }
        return self::$obj;
    }

    /**
     * @return AuthInterface
     */
    public function getUser()
    {
        if ($this->user === null) {
            $userData = (new UserRepository())->getById($this->user_id);
            unset($userData['pass']);
            if ($userData !== null) {
                $this->user = new User();
                $this->user->load($userData, false);
            } else {
                $this->user = new Guest();
            }
        }
        return $this->user;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->user_id;
    }
}