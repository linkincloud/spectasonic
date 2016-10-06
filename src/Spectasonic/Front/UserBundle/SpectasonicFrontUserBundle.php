<?php

// src\Spectasonic\Front\UserBundle\SpectasonicFrontUserBundle.php

namespace Spectasonic\Front\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpectasonicFrontUserBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }

}
