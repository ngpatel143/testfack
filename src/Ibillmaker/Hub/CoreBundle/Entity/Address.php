<?php

/*
 * This file is part of the Ibillmaker package.
 *
 * (c) Nishant Patel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ibillmaker\Hub\CoreBundle\Entity;

use Sylius\Component\Addressing\Model\Address as baseAddress;

/**
 * Ibillmaler's address model.
 *
 * @author Nishant Patel <ngpatel@outlook.com>
 */
class Address extends baseAddress
{

    public function __construct() {
        parent::__construct();
    }
    
}
