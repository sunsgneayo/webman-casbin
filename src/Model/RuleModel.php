<?php

/**
 * @desc Real Policy Model
 * @author Tinywan(ShaoBo Wan)
 * @date 2021/8/28 10:37
 */

declare(strict_types=1);

namespace Sunsgne\Casbin\Model;


use Illuminate\Database\Eloquent\Model;

/**
 * RuleModel Model
 */
class RuleModel extends Model
{
    /**
     * @var string
     */
    protected $guard;

    /**
     * @var string[]
     */
    protected $fillable = ['ptype', 'v0', 'v1', 'v2', 'v3', 'v4', 'v5'];

    /**
     * @param array $attributes
     * @param string $guard
     */
    public function __construct(array $attributes = [], string $guard = '')
    {
        $this->guard = $guard;
        if (!$guard) {
            $this->guard = config('plugin.casbin.webman-permission.permission.default');
        }
        $connection = $this->config('database.connection') ?: config('database.default');
        $this->setConnection($connection);
        $this->setTable($this->config('database.rules_table'));

        parent::__construct($attributes);

    }
    /**
     * Gets rules from caches.
     *
     * @return mixed
     */
    public function getAllFromCache()
    {
        $get = function () {
            return $this->select('ptype', 'v0', 'v1', 'v2', 'v3', 'v4', 'v5')->get()->toArray();
        };
        return $get();
    }


    /**
     * Gets config value by key.
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    protected function config($key = null, $default = null)
    {
        return config('plugin.casbin.webman-permission.permission.'.$this->guard.'.'.$key, $default);
    }
}
