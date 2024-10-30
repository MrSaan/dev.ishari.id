<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Audio extends Model
{
    protected $table = 'audio';
    protected $guarded = [];

    /**
     * @return HasOne
     */
    public function pimpinan(): HasOne
    {
        return $this->hasOne(Person::class, 'id', 'person_id');
    }
}
