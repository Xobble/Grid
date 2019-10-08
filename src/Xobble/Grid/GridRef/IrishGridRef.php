<?php

namespace Xobble\Grid\GridRef;

use Xobble\Grid\GridRef;
use Xobble\Grid\Cartesian;

class IrishGridRef implements GridRef
{
    /** @var GridRefHelper */
    protected $helper;

    /** @var array */
    protected $options;

    public function __construct(array $options = [])
    {
        $this->helper = new GridRefHelper($this->getDatum(), 500000, 1);

        $this->options = array_merge([
            'allowed_references' => null,
            'grid_exclude_channel_islands' => true,
        ], $options);

        $this->options['allowed_references'] = $this->helper->processAllowedReferences($this->options['allowed_references']);
    }

    public function supportsGridRef(string $gridRef) : bool
    {
        if (!preg_match('/^[A-HJ-Z](?P<number>[ \d]*)$/', $gridRef, $matches)) {
            return false;
        }

        $prefix = $gridRef[0];

        if ($this->options['allowed_references'] && !isset($this->options['allowed_references'][$prefix])) {
            return false;
        }

        $number = str_replace(' ', '', $matches['number']);
        return (strlen($number) % 2 === 0);
    }

    public function supportsCartesian(Cartesian $cartesian) : bool
    {
        return $cartesian->getDatum() === $this->getDatum();
    }

    public function toCartesian(string $gridRef) : Cartesian
    {
        return $this->helper->toCartesian($gridRef);
    }

    public function toGridRef(Cartesian $cartesian) : string
    {
        return $this->helper->toGridRef($cartesian);
    }

    public function getGridReferenceName() : string {
        return 'Irish Grid';
    }

    public function getDatum() : string
    {
        return 'EPSG:29902';
    }
}