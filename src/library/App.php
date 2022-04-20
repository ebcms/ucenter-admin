<?php

declare(strict_types=1);

namespace App\Ebcms\UcenterAdmin;

use Ebcms\Framework\AppInterface;
use PDO;

class App implements AppInterface
{

    public static function onInstall()
    {
        $sql = self::getInstallSql();
        fwrite(STDOUT, "是否安装演示数据？y [y,n]：");
        switch (trim((string) fgets(STDIN))) {
            case '':
            case 'y':
            case 'yes':
                fwrite(STDOUT, "安装演示数据\n");
                $sql .= PHP_EOL . self::getDemoSql();
                break;

            default:
                fwrite(STDOUT, "不安装演示数据\n");
                break;
        }
        self::execSql($sql);
    }

    public static function onUninstall()
    {
        $sql = '';
        fwrite(STDOUT, "是否删除数据库？y [y,n]：");
        switch (trim((string) fgets(STDIN))) {
            case '':
            case 'y':
            case 'yes':
                fwrite(STDOUT, "删除数据库\n");
                $sql .= PHP_EOL . self::getUninstallSql();
                break;
            default:
                break;
        }
        self::execSql($sql);
    }

    private static function execSql(string $sql)
    {
        $sqls = array_filter(explode(";" . PHP_EOL, $sql));

        $prefix = 'prefix_';
        $cfg_file = getcwd() . '/config/database.php';
        $cfg = (array)include $cfg_file;
        if (isset($cfg['master']['prefix'])) {
            $prefix = $cfg['master']['prefix'];
        }

        $dbh = new PDO("{$cfg['master']['database_type']}:host={$cfg['master']['server']};dbname={$cfg['master']['database_name']}", $cfg['master']['username'], $cfg['master']['password']);

        foreach ($sqls as $sql) {
            $dbh->exec(str_replace('prefix_', $prefix, $sql . ';'));
        }
    }

    private static function getInstallSql(): string
    {
        return <<<'str'
DROP TABLE IF EXISTS `prefix_ebcms_user_message`;
CREATE TABLE `prefix_ebcms_user_message` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `user_id` int(10) unsigned NOT NULL DEFAULT '0',
    `title` varchar(80) NOT NULL DEFAULT '' COMMENT '标题',
    `body` text COMMENT '内容',
    `send_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
    `is_read` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0未读 1已读',
    `read_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读时间',
    PRIMARY KEY (`id`) USING BTREE,
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='会员日志表';
DROP TABLE IF EXISTS `prefix_ebcms_user_user`;
CREATE TABLE `prefix_ebcms_user_user` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `phone` varchar(20) NOT NULL DEFAULT '',
    `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '标题',
    `avatar` varchar(255) NOT NULL DEFAULT '',
    `introduction` varchar(255) NOT NULL DEFAULT '',
    `score` int(10) unsigned NOT NULL COMMENT '积分',
    `coin` int(10) unsigned NOT NULL COMMENT '金币',
    `state` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0待审核 1正常 99黑名单',
    `salt` char(32) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE KEY `phone` (`phone`) USING BTREE,
    UNIQUE KEY `nickname` (`nickname`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='会员表';
str;
    }

    private static function getDemoSql(): string
    {
        return <<<'str'
str;
    }

    private static function getUninstallSql(): string
    {
        return <<<'str'
DROP TABLE IF EXISTS `prefix_ebcms_user_message`;
DROP TABLE IF EXISTS `prefix_ebcms_user_user`;
str;
    }
}
