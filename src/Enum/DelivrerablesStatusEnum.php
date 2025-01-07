<?php

declare(strict_types=1);

namespace App\Enum;

enum DelivrerablesStatusEnum: string
{
    case PENDING = 'Pending';
    case IN_PROGRESS = 'In progress';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
}