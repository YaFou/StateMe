<?php

namespace Symfony\Config;

return static function (DebugConfig $config) {
    $config->dumpDestination("tcp://%env(VAR_DUMPER_SERVER)%");
};
