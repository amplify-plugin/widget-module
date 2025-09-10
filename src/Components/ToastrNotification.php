<?php

namespace Amplify\Widget\Components;

use Amplify\Widget\Abstracts\BaseComponent;
use Closure;
use Illuminate\Contracts\View\View;

/**
 * @class ToastrNotification
 */
class ToastrNotification extends BaseComponent
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
        $level = null;
        $title = ' ';
        $message = null;

        if (session()->has('success')) {
            $level = 'success';
            $title = 'Congratulations!';
            $message = session('success');
        } elseif (session()->has('warning')) {
            $level = 'warning';
            $title = 'Warning!';
            $message = session('warning');
        } elseif (session()->has('error')) {
            $level = 'error';
            $title = 'Sorry!';
            $message = session('error');
        }

        return view('widget::toastr-notification', compact('title', 'level', 'message'));
    }
}
