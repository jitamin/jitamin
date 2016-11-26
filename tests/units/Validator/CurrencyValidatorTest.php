<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Hiject\Validator\CurrencyValidator;

class CurrencyValidatorTest extends Base
{
    public function testValidation()
    {
        $currencyValidator = new CurrencyValidator($this->container);
        $result = $currencyValidator->validateCreation([]);
        $this->assertFalse($result[0]);

        $result = $currencyValidator->validateCreation(['currency' => 'EUR']);
        $this->assertFalse($result[0]);

        $result = $currencyValidator->validateCreation(['rate' => 1.9));
        $this->assertFalse($result[0]);

        $result = $currencyValidator->validateCreation(['currency' => 'EUR', 'rate' => 'foobar']);
        $this->assertFalse($result[0]);

        $result = $currencyValidator->validateCreation(['currency' => 'EUR', 'rate' => 1.25]);
        $this->assertTrue($result[0]);
    }
}
