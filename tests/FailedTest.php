<?php

namespace Dipantry\Kbbi\Tests;

use Dipantry\Kbbi\KBBI;

class FailedTest extends TestCase
{
    public function testNotFound()
    {
        $kbbi = (new KBBI())->search('lorem');

        $decoded_data = $this->decodeContent($kbbi);
        self::assertFalse($decoded_data['success']);
        self::assertEquals('Entri tidak ditemukan.', $decoded_data['message']);
    }
}
