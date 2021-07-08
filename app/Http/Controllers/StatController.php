<?php

namespace App\Http\Controllers;
use App\Stat;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    public function heare(int $id)
    {
        $stat = Stat::find($id);
        if ( $stat ) {
            $stat->time = DB::raw('NOW() - created_at');
            $stat->save();
        }
        exit;
    }


}
