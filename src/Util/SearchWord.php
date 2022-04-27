<?php

namespace Dipantry\Kbbi\Util;

use Dipantry\Kbbi\Exception\KbbiResponseException;
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;

class SearchWord
{
    private string $kbbiUrl = 'https://kbbi.kemdikbud.go.id/entri';
    private $doc;
    private $xpath;

    /* @throws GuzzleException|KbbiResponseException */
    private function getHttp($url, string $session): StreamInterface
    {
        try {
            $httpClient = new Client();
            $response = $httpClient->get($this->kbbiUrl . $url, [
                'headers' => [
                    'Cookie' => '.AspNet.ApplicationCookie=' . $session,
                ]
            ]);
        } catch (Exception){
            throw new KbbiResponseException('Koneksi ke KBBI gagal');
        }

        return $response->getBody();
    }

    /* @throws KbbiResponseException|GuzzleException */
    protected function searchWord(string $word, string $session): array
    {
        libxml_use_internal_errors(true);
        $htmlString = $this->getHttp("/{$word}", $session);

        $this->doc = new DOMDocument();
        $this->doc->loadHTML($htmlString);
        $this->xpath = new DOMXPath($this->doc);

        return $this->processResult($this->doc->getElementsByTagName('h2'));
    }

    private function processResult(DOMNodeList $h2s): array {
        $results = [];
        foreach ($h2s as $index => $h2){
            $result['spelling'] = $h2->nodeValue;

            $meaning_array = [];
            $list = $this->xpath->query("//ol[contains(@class, 'last-list-child')]//li");

            $result['meanings'] = $this->processMeaning($list);

            $results[] = $result;
        }
        return $results;
    }

    private function processMeaning(DOMNodeList $meanings): array {
        $meaning_array = [];
        foreach ($meanings as $index => $meaning){
            if ($index == $meanings->count() - 1){
                continue;
            }

            $mean['description'] = $meaning->childNodes->item(1)->nodeValue;
            $mean['categories'] = $this->processCategory(
                $this->xpath->query('.//font//i//span', $meaning)
            );

            $meaning_array[] = $mean;
        }
        return $meaning_array;
    }

    private function processCategory(DOMNodeList $categories): array {
        $category_array = [];
        foreach ($categories as $index => $category){
            $cat['code'] = $category->nodeValue;
            $cat['description'] = $category->attributes->getNamedItem('title')->nodeValue;

            $category_array[] = $cat;
        }
        return $category_array;
    }
}