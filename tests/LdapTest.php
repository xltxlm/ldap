<?php
/**
 * Created by PhpStorm.
 * User: xialintai
 * Date: 2017/7/10
 * Time: 14:13
 */

namespace xltxlm\ldap\Config\tests;


use PHPUnit\Framework\TestCase;
use xltxlm\ldap\Config\LdapConfig;
use xltxlm\ldap\Config\LdapItemModel;
use xltxlm\ldap\Ldap;

class LdapTest extends TestCase
{
    protected static $LdapConfig;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$LdapConfig = (new LdapConfig)
            ->setHost($_SERVER['host'])
            ->setPort($_SERVER['port'])
            ->setAdminrdn($_SERVER['adminrdn'])
            ->setPassword($_SERVER['password'])
            ->setUserdn($_SERVER['userdn']);
    }

    /**
     * 测试添加账户
     */
    public function testadd()
    {
        $LdapItemModel = (new LdapItemModel)
            ->setUid('ldaptest5')
            ->setAlaisName('测试中文名称')
            ->setUserPassword('abc')
            ->__invoke();

        (new Ldap())
            ->setLdapConfig(self::$LdapConfig)
            ->setLdapItemModel($LdapItemModel)
            ->insert();
    }

    /**
     * 删除账户
     */
    public function testdelete()
    {
        $LdapItemModel = (new LdapItemModel)
            ->setUid('ldaptest5')
            ->__invoke();

        (new Ldap())
            ->setLdapConfig(self::$LdapConfig)
            ->setLdapItemModel($LdapItemModel)
            ->delete();
    }

    /**
     * 修改密码
     */
    public function testchangepasseord()
    {
        $LdapItemModel = (new LdapItemModel)
            ->setUid('ldaptest5')
            ->__invoke();

        (new Ldap())
            ->setLdapConfig(self::$LdapConfig)
            ->setLdapItemModel($LdapItemModel)
            ->changePassword('123');
    }


}