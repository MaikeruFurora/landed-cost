<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $code;
    public $landedCostParticular;
    public $mt;
    public $lcoc;

    public function __construct($code,$landedCostParticular,$mt,$lcoc)
    {
        $this->code = $code;
        $this->landedCostParticular = $landedCostParticular;
        $this->mt = $mt;
        $this->lcoc = $lcoc;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal-form');
    }
}
