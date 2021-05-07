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
     * @param object $ignore
     * @return bool
     */
    private function validateDateRange($startDate, $endDate, $model, $ignore = null): bool
    {
        $records = $model::all();

        if (count($records) < 1) {
            return true;
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

        foreach ($records as $record) {

            $recordStartDate =  Carbon::createFromFormat('Y-m-d', $record->start_date);
            $recordEndDate =  Carbon::createFromFormat('Y-m-d', $record->end_date);

            if ($recordStartDate->lessThanOrEqualTo($endDate) && $recordEndDate->greaterThanOrEqualTo($startDate)) {

                //ignore given record by returning true if it overlaps itself
                if ($ignore != null) {
                    if ($record->id == $ignore->id) {
                        return true;
                    }
                }

                return false;
            }
        }

        return true;
    }
}
