<?php
 
namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
 
class UserRegisterEventEmitted
{
    use SerializesModels;

    public User $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

}