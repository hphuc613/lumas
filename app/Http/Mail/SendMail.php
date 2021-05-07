<?php

namespace App\Http\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $body;

    /**
     * @param string $body
     * @return $this
     */
    public function body(string $body): SendMail
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function title(string $title): SendMail
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this;
    }


}
