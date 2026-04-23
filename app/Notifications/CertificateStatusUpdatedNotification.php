<?php

namespace App\Notifications;

use App\Models\CertificateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CertificateStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly CertificateRequest $request) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'certificate_request_id' => $this->request->id,
            'status' => $this->request->status,
            'status_label' => ucfirst(str_replace('_', ' ', $this->request->status)),
            'type_name' => $this->request->type_name,
            'message' => "Your {$this->request->type_name} request is now " . ucfirst(str_replace('_', ' ', $this->request->status)) . '.',
            'url' => route('my.requests.show', $this->request),
        ];
    }
}
