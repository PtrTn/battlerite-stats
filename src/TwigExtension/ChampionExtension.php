<?php

namespace App\TwigExtension;

use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ChampionExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('champion', [$this, 'formatChampion']),
        ];
    }

    public function formatChampion(string $championId)
    {
        $championMapping = [
            '467463015' => 'Lucie',
            '259914044' => 'Sirius',
            '1649551456' => 'Pestilus',
            '543520739' => 'Blossom',
            '842211418' => 'Iva',
            '613085868' => 'Alysia',
            '1318732017' => 'Rook',
            '65687534' => 'Jade',
            '550061327' => 'Ruh Kaan',
            '1908945514' => 'Oldur',
            '1' => 'Ashka',
            '369797039' => 'Varesh',
            '44962063' => 'Pearl',
            '870711570' => 'Destiny',
            '154382530' => 'Taya',
            '1134478706' => 'Poloma',
            '1208445212' => 'Croak',
            '1606711539' => 'Freya',
            '39373466' => 'Jumong',
            '763360732' => 'Shifu',
            '1377055301' => 'Ezmo',
            '1749055646' => 'Raigon',
            '1463164578' => 'Thorn',
            '1422481252' => 'Bakko'
        ];
        if (!isset($championMapping[$championId])) {
            throw new InvalidArgumentException(sprintf('Champion mapping not found for "%s"', $championId));
        }

        return $championMapping[$championId];
    }
}
