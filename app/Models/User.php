<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contact> $contacts
 * @property-read int|null $contacts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @mixin \Eloquent
 */
class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'username',
        'password',
        'name'
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'user_id', 'id');
    }
}
