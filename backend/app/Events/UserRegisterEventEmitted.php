<?php
 
namespace App\Events;

use Illuminate\Queue\SerializesModels;
 
class UserRegisterEventEmitted
{
    use SerializesModels;

    public array $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

}