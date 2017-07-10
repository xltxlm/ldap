<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/7/10
 * Time: 13:29
 */

namespace xltxlm\ldap\Config;

use xltxlm\config\TestConfig;
use xltxlm\helper\Hclass\ConvertObject;

/**
 * Ldap的基础配置结构类
 * Class LdapConfig
 * @package xltxlm\ldap\Config
 */
class LdapConfig implements TestConfig
{
    protected $ldap_connect;
    /** @var string 服务器地址/域名 */
    protected $host = "";
    /** @var int 端口 */
    protected $port = 389;
    /** @var string 用户的域名路径 */
    protected $userdn = "";
    /** @var string 管理员的sn */
    protected $adminrdn = "";
    /** @var string 密码 */
    protected $password = "";

    /**
     * @return string
     */
    public function getUserdn(): string
    {
        return $this->userdn;
    }

    /**
     * @param string $userdn
     * @return LdapConfig
     */
    public function setUserdn(string $userdn): LdapConfig
    {
        $this->userdn = $userdn;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return LdapConfig
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return LdapConfig
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdminrdn()
    {
        return $this->adminrdn;
    }

    /**
     * @param string $adminrdn
     * @return LdapConfig
     */
    public function setAdminrdn($adminrdn)
    {
        $this->adminrdn = $adminrdn;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return LdapConfig
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * 返回ldap的链接
     */
    public function __invoke()
    {
        static $md5s = [];
        $tns = (new ConvertObject($this))
            ->toMd5();
        if ($md5s[$tns]) {
            $this->ldap_connect = $md5s[$tns];
        } else {
            $this->ldap_connect = ldap_connect($this->getHost(), $this->getPort());
            ldap_set_option($this->ldap_connect, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->ldap_connect, LDAP_OPT_REFERRALS, 0);
            ldap_bind($this->ldap_connect, $this->getAdminrdn(), $this->getPassword());
            $md5s[$tns] = $this->ldap_connect;
        }
        return $this->ldap_connect;
    }

    /**
     * 测试服务器链接是否正常
     * @throws \Exception
     */
    public function test()
    {
        if (!$this->__invoke()) {
            throw new \Exception("链接ldap失败:{$this->getHost()}");
        }
    }


}