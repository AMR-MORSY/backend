<?php

namespace App\Services;

use auth;
use Carbon\Carbon;

class DateFormatter
{
    public function formatToUserTimezone($dateTime, $format = 'Y-m-d H:i:s')
    {
        if (!$dateTime) return null;



        
        // If it's already a Carbon instance, clone it to avoid modifying the original
        if ($dateTime instanceof Carbon) {
            $date = $dateTime->copy();
        } else {
            // Create a Carbon instance with UTC timezone explicitly
            $date = Carbon::parse($dateTime, 'UTC');
        }
        
        // Convert to user's timezone
        $userTimezone = auth()->check() && auth()->user()->timezone 
            ? auth()->user()->timezone 
            : config('app.timezone');
            
        return $date->timezone($userTimezone)->format($format);
    }
} 
