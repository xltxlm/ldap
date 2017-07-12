<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/7/10
 * Time: 13:34
 */

namespace xltxlm\ldap;

use xltxlm\helper\Hclass\ConvertObject;
use xltxlm\ldap\Config\LdapConfig;
use xltxlm\ldap\Config\LdapItemModel;

/**
 * 操作类，支持增删改查
 * Class Ldap
 * @package xltxlm\ldap
 */
class Ldap
{
    /** @var  LdapConfig */
    protected $LdapConfig;

    /** @var  LdapItemModel */
    protected $LdapItemModel;

    /**
     * @return LdapItemModel
     */
    public function getLdapItemModel(): LdapItemModel
    {
        return $this->LdapItemModel;
    }

    /**
     * @param LdapItemModel $LdapItemModel
     * @return Ldap
     */
    public function setLdapItemModel(LdapItemModel $LdapItemModel): Ldap
    {
        $this->LdapItemModel = $LdapItemModel;
        return $this;
    }

    /**
     * @return LdapConfig
     */
    public function getLdapConfig()
    {
        return $this->LdapConfig;
    }

    /**
     * @param LdapConfig $LdapConfig
     * @return Ldap
     */
    public function setLdapConfig($LdapConfig)
    {
        $this->LdapConfig = $LdapConfig;
        return $this;
    }


    /**
     * 添加数据
     */
    public function insert()
    {
        $connect = $this->getLdapConfig()->__invoke();
        $info = (new ConvertObject($this->getLdapItemModel()))
            ->toArray();
        return ldap_add($connect, $this->getLdapItemModel()->getUserdn() . $this->getLdapConfig()->getUserdn(), $info);
    }

    /**
     * 删除掉一个账户
     */
    public function delete()
    {
        $connect = $this->getLdapConfig()->__invoke();
        ldap_delete($connect, $this->getLdapItemModel()->getUserdn() . $this->getLdapConfig()->getUserdn());
    }

    /**
     * 修改密码
     */
    public function changePassword()
    {
        $connect = $this->getLdapConfig()->__invoke();
        $values["userPassword"] = "{SHA}" . base64_encode(pack("H*", sha1($this->getLdapItemModel()->getUserPasswordOld())));  //密码sha1加密
        $user_dn = $this->getLdapItemModel()->getUserdn() . $this->getLdapConfig()->getUserdn();
        ldap_mod_replace($connect, $user_dn, $values);
    }

    /**
     * 校验账户密码是否正确
     */
    public function check(): bool
    {
        $connect = $this->getLdapConfig()->__invoke();
        $user_dn = $this->getLdapItemModel()->getUserdn() . $this->getLdapConfig()->getUserdn();
        return ldap_bind($connect, $user_dn, $this->getLdapItemModel()->getUserPasswordOld());
    }
}