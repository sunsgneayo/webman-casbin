<?php
declare(strict_types=1);

namespace sunsgne;


use Casbin\Enforcer;
use Casbin\Exceptions\CasbinException;
use Casbin\Model\Model;
use support\Container;
use Workerman\Timer;
use Workerman\Worker;
use Webman\Bootstrap;

/**
 * @see \Casbin\Enforcer
 * @mixin \Casbin\Enforcer
 * @method static enforce(mixed ...$rvals) 权限检查，输入参数通常是(sub, obj, act)
 * @method static bool addPolicy(mixed ...$params) 当前策略添加授权规则
 * @method static bool addPolicies(mixed ...$params) 当前策略添加授权规则
 * @method static bool hasPolicy(mixed ...$params) 确定是否存在授权规则
 * @method static bool removePolicy(mixed ...$params) 当前策略移除授权规则
 * @method static getAllRoles() 获取所有角色
 * @method static getPolicy() 获取所有的角色的授权规则
 * @method static getRolesForUser(string $name, string ...$domain) 获取某个用户的所有角色
 * @method static getUsersForRole(string $name, string ...$domain) 获取某个角色的所有用户
 * @method static hasRoleForUser(string $name, string $role, string ...$domain) 决定用户是否拥有某个角色
 * @method static addRoleForUser(string $user, string $role, string ...$domain) 给用户添加角色
 * @method static addauthForUser(string $user, string ...$auth) 赋予权限给某个用户或角色
 * @method static deleteRoleForUser(string $user, string $role, string $domain) 删除用户的角色
 * @method static deleteRolesForUser(string $user, string ...$domain) 删除某个用户的所有角色
 * @method static deleteRole(string $role) 删除单个角色
 * @method static deleteauth(string ...$auth) 删除某个权限
 * @method static deleteauthsForUser(string $user, string ...$auth) 删除某个用户或角色的权限
 * @method static getauthsForUser(string $user) 获取用户或角色的所有权限
 * @method static hasauthForUser(string $user, string ...$auth) 决定某个用户是否拥有某个权限
 * @method static getImplicitRolesForUser(string $name, string ...$domain) 获取用户具有的隐式角色
 * @method static getImplicitauthsForUser(string $username, string ...$domain) 获取用户具有的隐式权限
 * @method static addFunction(string $name, \Closure $func) 添加一个自定义函数
 */

class Auth implements Bootstrap
{
    /**
     * @var $_manager
     */
    protected static $_manager = null;

    /**
     * @param $worker
     * @return void
     * @throws CasbinException
     * @datetime 2022/5/16 15:11
     * @author zhulianyou
     */
    public static function start($worker)
    {
        $configType = config('plugin.sunsgne.casbin.auth.basic.model.config_type');
        $model = new Model();
        if ('file' == $configType) {
            $model->loadModel(config('plugin.sunsgne.casbin.auth.basic.model.config_file_path'));
        } elseif ('text' == $configType) {
            $model->loadModel(config('plugin.sunsgne.casbin.auth.basic.model.config_text'));
        }
        if (is_null(static::$_manager)) {
            static::$_manager = new Enforcer($model, Container::get(config('plugin.sunsgne.casbin.auth.basic.adapter')),false);
        }
        // 多进程需要使用watcher，这里使用定时器定时刷新策略
        Timer::add(config('plugin.sunsgne.casbin.auth.basic.policy_refresh_time'), function () {
            static::$_manager->loadPolicy();
        });
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @datetime 2022/5/16 16:19
     * @author zhulianyou
     */
    public static function __callStatic($name, $arguments)
    {
        return static::$_manager->{$name}(...$arguments);
    }
}