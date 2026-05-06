<?php
namespace Modules\Users\Http\Controllers;
use App\Http\Controllers\Controller;

class UsersController extends Controller {
    public function index() {          
        return view('Users::pages.staff.index'); 
    }
    public function create() { return view('Users::pages.staff.create'); }
    public function edit($id) { return view('Users::pages.staff.edit', compact('id')); }
}
