<?php

namespace App\Traits;

use Carbon\Carbon;

trait ValidationTrait
{

    /**
     * Validate date range is unique and does not
     * overlap other date ranges
     * 
     * For Two Date ranges A and B
     * Formula : StartA <= EndB && EndA >= StartB
     * 
     * If the function returns true, date range is unique
     * else it is not
     *
     * @param  string $startDate
     * @param  string $endDate
     * @param string $model
     * @return bool
     */
    private function validateDateRange($startDate, $endDate, $model): bool
    {
        $periods = $model::all();

        if (count($periods) < 1) {
            return true;
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

        foreach ($periods as $period) {

            $periodStartDate =  Carbon::createFromFormat('Y-m-d', $period->start_date);
            $periodEndDate =  Carbon::createFromFormat('Y-m-d', $period->end_date);

            if ($periodStartDate->lessThanOrEqualTo($endDate) && $periodEndDate->greaterThanOrEqualTo($startDate)) {
                return false;
            }
        }

        return true;
    }
}
