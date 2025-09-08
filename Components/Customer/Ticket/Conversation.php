<?php

namespace Amplify\Widget\Components\Customer\Ticket;

use Amplify\System\Ticket\Exceptions\TicketException;
use Amplify\System\Ticket\Models\TicketThread;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Conversation
 */
class Conversation extends BaseComponent
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
        $id = request()->ticket;

        if (! is_numeric($id)) {
            throw new TicketException('Invalid Ticket Details Id.');
        }

        $threads = customer(true)->ticketThreads;
        $threadMsg = TicketThread::with('participants')->find($id);

        if (! $threadMsg) {
            abort(404, 'Invalid Ticket Details Id.');
        }

        $hasPermission = false;
        $participants = $threadMsg->participants;

        foreach ($participants as $participant) {
            if ($participant->model === "Amplify\System\Backend\Models\Contact" && customer(true)->id == $participant->user_id) {
                $hasPermission = true;
                break;
            }
        }

        if (! $hasPermission) {
            abort(403, 'Ticket Participants Are Not Permitted.');
        }

        return view('widget::customer.ticket.conversation', [
            'threads' => $threads,
            'threadMsg' => $threadMsg,
        ]);
    }
}
