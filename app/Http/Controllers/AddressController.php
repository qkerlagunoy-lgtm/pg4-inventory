<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = []; // empty for now

        return view('admin.addresses.index', compact('addresses'));
    }
}
