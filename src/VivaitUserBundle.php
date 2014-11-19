<?php

namespace Vivait\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VivaitUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
