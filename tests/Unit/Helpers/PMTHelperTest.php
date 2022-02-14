<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use App\Helpers\PMTHelper;

class PMTHelperTest extends TestCase
{
    public function testCalculate_pmt_expectResultCorrect()
    {
        $pmt = PMTHelper::calculatePMT(10, 10000, 1);

        $this->assertEquals(879.16, round($pmt, 2));
    }

    public function testCalculate_interest_expectResultCorrect()
    {
        $interest = PMTHelper::calculateInterest(10, 10000);

        $this->assertEquals(83.33, round($interest, 2));
    }

    public function testCalculate_principle_expectResultCorrect()
    {
        $pmt = PMTHelper::calculatePMT(10, 10000, 1);
        $interest = PMTHelper::calculateInterest(10, 10000);
        $principal = PMTHelper::calculatePrincipal($pmt, $interest);

        $this->assertEquals(795.83, round($principal, 2));
    }
}