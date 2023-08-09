<?php

namespace App\Enums;

enum ConferenceFilterEnum: string {
    case ALL = 'all';
    case FUTURE = 'future';
    case FAVORITE = 'favorites';
    case DISMISSED = 'dismissed';
    case OPENCFC = 'open_cfp';
    case UNCLOSEDCFP = 'unclosed_cfp';
}