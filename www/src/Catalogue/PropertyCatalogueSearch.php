<?php

namespace App\Catalogue;

class PropertyCatalogueSearch
{
    public const PER_PAGE = 9;
    private const MAX_PAGE = 1000;
    private const MAX_CITY_LENGTH = 80;

    public function __construct(
        private readonly ?string $city,
        private readonly ?string $categoryId,
        private readonly int $page,
    ) {
    }

    public static function fromQuery(mixed $city, mixed $categoryId, mixed $page): self
    {
        return new self(
            self::normalizeCity($city),
            self::normalizeCategoryId($categoryId),
            self::normalizePage($page),
        );
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCategoryId(): ?string
    {
        return $this->categoryId;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return self::PER_PAGE;
    }

    /** @return array<string, string> */
    public function toQueryParameters(int $page): array
    {
        $parameters = ['page' => (string) max(1, min(self::MAX_PAGE, $page))];

        if (null !== $this->city) {
            $parameters['ville'] = $this->city;
        }

        if (null !== $this->categoryId) {
            $parameters['categorie'] = $this->categoryId;
        }

        return $parameters;
    }

    private static function normalizeCity(mixed $city): ?string
    {
        if (!\is_string($city)) {
            return null;
        }

        $city = trim(preg_replace('/\s+/', ' ', $city) ?? '');
        if ('' === $city) {
            return null;
        }

        return substr($city, 0, self::MAX_CITY_LENGTH);
    }

    private static function normalizeCategoryId(mixed $categoryId): ?string
    {
        if (!\is_string($categoryId)) {
            return null;
        }

        $categoryId = trim($categoryId);

        return '' === $categoryId ? null : substr($categoryId, 0, 36);
    }

    private static function normalizePage(mixed $page): int
    {
        $page = filter_var($page, FILTER_VALIDATE_INT, [
            'options' => [
                'default' => 1,
                'min_range' => 1,
            ],
        ]);

        return max(1, min(self::MAX_PAGE, (int) $page));
    }
}
