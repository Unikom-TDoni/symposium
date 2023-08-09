<?php

namespace App\Enums;

enum ConferenceSortEnum: string {
    case ALPHA = 'alpha';
    case DATE = 'date';
    case OPENINGNEXT = 'opening_next';
    case CLOSSINGNEXT = 'closing_next';
}