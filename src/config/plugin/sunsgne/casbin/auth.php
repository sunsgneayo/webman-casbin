<?php
return [
    'default' => 'basic',
    'basic' => [
        # Model 设置
        'model' => [
            'config_type' => 'file',
            'config_file_path' => config_path() . '/plugin/sunsgne/casbin/rbac-model.conf',
            'config_text' => '',
        ],
        # 适配器
        'adapter' => \sunsgne\Adapter\DatabaseAdapter::class,
        'database' => [
            'connection' => '',
            'rules_table' => 'casbin_rule',
        ],
        # 多进程策略定时刷新时间，单位秒
        'policy_refresh_time' => 180
    ]
];