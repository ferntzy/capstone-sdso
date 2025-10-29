<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
  use HasFactory;

  protected $primaryKey = 'event_id';

  protected $fillable = [
    'organization_id',
    'event_title',
    'event_date',
    'proposal_status',
  ];

  public function approvals()
  {
    return $this->hasMany(EventApproval::class, 'event_id', 'event_id');
  }

  public function organization()
  {
    return $this->belongsTo(Organization::class, 'organization_id', 'organization_id');
  }
}
