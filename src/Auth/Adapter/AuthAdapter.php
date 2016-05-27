<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 12.02.16
 * Time: 13:49
 */

namespace victorynox\Auth\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class AuthAdapter implements AdapterInterface
{

    private $login;
    private $config;
    private $password;

    public function __construct($login, $password, $config)
    {
        $this->login = $login;
        $this->password = $password;
        $this->config = $config;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        if (isset($this->config['authentications'][$this->login])) {
            $pass = $this->config['authentications'][$this->login]['password'];
            $role = $this->config['authentications'][$this->login]['role'];
            if (strcmp($pass, $this->password) == 0) {
                return new Result(Result::SUCCESS, ['login' => $this->login, 'role' => $role]);
            } else {
                return new Result(Result::FAILURE, $this->login);
            }
        } else {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->login);
        }
    }
}
