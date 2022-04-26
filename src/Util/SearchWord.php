<?php

namespace Dipantry\Kbbi\Util;

use Dipantry\Kbbi\Exception\KbbiResponseException;
use DOMDocument;
use DOMXPath;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;

class SearchWord
{
    private string $kbbiUrl = 'https://kbbi.kemdikbud.go.id/entri';

    /* @throws GuzzleException|KbbiResponseException */
    private function getHttp($url): StreamInterface
    {
        try {
            $httpClient = new Client();
            $response = $httpClient->get($this->kbbiUrl . $url);
        } catch (Exception){
            throw new KbbiResponseException('Koneksi ke KBBI gagal');
        }

        return $response->getBody();
    }

    /* @throws KbbiResponseException|GuzzleException */
    public function search(string $word)
    {
        libxml_use_internal_errors(true);
        $htmlString = $this->getHttp("/{$word}");

        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);

        $results = [];
        foreach ($doc->getElementsByTagName('h2') as $h2) {
            $result['spelling'] = $h2->nodeValue;

            $meaning_array = [];
            foreach ($xpath->query('//ol//li') as $meaning){
                $mean['description'] = $meaning->childNodes->item(1)->nodeValue;

                $category_array = [];
                foreach ($xpath->query('.//span', $meaning) as $spans){
                    $category['code'] = $spans->nodeValue;
                    $category['description'] = $spans->attributes->getNamedItem('title')->nodeValue;

                    $category_array[] = $category;
                }
                $mean['categories'] = $category_array;

                $meaning_array[] = $mean;
            }

            $result['meanings'] = $meaning_array;
            $results[] = $result;
        }

        return $results;
    }
}