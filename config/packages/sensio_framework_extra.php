<?php

namespace Symfony\Config;

return static function (SensioFrameworkExtraConfig $config) {
    $config->router()
        ->annotations(false);
};
