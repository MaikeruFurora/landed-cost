<?php

namespace App\View\Components\LandedCost;

use Illuminate\View\Component;

class SackLcdpNego extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $landedCostParticular;
    public $detail;

    public function __construct($landedCostParticular,$detail)
    {
        $this->landedCostParticular = $landedCostParticular;
        $this->detail = $detail;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.landed-cost.sack-lcdp-nego');
    }
}
