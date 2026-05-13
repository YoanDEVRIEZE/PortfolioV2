<?php

namespace App\Enum;

enum CareerStatus: string
{
    case PermanentContract = 'permanent_contract';
    case FixedTermContract = 'fixed_term_contract';
    case Apprenticeship = 'apprenticeship';
    case Internship = 'internship';
    case Freelance = 'freelance';

    public function translationKey(): string
    {
        return match ($this) {
            self::PermanentContract => 'career_status.permanent_contract',
            self::FixedTermContract => 'career_status.fixed_term_contract',
            self::Apprenticeship => 'career_status.apprenticeship',
            self::Internship => 'career_status.internship',
            self::Freelance => 'career_status.freelance',
        };
    }
}
