<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;
    protected $table = 'institution';
    protected $guarded = [];

    const LEVEL_1 = 1;
    const LEVEL_2 = 2;
    const LEVEL_3 = 3;
    const LEVEL_4 = 4;

    public static function getLevel()
    {
        return [
            [
                'level' => 1,
                'name' => 'Tingkat Mabes',
            ],
            [
                'level' => 2,
                'name' => 'Tingkat Polda',
            ],
            [
                'level' => 3,
                'name' => 'Tingkat Polres',
            ],
            [
                'level' => 4,
                'name' => 'Tingkat Polsek',
            ],
        ];
    }

    public function parent()
    {
        return $this->hasOne(Institution::class, 'id', 'parent_id');
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
