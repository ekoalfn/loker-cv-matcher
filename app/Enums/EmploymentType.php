<?php

namespace App\Enums;

enum EmploymentType: string
{
    case FullTime = 'full-time';
    case PartTime = 'part-time';
    case Contract = 'contract';
    case Freelance = 'freelance';
    case Internship = 'internship';
}
