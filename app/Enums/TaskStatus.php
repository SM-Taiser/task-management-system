<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TODO = 'Todo';
    case COMPLETED = 'Complete';
    case INCOMPLETE = 'Incomplete';
}
