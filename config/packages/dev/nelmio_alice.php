<?php

namespace Symfony\Config;

return static function (NelmioAliceConfig $config) {
    $config->functionsBlacklist(
        [
            'current',
            'shuffle',
            'date',
            'time',
            'file',
            'md5',
            'sha1'
        ]
    );
};
