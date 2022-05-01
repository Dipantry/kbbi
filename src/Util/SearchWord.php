<?php

namespace Dipantry\Kbbi\Util;

use Dipantry\Kbbi\Exception\KbbiResponseException;
use DOMDocument;
use DOMElement;
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
            $response = $httpClient->get($this->kbbiUrl.$url, [
                'headers' => [
                    'Cookie' => '.AspNet.ApplicationCookie='.$session,
                ],
            ]);
        } catch (Exception) {
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

    /* @throws KbbiResponseException */
    private function processResult(DOMNodeList $h2s): array
    {
        $results = [];
        foreach ($h2s as $index => $h2) {
            $result['spelling'] = $h2->nodeValue;

            $manySiblings = $this->xpath->query("following-sibling::ol[@class='last-list-child']", $h2);
            $oneSibling = $this->xpath->query("following-sibling::ul[@class='adjusted-par']", $h2);

            if (count($manySiblings) > 0) {
                $lis = $this->xpath->query('.//li', $manySiblings->item(0));
                $result['meanings'] = $this->processMeanings($lis);
            } elseif (count($oneSibling) > 0) {
                $li = $this->xpath->query('.//li', $oneSibling->item(0));
                $result['meanings'] = $this->processMeaning($li->item(0));
            } else {
                $this->checkError();
                $result['meanings'] = [];
            }

            $results[] = $result;
        }

        return $results;
    }

    private function processMeanings(DOMNodeList $meanings): array
    {
        $meaning_array = [];
        foreach ($meanings as $index => $meaning) {
            if ($index == $meanings->count() - 1) {
                continue;
            }

            try {
                $description = $meaning->childNodes->item(1)->nodeValue;
                if (preg_match('/[a-z]/i', $description)) {
                    $mean['description'] = $description;
                } else {
                    continue;
                }

                $mean['categories'] = $this->processCategory(
                    $this->xpath->query('.//font//i//span', $meaning)
                );

                $meaning_array[] = $mean;
            } catch (Exception) {
                continue;
            }
        }

        return $meaning_array;
    }

    private function processMeaning(DOMElement $node)
    {
        try {
            $description = $node->childNodes->item(1)->nodeValue;
            if (preg_match('/[a-z]/i', $description)) {
                $mean['description'] = $description;
            } else {
                return [];
            }

            $mean['categories'] = $this->processCategory(
                $this->xpath->query('.//font//i//span', $node)
            );

            $meaning_array[] = $mean;

            return $meaning_array;
        } catch (Exception) {
            return [];
        }
    }

    private function processCategory(DOMNodeList $categories): array
    {
        $category_array = [];
        foreach ($categories as $index => $category) {
            try {
                $cat['code'] = $category->nodeValue;
                $cat['description'] = $category->attributes->getNamedItem('title')->nodeValue;

                $category_array[] = $cat;
            } catch (Exception) {
                continue;
            }
        }

        return $category_array;
    }

    /* @throws KbbiResponseException */
    private function checkError(): void
    {
        $seeError = $this->xpath->query("//h4[contains(@style, 'color:red')]");
        if ($seeError->length > 0) {
            throw new KbbiResponseException(trim($seeError->item(0)->nodeValue));
        }
        throw new KbbiResponseException('Pencarian terkena limit');
    }
}
