<?php

namespace App\View\Components\LandedCost;

use Illuminate\View\Component;

class landedCostInput extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $landedCostParticular;
    public $detail;
    public $companies;

    public function __construct($landedCostParticular,$detail,$companies)
    {
        $this->landedCostParticular=$landedCostParticular;
        $this->detail=$detail;
        $this->companies=$companies;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.landed-cost.landed-cost-input');
    }
}
