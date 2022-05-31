# webman casbin

基于php-casbin，与webman-permission不同之处是没有用thinkorm,然后解决了几个错误
## 依赖
- [tinywan-casbin](https://github.com/php-casbin/webman-permission) 
- [Casbin](https://casbin.org)
- [PHP-DI](https://github.com/PHP-DI/PHP-DI)
- [illuminate/database](https://www.workerman.net/doc/webman/db/tutorial.html)

## 安装

```sh
composer require  sunsgne/casbin
```

## 使用

### 1. 依赖注入配置

修改配置`config/container.php`，其最终内容如下：

```php
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(config('dependence', []));
$builder->useAutowiring(true);
return $builder->build();
```

### 2.创建 `casbin_rule` 数据表
```sql
CREATE TABLE `casbin_rule` (
	`id` BIGINT ( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
	`ptype` VARCHAR ( 128 ) NOT NULL DEFAULT '',
	`v0` VARCHAR ( 128 ) NOT NULL DEFAULT '',
	`v1` VARCHAR ( 128 ) NOT NULL DEFAULT '',
	`v2` VARCHAR ( 128 ) NOT NULL DEFAULT '',
	`v3` VARCHAR ( 128 ) NOT NULL DEFAULT '',
	`v4` VARCHAR ( 128 ) NOT NULL DEFAULT '',
	`v5` VARCHAR ( 128 ) NOT NULL DEFAULT '',
	PRIMARY KEY ( `id` ) USING BTREE,
	KEY `idx_ptype` ( `ptype` ) USING BTREE,
	KEY `idx_v0` ( `v0` ) USING BTREE,
	KEY `idx_v1` ( `v1` ) USING BTREE,
	KEY `idx_v2` ( `v2` ) USING BTREE,
	KEY `idx_v3` ( `v3` ) USING BTREE,
	KEY `idx_v4` ( `v4` ) USING BTREE,
    KEY `idx_v5` ( `v5` ) USING BTREE 
) ENGINE = INNODB CHARSET = utf8mb4 COMMENT = '策略规则表';
```
## 用法
```php
use sunsgne\Auth;

// adds permissions to a user
Auth::addPermissionForUser('user:1', '/api/backend/cap', 'get');
// adds a role for a user.
Auth::addRoleForUser('user:1', 'role:1');
// adds permissions to a rule
Auth::addPolicy('role:1', '/api/backend/login','post');
```

你可以检查一个用户是否拥有某个权限:

```php
if (Auth::enforce("user:1", "/api/backend/login", "post")) {
    echo '恭喜你！通过权限认证';
} else {
    echo '对不起，您没有该资源访问权限';
}
```
## 获取角色|用户
```php
$r =  Auth::getRolesForUser('uuid:1');
$u =  Auth::getUsersForRole('roleId:1');
$a =  Auth::enforce("uuid:1" , "roleId:1" ,"post");
```
### 响应
```php
 array:1 [
  0 => "roleId:1"
]
 array:1 [
  0 => "uuid:1"
]
true
```
