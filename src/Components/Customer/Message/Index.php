<?php

namespace Amplify\Widget\Components\Customer\Message;

use Amplify\System\Message\Models\MessageThread;
use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class Index
 */
class Index extends BaseComponent
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
        $threads = customer(true)->threads;
        $threadMsg = null;

        if (request()->message) {
            $thread_id = request()->message;
            $threadMsg = MessageThread::findOrFail($thread_id);
            $hasPermission = $threadMsg->participants()->where('model', get_class(customer(true)))->exists();

            abort_unless($hasPermission, 404);

            customer(true)->markThreadAsRead($thread_id);
        }

        return view('widget::customer.message.index', [
            'threads' => $threads,
            'threadMsg' => $threadMsg,
        ]);
    }
}
