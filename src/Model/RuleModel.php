<?php
declare(strict_types=1);

namespace sunsgne\Model;


use Illuminate\Database\Eloquent\Model;

/**
 * @purpose 数据模型
 * @date 2022/5/16
 * @author zhulianyou
 */
class RuleModel extends Model
{
    /**
     * @var string
     */
    protected mixed $guard;

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
            $this->guard = config('plugin.casbin.webman-auth.auth.default');
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
    public function getAllFromCache(): mixed
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
        return config('plugin.casbin.webman-auth.auth.'.$this->guard.'.'.$key, $default);
    }
}
