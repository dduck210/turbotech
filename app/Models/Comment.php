<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Product reviews. `UNIQUE(id_user, id_pro)` at the DB level is the real
 * backstop against duplicate reviews from the same buyer — Phase 4 Group B
 * adds a friendlier validation message in front of it, this model doesn't
 * need to re-implement the check itself.
 */
class Comment extends Model
{
    protected $table = 'comment';

    protected $primaryKey = 'id_cmt';

    public $timestamps = false;

    protected $fillable = [
        'content',
        'id_user',
        'user_name',
        'full_name',
        'id_pro',
        'comment_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_pro', 'id_pro');
    }
}