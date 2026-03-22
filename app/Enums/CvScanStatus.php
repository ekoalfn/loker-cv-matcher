<?php

namespace App\Enums;

enum CvScanStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
}
