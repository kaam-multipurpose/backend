<?php

declare(strict_types=1);

use App\Utils\Logger\Logger;
use Rector\Config\RectorConfig;
use Rector\Exception\Configuration\InvalidConfigurationException;

try {
    return RectorConfig::configure()
        ->withPaths([
            __DIR__.'/app',
            __DIR__.'/bootstrap',
            __DIR__.'/config',
            __DIR__.'/public',
            __DIR__.'/resources',
            __DIR__.'/routes',
            __DIR__.'/tests',
        ])
        // uncomment to reach your current PHP version
        ->withPhpSets(php82: true)
        ->withTypeCoverageLevel(2)
        ->withDeadCodeLevel(2)
        ->withCodeQualityLevel(2);
} catch (InvalidConfigurationException $e) {
    Log::error("Unable to rector load configuration: {$e->getMessage()}",[
        "trace" => $e->getTraceAsString(),
        "file" => $e->getFile(),
    ]);
}
