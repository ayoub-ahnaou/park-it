<?php

use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Log::info('Checking for expired reservations...');
    $reservations = Reservation::where("end_date", "<", now())->where("status", "active")->with("parking")->get();

    foreach ($reservations as $reservation) {
        $reservation->update(["status" => "expired"]);
        $reservation->parking->places_disponible++;
        $reservation->parking->save();
    }

    Log::info('Updated reservations to expired.');
})->everyThirtyMinutes();

// https://grok.com/share/bGVnYWN5_bd3ea5e2-4d3c-4933-86eb-b60f7eae8d54