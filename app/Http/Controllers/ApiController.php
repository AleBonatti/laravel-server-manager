<?php

namespace App\Http\Controllers;

use App\Models\Database;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function delete(Request $request, $element)
    {
        if($element==='database') {
            foreach($request->all() as $id) {
                Database::findOrFail($id)->delete();
            }
            
            return redirect('/admin/database');
        }
        
        abort(404);
    }
}
