<?php

namespace Dwf\PronosticsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DwfPronosticsBundle extends Bundle
{
    public function getParent()
    {
        return 'ApplicationSonataUserBundle';
    }
}
