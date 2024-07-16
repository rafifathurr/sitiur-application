<?php

namespace App\Models\Archieve;

use App\Models\Master\Classification;
use App\Models\Master\Institution;
use App\Models\Master\TypeMailContent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingMail extends Model
{
    use HasFactory;
    protected $table = 'outgoing_mail';
    protected $guarded = [];

    public function institution()
    {
        return $this->hasOne(Institution::class, 'id', 'institution_id');
    }

    public function classification()
    {
        return $this->hasOne(Classification::class, 'id', 'classification_id');
    }

    public function typeMailContent()
    {
        return $this->hasOne(TypeMailContent::class, 'id', 'type_mail_content_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function deletedBy()
    {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }
}
