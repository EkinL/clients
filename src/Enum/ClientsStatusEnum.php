<?php

declare(strict_types=1);

namespace App\Enum;

enum ClientsStatusEnum: string
{
    case NOT_STARTED = 'Not started';
    case IN_PROGRESS = 'In progress';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
}
