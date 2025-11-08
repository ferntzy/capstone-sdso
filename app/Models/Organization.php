<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';
    protected $primaryKey = 'organization_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'organization_name',
        'organization_type',
        'adviser_name',
        'contact_email',
        'contact_number',
        'status',
        'members',
        'description',
    ];

    // 🔗 Link back to the user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔗 Link to events
    public function events()
    {
        return $this->hasMany(Event::class, 'organization_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors — make your Blade variables work naturally
    |--------------------------------------------------------------------------
    */

    // Use $org->name instead of $org->organization_name
    public function getNameAttribute()
    {
        return $this->organization_name;
    }

    // Use $org->type instead of $org->organization_type
    public function getTypeAttribute()
    {
        return $this->organization_type;
    }

    // Use $org->advisor instead of $org->adviser_name
    public function getAdvisorAttribute()
    {
        return $this->adviser_name;
    }

    // Optional: members (default to 0 if null)
    public function getMembersAttribute($value)
    {
        return $value ?? 0;
    }

    // Optional: status (default to "Active" if null)
    public function getStatusAttribute($value)
    {
        return $value ?? 'Active';
    }

    // Optional: description (default empty string)
    public function getDescriptionAttribute($value)
    {
        return $value ?? '';
    }
}
