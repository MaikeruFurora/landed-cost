<?php

namespace App\View\Components\Payment;

use Illuminate\View\Component;

class SearchInvoice extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    
    public $action;
    public $id;
    public $url;

    public function __construct($action,$id,$url)
    {
        $this->action      = $action;
        $this->id          = $id;
        $this->url         = $url;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.payment.search-invoice');
    }
}
