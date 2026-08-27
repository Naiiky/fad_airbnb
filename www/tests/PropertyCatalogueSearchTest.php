<?php

namespace App\Tests;

use App\Catalogue\PropertyCatalogueSearch;
use PHPUnit\Framework\TestCase;

class PropertyCatalogueSearchTest extends TestCase
{
    public function testSearchQueryIsNormalized(): void
    {
        $search = PropertyCatalogueSearch::fromQuery('  Lyon   Centre  ', 'category-id', '3');

        self::assertSame('Lyon Centre', $search->getCity());
        self::assertSame('category-id', $search->getCategoryId());
        self::assertSame(3, $search->getPage());
        self::assertSame(9, $search->getPerPage());
        self::assertSame([
            'page' => '2',
            'ville' => 'Lyon Centre',
            'categorie' => 'category-id',
        ], $search->toQueryParameters(2));
    }

    public function testInvalidQueryFallsBackToFirstPageWithoutEmptyFilters(): void
    {
        $search = PropertyCatalogueSearch::fromQuery('', '', '-20');

        self::assertNull($search->getCity());
        self::assertNull($search->getCategoryId());
        self::assertSame(1, $search->getPage());
        self::assertSame(['page' => '1'], $search->toQueryParameters(0));
    }

    public function testPageIsCapped(): void
    {
        $search = PropertyCatalogueSearch::fromQuery(null, null, '999999');

        self::assertSame(1000, $search->getPage());
    }
}
