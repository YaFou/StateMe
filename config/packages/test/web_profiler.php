<?php

namespace Symfony\Config;

return static function (WebProfilerConfig $config, FrameworkConfig $frameworkConfig) {
    $config->toolbar(false)
        ->interceptRedirects(false);

    $frameworkConfig->profiler()
        ->collect(false);
};
