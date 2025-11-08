<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
  use HasFactory;

  protected $table = 'organizations';
  protected $primaryKey = 'organization_id';

  protected $fillable = [
    'user_id',
    'organization_name',
    'organization_type',
    'adviser_name',
    'contact_email',
    'contact_number',
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
}
