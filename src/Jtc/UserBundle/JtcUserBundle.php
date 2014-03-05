<?php

namespace Jtc\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JtcUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }


}
