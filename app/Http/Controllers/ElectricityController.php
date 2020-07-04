<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Myckhel\Vtpass\Support\Electric;

class ElectricityController extends Controller
{
  public function verify(Request $request)
  {
    $request->validate([
      'serviceID' => 'required|in:eko-electric',
      'type'      => 'required|in:postpaid,prepaid',
      'meter'     => 'required|int:5,20',
    ]);

    $serviceID      = $request->serviceID;
    $type           = $request->type;
    $meter          = $request->meter;

    return Electric::verify([
      'serviceID'   => $serviceID,
      'billersCode' => $meter,
      'type'        => $type,
    ]);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
