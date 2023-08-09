<?php

namespace App\View\Components\landedCost;

use Illuminate\View\Component;

class DetailForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $detail;

    public $companies;

    public function __construct($detail,$companies)
    {
        $this->detail = $detail;

        $this->companies = $companies;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.landed-cost.detail-form');
    }
}
