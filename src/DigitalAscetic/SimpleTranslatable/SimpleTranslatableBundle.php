<?php

namespace DigitalAscetic\SimpleTranslatable;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SimpleTranslatableBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
