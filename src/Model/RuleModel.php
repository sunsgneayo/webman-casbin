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
     * a cache store.
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $store;

//    /**
//     * 设置字段信息
//     *
//     * @var array
//     */
//    protected $schema = [
//        'id'    => 'int',
//        'ptype' => 'string',
//        'v0'    => 'string',
//        'v1'    => 'string',
//        'v2'    => 'string',
//        'v3'    => 'string',
//        'v4'    => 'string',
//        'v5'    => 'string',
//    ];
    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = ['ptype', 'v0', 'v1', 'v2', 'v3', 'v4', 'v5'];


    /**
     * the guard for lauthz.
     *
     * @var string
     */
    protected $guard;

    /**
     * 架构函数
     * @access public
     * @param array $data 数据
     */
    public function __construct($data = [])
    {
//        $this->connection = $this->config('database.connection') ?: '';
//        $this->table = $this->config('database.rules_table');
//        $this->name = $this->config('database.rules_name');
//        parent::__construct($data);

        $connection = $this->config('database.connection') ?: config('database.default');

        $this->setConnection($connection);
        $this->setTable($this->config('database.rules_table'));

        parent::__construct($data);

//        $this->initCache();
    }

    /**
     * Gets config value by key.
     *
     * @param string $key
     * @param string $default
     *
     * @return mixed
     */
    protected function config(string $key = null, $default = null)
    {
        $driver = config('plugin.Sunsgne.casbin.permission.default');
        return config('plugin.Sunsgne.casbin.permission.' . $driver . '.' . $key, $default);
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
        if (!$this->config('cache.enabled', false)) {
            return $get();
        }

        return $this->store->remember($this->config('cache.key'), $this->config('cache.ttl'), $get);
    }
}
