<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
  use HasFactory;

  protected $table = 'permits';

  protected $primaryKey = 'permit_id'; // if your PK isn’t "id"

  protected $fillable = [
    'organization_id',
    'title_activity',
    'purpose',
    'type',
    'nature',
    'venue',
    'date_start',
    'date_end',
    'time_start',
    'time_end',
    'participants',
    'number',
    'signature_data',
    'pdf_data'
  ];

  // ✅ The permit belongs to one organization
  public function organization()
  {
    return $this->belongsTo(Organization::class, 'organization_id', 'organization_id');
  }

  // ✅ The permit may be linked to an event (if you create one during generation)
  public function event()
  {
    return $this->hasOne(Event::class, 'organization_id', 'organization_id');
  }
}
