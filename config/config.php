<?php // @formatter:off
declare(strict_types=1);

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

use function Hyperf\Support\env;

return [
    'app_name'                      =>  env('APP_NAME', 'skeleton'),
    'app_env'                       =>  env('APP_ENV', 'dev'),
    'scan_cacheable'                =>  env('SCAN_CACHEABLE', false),
    StdoutLoggerInterface::class    =>  [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
    'admin'     =>  [
        'key'   =>  '7L7wBz2uM8NVFnmQNC95XOZ6NVMscl4Yh0ZWTYjKdTG_aTa8FBM_uRDeNl4RWMz4muflX82CoS8wc0bgkfsjzX3oJrQ_VgmlObCCsUBixYFT8AkjNhuzz98adyaH43AxASf6P1ZpeTvuwnwZzTxsFYwfSDsnfmQWTTnEPVH8eUc',
    ]
];
