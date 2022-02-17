<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\PMTHelper;

class PMTHelperTest extends TestCase
{
    public function testCalculatePMT()
    {
        $pmt = PMTHelper::calculatePMT(0.1, 10000, 12);

        $this->assertEquals(879.16, round($pmt, 2));
    }

    public function testCalculateInterest()
    {
        $interest = PMTHelper::calculateInterest(0.1, 10000);

        $this->assertEquals(83.33, round($interest, 2));
    }

    public function testCalculatePrinciple()
    {
        $principal = PMTHelper::calculatePrincipal(879.158872, 83.333333);

        $this->assertEquals(795.83, round($principal, 2));
    }
}