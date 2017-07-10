<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/7/10
 * Time: 13:39
 */

namespace xltxlm\ldap\Config;

/**
 * 账户的数据结构
 * Class LdapItem
 * @package xltxlm\ldap\Config
 */
class LdapItemModel
{
    protected $uid = "";
    protected $cn = "";
    protected $sn = "";
    protected $uidNumber = 1;
    protected $objectclass = [
        'top',
        'posixAccount',
        'inetOrgPerson'
    ];
    protected $gidnumber = '502';
    protected $homedirectory = '';
    protected $loginShell = '/bin/sh';
    protected $userPassword = "";


    /**
     * @param string $alaisName
     * @return LdapItemModel
     */
    public function setAlaisName(string $alaisName): LdapItemModel
    {
        $this->alaisName = $alaisName;
        if (is_string($this->getUid())) {
            $this->uid = $this->cn = $this->sn = [$this->uid];
        }
        $this->uid[] = $this->cn[] = $this->sn[] = $alaisName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    /**
     * @param string $userPassword
     * @return LdapItemModel
     */
    public function setUserPassword(string $userPassword): LdapItemModel
    {
        $this->userPassword = "{SHA}" . base64_encode(pack("H*", sha1($userPassword)));;
        return $this;
    }


    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return LdapItemModel
     */
    public function setUid($uid)
    {
        $this->uid = $this->cn = $this->sn = $uid;
        return $this;
    }

    public function getUserdn()
    {
        $uid = $this->getUid();
        if (is_array($uid)) {
            $uid = array_shift($uid);
        }
        return "cn={$uid},";
    }

    public function __invoke(): LdapItemModel
    {
        $this->homedirectory = '/home/users/' . $this->getUid();
        return $this;
    }
}