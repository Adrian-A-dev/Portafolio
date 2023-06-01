<?php

namespace App\Http\Controllers;

use App\Models\lain;
use Illuminate\Http\Request;
use {{ namespacedModel }};

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function index(lain $lain)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function create(lain $lain)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\lain  $lain
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, lain $lain)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\lain  $lain
     * @param  \{{ namespacedModel }}  ${{ modelVariable }}
     * @return \Illuminate\Http\Response
     */
    public function show(lain $lain, {{ model }} ${{ modelVariable }})
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\lain  $lain
     * @param  \{{ namespacedModel }}  ${{ modelVariable }}
     * @return \Illuminate\Http\Response
     */
    public function edit(lain $lain, {{ model }} ${{ modelVariable }})
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\lain  $lain
     * @param  \{{ namespacedModel }}  ${{ modelVariable }}
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, lain $lain, {{ model }} ${{ modelVariable }})
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\lain  $lain
     * @param  \{{ namespacedModel }}  ${{ modelVariable }}
     * @return \Illuminate\Http\Response
     */
    public function destroy(lain $lain, {{ model }} ${{ modelVariable }})
    {
        //
    }
}
