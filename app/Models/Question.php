<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Contact/support form submissions. Not tied to a logged-in account (only
 * name/email/phone are captured), so an admin reply is emailed to the
 * submitted address rather than shown on any "my questions" page — see
 * Phase 4 Group F.
 */
class Question extends Model
{
    protected $table = 'question';

    protected $primaryKey = 'id_ques';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'contennt',
        'reply',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
        ];
    }
}