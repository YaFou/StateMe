<?php

namespace Symfony\Config;

return static function (WebProfilerConfig $config, FrameworkConfig $frameworkConfig) {
    $config->toolbar(true)
        ->interceptRedirects(false);

    $frameworkConfig->profiler()
        ->onlyExceptions(false);
};
