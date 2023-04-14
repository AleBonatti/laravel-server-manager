<?php

namespace App\Http\Controllers;

use App\Models\Database;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $items = Database::orderBy('name')
            ->when($request->name, function(Builder $query, $name)  {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($request->filled('active'), function(Builder $query) use($request) {
                $query->where('active', $request->active);
            })
            ->get();

        return inertia('Database', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        sleep(1);

        $request->validate([
            'name'=>'required',
            'active'=>'required'
        ]);

        $item = new Database();
        $item->name = $request->name;
        $item->extra_commands = $request->extra_commands;
        $item->active = $request->active;
        $item->save();

        return redirect('/admin/database');
    }

    /**
     * Display the specified resource.
     */
    public function show(Database $database)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Database $database)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Database $database)
    {
        sleep(1);

        $request->validate([
            'name'=>'required',
            'active'=>'required'
        ]);

        //$item = new Database();
        $database->name = $request->name;
        $database->extra_commands = $request->extra_commands;
        $database->active = $request->active;
        $database->save();

        return redirect('/admin/database');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        dd($id);
    }
}
