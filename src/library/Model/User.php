<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin\Model;

use DigPHP\Database\Db;
use DigPHP\Framework\Config;
use DigPHP\Framework\Framework;
use DigPHP\Session\Session;

class User
{

    private $db;
    private $users = [];

    public function __construct(
        Db $db
    ) {
        $this->db = $db;
    }

    public function init($ids)
    {
        $tmp = [];
        foreach ($ids as $value) {
            if (!isset($this->users[$value])) {
                $tmp[] = $value;
            }
        }
        if ($tmp) {
            $users = $this->db->select('ebcms_user_user', '*', [
                'id' => $tmp,
            ]);
            foreach ($users as $value) {
                $this->users[$value['id']] = $value;
            }
        }
    }

    public function login(int $uid): bool
    {
        $this->getLog()->record($uid, 'login');
        $this->getSession()->set('ucenter_user_id', $uid);
        setcookie($this->getTokenKey(), $this->makeToken($uid), time() + (int)$this->getConfig()->get('auth.expire_time@ebcms.ucenter', 0), '/');
        return true;
    }

    public function logout(): bool
    {
        $this->getLog()->record($this->getLoginId(), 'logout');
        setcookie($this->getTokenKey(), '', time() - 3600, '/');
        $this->getSession()->delete('ucenter_user_id');
        return true;
    }

    public function getLoginId(): int
    {
        if ($uid = $this->getSession()->get('ucenter_user_id')) {
            return (int)$uid;
        }

        if ($uid = $this->autoLogin()) {
            return (int)$uid;
        }

        return 0;
    }

    private function autoLogin(): int
    {
        if (isset($_COOKIE[$this->getTokenKey()])) {
            $token = $_COOKIE[$this->getTokenKey()];

            $tmp = array_filter(explode('_', $token));
            if (count($tmp) == 2) {
                $uid = (int)$tmp[0];
                $code = $tmp[1];
                if ($user = $this->db->get('ebcms_user_user', '*', [
                    'id' => $uid,
                ])) {
                    if (md5($user['salt'] . '_' . $uid) == $code) {
                        $this->login($uid);
                        return $uid;
                    }
                }
            }
        }
        return 0;
    }

    private function makeToken(int $uid): string
    {
        if ($user = $this->db->get('ebcms_user_user', '*', [
            'id' => $uid,
        ])) {
            return $uid . '_' . md5($user['salt'] . '_' . $uid);
        }
        return '';
    }

    private function getTokenKey(): string
    {
        return md5($_SERVER['HTTP_USER_AGENT']);
    }

    private function getSession(): Session
    {
        return Framework::execute(function (
            Session $session
        ): Session {
            return $session;
        });
    }

    private function getConfig(): Config
    {
        return Framework::execute(function (
            Config $config
        ): Config {
            return $config;
        });
    }

    private function getLog(): Log
    {
        return Framework::execute(function (
            Log $log
        ): Log {
            return $log;
        });
    }
}
