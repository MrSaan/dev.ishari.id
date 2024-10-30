<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shalawat extends Model
{
    protected $table = 'arabic_text';
    protected $guarded = [];

    /**
     * @return HasOne
     */
    public function muhud(): HasOne
    {
        return $this->hasOne(Muhud::class, 'id', 'muhud_id');
    }

    /**
     * @return HasMany
     */
    public function audios(): HasMany
    {
        return $this->hasMany(Audio::class, 'arabic_text_id', 'id');
    }

}
