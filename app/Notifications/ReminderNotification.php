<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReminderNotification extends Notification
{
    use Queueable;

    protected $pesan;

    public function __construct($pesan)
    {
        $this->pesan = $pesan;
    }

    // Tentukan channel notifikasi: database
    public function via($notifiable)
    {
        return ['database'];
    }

    // Data yang disimpan di tabel user_notifications
    public function toDatabase($notifiable)
    {
        return [
            'pesan' => $this->pesan,
        ];
    }
}
