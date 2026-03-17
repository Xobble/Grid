<?php

namespace Xobble\Grid\GridRef;

use Xobble\Grid\Exception\UnsupportedRefException;
use Xobble\Grid\GridRef;
use Xobble\Grid\Cartesian;

class BritishGridRef implements GridRef
{
    private const DEFAULT_ALLOWED_REFERENCES = [
        'HP', 'HT', 'HU', 'HW', 'HX', 'HY', 'HZ', 'NA', 'NB', 'NC', 'ND', 'NF', 'NG', 'NH', 'NJ', 'NK', 'NL',
        'NM', 'NN', 'NO', 'NR', 'NS', 'NT', 'NU', 'NW', 'NX', 'NY', 'NZ', 'OV', 'SC', 'SD', 'SE', 'TA', 'SH',
        'SJ', 'SK', 'TF', 'TG', 'SM', 'SN', 'SO', 'SP', 'TL', 'TM', 'SR', 'SS', 'ST', 'SU', 'TQ', 'TR', 'SV',
        'SW', 'SX', 'SY', 'SZ', 'TV',
    ];

    /** @var GridRefHelper */
    protected GridRefHelper $helper;

    /** @var array{allowed_references: ?array<string, string>, grid_exclude_channel_islands: bool} */
    protected array $options;

    /**
     * @param array{allowed_references?: list<string>|null, grid_exclude_channel_islands?: bool} $options
     */
    public function __construct(array $options = [])
    {
        $this->helper = new GridRefHelper($this->getDatum(), 2500000, 2, -1000000, -500000);

        $mergedOptions = array_merge([
            'allowed_references' => self::DEFAULT_ALLOWED_REFERENCES,
            'grid_exclude_channel_islands' => true,
        ], $options);

        $this->options = [
            'allowed_references' => $this->helper->processAllowedReferences($mergedOptions['allowed_references']),
            'grid_exclude_channel_islands' => $mergedOptions['grid_exclude_channel_islands'],
        ];
    }

    public function toCartesian(string $gridRef): Cartesian
    {
        if (!$this->supportsGridRef($gridRef)) {
            throw new UnsupportedRefException();
        }

        return $this->helper->toCartesian($gridRef);
    }

    public function toGridRef(Cartesian $cartesian): string
    {
        if ($cartesian->getDatum() !== $this->getDatum()) {
            throw new UnsupportedRefException();
        }

        return $this->helper->toGridRef($cartesian);
    }

    public function getGridReferenceName(): string
    {
        return 'British National Grid';
    }

    public function getDatum(): int
    {
        return 27700;
    }

    protected function supportsGridRef(string $gridRef): bool
    {
        if (!preg_match('/^(?P<prefix>[A-HJ-Z]{2})(?P<number>[ \d]*)$/', $gridRef, $matches)) {
            return false;
        }

        $prefix = $matches['prefix'];
        $number = str_replace(' ', '', $matches['number']);

        if ($this->options['grid_exclude_channel_islands'] && ($prefix === 'WA' || $prefix === 'WV')) {
            return false;
        }

        if ($this->options['allowed_references'] !== null && !isset($this->options['allowed_references'][$prefix])) {
            return false;
        }

        return (strlen($number) % 2 === 0);
    }
}
