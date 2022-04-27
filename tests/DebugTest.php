<?php /** @noinspection ALL */

namespace Dipantry\Kbbi\Tests;

use Dipantry\Kbbi\KBBI;

class DebugTest extends TestCase
{
    public function testDemokrasiSuccessful(){
        $kbbi = (new KBBI())->search('demokrasi', $this->getApplicationSession());
        self::assertNotEmpty($kbbi);

        $decoded_data = $this->decodeContent($kbbi);
        self::assertTrue($decoded_data['success']);
        self::assertCount(1, $decoded_data['data']);

        foreach ($decoded_data['data'] as $content){
            self::assertNotEmpty($content['spelling']);
            self::assertCount(2, $content['meanings']);

            foreach ($content['meanings'] as $meaning){
                self::assertNotEmpty($meaning['description']);
                self::assertCount(2, $meaning['categories']);

                foreach ($meaning['categories'] as $category){
                    self::assertNotEmpty($category['code']);
                    self::assertNotEmpty($category['description']);
                }
            }
        }
    }

    public function testNotFound(){
        $kbbi = (new KBBI())->search('lorem');

        $decoded_data = $this->decodeContent($kbbi);
        self::assertFalse($decoded_data['success']);
        self::assertEquals('Kata tidak ditemukan', $decoded_data['message']);
    }
}