<?php

/** @noinspection ALL */

namespace Dipantry\Kbbi\Tests;

use Dipantry\Kbbi\KBBI;

class SuccessTest extends TestCase
{
    public function testOneManyList()
    {
        $kbbi = (new KBBI())->search('demokrasi', $this->getApplicationSession());
        self::assertNotEmpty($kbbi);

        $decoded_data = $this->decodeContent($kbbi);
        self::assertTrue($decoded_data['success']);
        self::assertCount(1, $decoded_data['data']);

        foreach ($decoded_data['data'] as $content) {
            self::assertNotEmpty($content['spelling']);
            self::assertCount(2, $content['meanings']);

            foreach ($content['meanings'] as $meaning) {
                self::assertNotEmpty($meaning['description']);
                self::assertCount(2, $meaning['categories']);

                foreach ($meaning['categories'] as $category) {
                    self::assertNotEmpty($category['code']);
                    self::assertNotEmpty($category['description']);
                }
            }
        }
    }

    public function testOneManyAndOneSingleList()
    {
        $kbbi = (new KBBI())->search('makan', $this->getApplicationSession());
        self::assertNotEmpty($kbbi);

        $decoded_data = $this->decodeContent($kbbi);
        self::assertTrue($decoded_data['success']);
        self::assertCount(2, $decoded_data['data']);

        foreach ($decoded_data['data'] as $index => $content) {
            self::assertNotEmpty($content['spelling']);

            if ($index == 0) {
                self::assertCount(15, $content['meanings']);
            } else {
                self::assertCount(1, $content['meanings']);
            }
        }
    }

    public function testMultipleSingleList()
    {
        $kbbi = (new KBBI())->search('aku', $this->getApplicationSession());
        self::assertNotEmpty($kbbi);

        $decoded_data = $this->decodeContent($kbbi);
        self::assertTrue($decoded_data['success']);
        self::assertCount(3, $decoded_data['data']);

        foreach ($decoded_data['data'] as $index => $content) {
            self::assertNotEmpty($content['spelling']);
            self::assertCount(1, $content['meanings']);
        }
    }

    public function testMultipleManyList()
    {
        $kbbi = (new KBBI())->search('sayang', $this->getApplicationSession());
        self::assertNotEmpty($kbbi);

        $decoded_data = $this->decodeContent($kbbi);
        self::assertTrue($decoded_data['success']);
        self::assertCount(2, $decoded_data['data']);

        foreach ($decoded_data['data'] as $index => $content) {
            self::assertNotEmpty($content['spelling']);

            if ($index == 0) {
                self::assertCount(3, $content['meanings']);
            } else {
                self::assertCount(4, $content['meanings']);
            }
        }
    }
}
