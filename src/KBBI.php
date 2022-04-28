<?php

namespace Dipantry\Kbbi;

use Dipantry\Kbbi\Exception\KbbiResponseException;
use Dipantry\Kbbi\Util\SearchWord;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Rigsto\ApiHttpStatus\ApiResources;
use Rigsto\ApiHttpStatus\HttpStatus;

class KBBI extends SearchWord
{
    public function search(string $word, string $session = ''): JsonResponse
    {
        try {
            $response = $this->searchWord($word, $session);
        } catch (KbbiResponseException $e) {
            return ApiResources::generateResponse(
                HttpStatus::BAD_REQUEST,
                $e->getMessage()
            );
        } catch (GuzzleException $e) {
            return ApiResources::generateResponse(
                HttpStatus::BAD_REQUEST,
                'Request Error',
                $e->getMessage()
            );
        } catch (Exception $e) {
            return ApiResources::generateResponse(
                HttpStatus::INTERNAL_SERVER_ERROR,
                'Internal Server Error',
                $e->getMessage()
            );
        }

        return ApiResources::generateResponse(
            HttpStatus::OK,
            'Search word success',
            $response
        );
    }
}
