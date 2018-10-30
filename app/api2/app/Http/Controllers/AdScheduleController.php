<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Ad;

class AdScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @todo Change input to laravel best practices
     * @todo Perform date formatting with Carbon
     */
    public function index()
    {
        date_default_timezone_set('America/Los_Angeles');

        $date           = implode('-', explode('/', $_GET['date']));
        $formatted_date = date('Y-m-d', strtotime($date));
        $unix           = strtotime($formatted_date);
        $parsed_date    = date_parse($formatted_date);

        if ($parsed_date["error_count"] == 0 && checkdate($parsed_date["month"], $parsed_date["day"], $parsed_date["year"])) {
            //Constants (second conversions)
            $one_day = 24*60*60;
            $one_hour = 60*60;
            $one_minute = 60;
    
    
            //Get Day of Week (0-6)
            $day_of_week = date('w', strtotime($date));
            //Get mod 2 of (current unix - time since start of last sunday divided by one week). Then add 1 to get 2||1 instead of 1||0
            $week = (floor((strtotime($date) - intval($day_of_week*$one_day)) /($one_day*7)) % 2) + 1;
    
    
            if ($formatted_date == date('Y-M-d', strtotime('now'))) {
                //Set cutoff time to right now if we are loading today
                $time = date('H:i:s', strtotime('now'));
            } else {
                //Set cutoff time to 00:00:00
                $time = '00:00:00';
            }
    
            //Select active shows that run during the date specified.
            $shows_select = array(
                'shows.id',
                'shows.name',
                'show_times.start_day',
                'show_times.start_time',
                'show_times.end_day',
                'show_times.end_time'
            );
            $shows = Show::selectRaw(implode(', ', $shows_select))
                        ->join('show_times', 'show_times.show_id', '=', 'shows.id')
                        ->where('show_times.start_day', '=', $day_of_week)
                        ->where('show_times.start_time', '>=', $time)
                        ->whereRaw('(show_times.alternating = '.$week.' OR show_times.alternating = 0)')
                        ->where('shows.active', '=', '1')
                        ->orderBy('show_times.start_time', 'ASC')
                        ->get();
    
            //for each show time get the ads, or create them.
            foreach ($shows as $show_time) {
                $start_hour_offset  = date_parse($show_time['start_time'])['hour'] * $one_hour;
                $start_minute_offset= date_parse($show_time['start_time'])['minute'] * $one_minute;
                $start_unix_offset  = $start_hour_offset + $start_minute_offset;
                $end_hour_offset    = date_parse($show_time['end_time'])['hour'] * $one_hour;
                $end_minute_offset  = date_parse($show_time['end_time'])['minute'] * $one_minute;
                $end_unix_offset    = $end_hour_offset + $end_minute_offset;

                if ($show_time['end_day'] != $show_time['start_day']) {
                    $end_unix_offset += $one_day;
                }
    
                $show_time->start_unix = $unix + $start_unix_offset;
                $show_time->end_unix   = $unix + $end_unix_offset;
                $show_time->duration   = $show_time->end_unix - $show_time->start_unix;
    
                $ads = Ad::where('time_block', '=', $show_time->start_unix)->get();
                $show_time->generated = false;
                if (count($ads) == 0) {
                    $show_time->generated = true;
                    $show_time->ads = Ad::generateAds($show_time->start_unix, $show_time->duration, $show_time->id);
                } else {
                    $show_time->ads = $ads;
                }

                $show_time->date  = date('l F jS g:i a', $show_time->start_unix);
                $show_time->start = date('g:i a', $show_time->start_unix);
            }
            return Response::json($shows);
        } else {
            http_response_code('400');
            return "Not a Valid Date: {$formatted_date}";
        }
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
        $posts = array();
        parse_str($request->input('ads'), $posts);
    
        if (array_key_exists('show', $posts)) {
            foreach ($posts['show'] as $ad) {
                if (!empty($ad['id'])) {
                    $ad_model = Ad::find($ad['id']);
                    unset($ad['id']);

                    $ad_model->update($ad);
                } else {
                    $ad_model = Ad::create($ad);
                }
                $ads[] = $ad_model;
            }
        } else {
            $ads = 'No Results Found';
        }

        return response()->json($ads);
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
