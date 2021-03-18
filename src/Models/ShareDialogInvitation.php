<?php

namespace Geekyants\ShareDialog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareDialogInvitation extends Model
{
    use HasFactory;
    protected $fillable = [
        'entity_name', 'invitation_token', 'entity_id', 'ability'
    ];
    public function generateInvitationToken()
    {
        $this->invitation_token = substr(md5(rand(0, 9) . $this->email . time()), 0, 32);
    }
}
