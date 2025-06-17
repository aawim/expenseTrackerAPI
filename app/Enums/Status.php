<?php

namespace App\Enums;

enum Status: string
{
    case AVAILABLE = 'Available';
    case BOOKED = 'Booked';
    case CONFIRMED = 'Confirmed';
}