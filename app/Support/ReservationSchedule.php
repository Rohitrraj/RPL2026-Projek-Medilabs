<?php

namespace App\Support;

class ReservationSchedule

{

    public static function availableHours(): array

    {

        $hours = [];

        for ($hour = 7; $hour <= 19; $hour++) {

            foreach (['00', '30'] as $minute) {

                if ($hour === 19 && $minute === '30') {

                    continue;

                }

                $hours[] = sprintf('%02d:%s', $hour, $minute);

            }

        }

        return $hours;

    }

}