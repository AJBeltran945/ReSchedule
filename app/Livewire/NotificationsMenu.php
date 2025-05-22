<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationsMenu extends Component
{
    public $open = false;

    protected $listeners = ['notificationRead' => '$refresh'];

    public function markAsRead($id)
    {
        auth()->user()->notifications()->find($id)?->markAsRead();
        $this->emit('notificationRead');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->emit('notificationRead');
    }

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function render()
    {
        return view('livewire.notifications-menu', [
            'notifications' => auth()->user()->notifications()->latest()->take(10)->get(),
            'unreadCount' => auth()->user()->unreadNotifications->count(),
        ]);
    }
}
