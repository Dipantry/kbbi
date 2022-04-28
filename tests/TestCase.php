<?php

namespace Dipantry\Kbbi\Tests;

use Illuminate\Http\JsonResponse;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getApplicationSession(): string
    {
        return 'A4dHXso6PNUTZzMYZCksSKtR0ynmp0BtOTmR2YQ0LF_H5s87H8hFgmaoQiC9RlZ-c0Xefkj46FS91fN2nQWFYtWp9fJKTJHCYkmEV'.
            'wW3QIoP5Mcv1SuTlt67AKWseWwfsUKHoEvOib4M_i864lKVNk3MglPYvwnHUYpCYacLiKaCJuTrxUW-TNDuZgFVs6gn3ce6h_6mD1cug'.
            'u5bnw0YRQRw8Q_9XNCPFBgNswsi9--BVs6-D3-1AHXMt56P306OQO3DnZvs6owrfgAceugdZINznSziuY0o179bf5J1xwJfTrLC5NIpG'.
            'tTsoqnfo_WihVE-sxsSxaF8-mM-TvrzjVAcpEycT3RBA0aAXjblY7gBdD4eRUmGnCwtxXBtgPgknKEatcv7HRWuaVF8REyJmeXUyT0zY'.
            'ihXUDR6f-orQsHUkcfJ-fAfW288T1b1QLVI0ytp8PKpRaS2AmGBJ3TcQEAQbJZXn8COjpSkIvkH1TG1iVnsPnqxGrMnroCnyiEu8i7nZ'.
            'nnoNgQ-awGTRqaN1IAfuKy86pa2zi-N9AfJG1lWvPbSUObIrxms711HPhVs';
    }

    protected function decodeContent(JsonResponse $json): array
    {
        return json_decode($json->content(), true);
    }
}
