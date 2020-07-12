<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Mark;
use Hash;
use JWTAuth;

class ApiMarkController extends Controller
{

    /**
     * Get marks.
     *
     * @param int $exam_id
     * @param int $user_id
     * @return object $mark
     */		
    public function getMarks($exam_id,$user_id)
    {   
		$mark = Mark::where('exam_id',$exam_id)->where('user_id',$user_id)->first();
		return response()->json($mark);
    }
}
