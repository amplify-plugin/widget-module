<?php

namespace Amplify\Widget\Components\Customer\Ticket;

use Amplify\System\Ticket\Models\Ticket;
use Amplify\System\Ticket\Models\TicketDepartment;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Create
 */
class Create extends BaseComponent
{
    /**
     * @var array
     */
    public $options;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Whether the component should be rendered
     */
    public function shouldRender(): bool
    {
        return true;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $ticket_departments = TicketDepartment::all();
        $priorities = Ticket::PRIORITY;

        return view('widget::customer.ticket.create', [
            'ticket_departments' => $ticket_departments,
            'priorities' => $priorities,
        ]);
    }
}
