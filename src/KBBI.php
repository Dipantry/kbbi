<?php

namespace Dipantry\Kbbi;

use Dipantry\Kbbi\Exception\KbbiResponseException;
use Dipantry\Kbbi\Util\SearchWord;
use Exception;
use Illuminate\Http\JsonResponse;
use Rigsto\ApiHttpStatus\ApiResources;
use Rigsto\ApiHttpStatus\HttpStatus;

class KBBI extends SearchWord
{
    public function search(string $word): JsonResponse
    {
        try {
            $response = $this->searchWord($word);
        } catch (KbbiResponseException $e){
            return ApiResources::generateResponse(
                HttpStatus::BAD_REQUEST,
                $e->getMessage()
            );
        } catch (Exception){
            return ApiResources::generateResponse(
                HttpStatus::INTERNAL_SERVER_ERROR,
                'Internal Server Error'
            );
        }

        return ApiResources::generateResponse(
            HttpStatus::OK,
            'Search word success',
            $response
        );
    }
}