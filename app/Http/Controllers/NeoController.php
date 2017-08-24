<?php

namespace App\Http\Controllers;

use App\Neo;
use Illuminate\Http\Request;

class NeoController extends Controller
{
    /**
     * The default application route '/'.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'hello' => 'world',
        ], 200);
    }

    /**
     * Show a paginated list of all hazardous Neos from the DB.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showHazardous()
    {
        return Neo::whereIsHazardous(true)->paginate(15);
    }

    /**
     * Show the fastest NEO from the DB.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showFastest(Request $request)
    {
        $this->validate($request, ['hazardous' => 'required|boolean']);

        return Neo::whereIsHazardous($request->hazardous)->orderByDesc('speed')->first();
    }

    /**
     * Return a year with most ateroids
     * 
     * NOTE: The Readme Doc only asks to fetch and persist a list of NEOs from 
     * last 3 days in which case, there's no comparison of years. Since the data
     * contains NEOs from the current year. More clarity required.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showBestYear(Request $request)
    {
        /**
         * Inentionally left blank.
         *
         * @see the above docblock
         */
    }

    /**
     * Return a month with most ateroids
     * 
     * NOTE: The Readme Doc only asks to fetch and persist a list of NEOs from 
     * last 3 days in which case, there's no comparison of months. Since the data
     * contains NEOs from the current month. More clarity required.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showBestMonth(Request $request)
    {
        /**
         * Inentionally left blank.
         * 
         * @see the above docblock
         */
    }

}
