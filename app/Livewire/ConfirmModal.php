<?php

namespace App\Livewire;

use Livewire\Component;

class ConfirmModal extends Component
{
    public bool $show = false;
    public string $title = '';
    public string $message = '';
    public $onConfirm;
    public $onCancel;

    public function mount()
    {
        $this->show = false;
    }

    public function confirm(): void
    {
        if (is_callable($this->onConfirm)) {
            call_user_func($this->onConfirm);
        }
        $this->resetModal();
    }

    public function cancel(): void
    {
        if (is_callable($this->onCancel)) {
            call_user_func($this->onCancel);
        }
        $this->resetModal();
    }

    private function resetModal(): void
    {
        $this->show = false;
        $this->title = '';
        $this->message = '';
        $this->onConfirm = null;
        $this->onCancel = null;
    }

    public function render()
    {
        return view('livewire.confirm-modal');
    }
}