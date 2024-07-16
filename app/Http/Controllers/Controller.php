<?php

namespace App\Http\Controllers;

use App\Models\Master\Institution;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getInstitution(int $level, int $global)
    {
        if ($global == 1) {
            $data['levels'] = Institution::getLevel();
            $data['institutions'] = Institution::whereNull('deleted_at')->where('level', $level)->get();
            return view('includes.global.institution_form', $data);
        } else {
            $data['levels'] = Institution::getLevel();
            $data['institutions'] = Institution::whereNull('deleted_at')->where('level', $level)->get();
            return view('includes.institution.form', $data);
        }
    }
}
