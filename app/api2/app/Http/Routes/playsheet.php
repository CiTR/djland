<?php
//Playsheet related classes
use App\Playsheet as Playsheet;
use App\Show as Show;
use App\Playitem as Playitem;
use App\Ad as Ad;
use App\Socan as Socan;
use App\Podcast as Podcast;
//Assisting Classes
use App\Member as Member;
#use Illluminate\Support\Facades\Log as Log;
use Illuminate\Support\Facades\Log;
#Log::info('Playsheet Routes Loaded');

/* Playsheet Routes */


Route::group(array('prefix' => 'playsheet'), function () {

  //Get: Return List of Playsheets descending by date updated.
  Route::get('/', function () {
    return Playsheet::orderBy('EDITED_AT', 'desc')->select('id', 'EDITED_AT');
  });
  //Create a new playsheet
  Route::put('/', function () {
    return Playsheet::create((array) Input::get()['playsheet']);
  });
  Route::post('/report', function () {
    // later: could replace this function with Performant version at bottom of page.

    include_once (dirname($_SERVER['DOCUMENT_ROOT']) . "/config.php");
    include_once ($_SERVER['DOCUMENT_ROOT'] . "/headers/session_header.php");
    //Get input variables and make sure they are set, otherwise abort with 400.
    $member_id = isset ($_SESSION['sv_id']) ? $_SESSION['sv_id'] : null;
    if ($member_id == null)
      return Response::json('You are not logged in');

    $from = isset (Input::get()['from']) ? str_replace('/', '-', Input::get()['from']) : null;
    $to = isset (Input::get()['to']) ? str_replace('/', '-', Input::get()['to']) : null;
    if ($from == null || $to == null)
      return Response::json("Not a valid range");

    $show_id = isset (Input::get()['show_id']) ? Input::get()['show_id'] : null;
    if ($show_id == null)
      return Response::json("Not a valid show id");

    $report_type = isset (Input::get()['report_type']) ? Input::get()['report_type'] : null;
    if ($report_type == null)
      return Response::json("No report type specified");


    //Initialize array for playsheets
    $playsheets = array ();
    $playsheet_totals = array ();

    //If the member is staff or admin, the report should be for all shows
    $permissions = Member::find($member_id)->user->permission;
    if ($permissions->staff == 1 || $permissions->administrator == 1) {
      $shows = Show::all();
    } else {
      $shows = Member::find($member_id)->shows;
    }
    //For each show available to the request user, get the playsheets for the period that match the specified show ID, or return all.
    $shows->load([
      'playsheets' => function ($query) use ($from, $to, $report_type) {
        $from_dt_string = $from . ($report_type == 'crtc' ? " 06:00:00" : " 00:00:00");

        $query->orderBy('start_time', 'asc')
          ->where('start_time', '>=', $from_dt_string)
          ->where('start_time', '<=', $to . " 23:59:59");
      }
    ]);

    foreach ($shows as $show) {
      if ($show_id == "all" || $show_id == $show['id']) {
        // $ps = Show::find($show['id'])->playsheets()->orderBy('start_time','asc')->where('start_time','>=',$from.($report_type=='crtc'? " 06:00:00":" 00:00:00"))->where('start_time','<=',$to." 23:59:59")->get();
        $ps = $show->playsheets;
        $show->playsheets->load('ads');
        foreach ($ps as $sheet) {
          $playsheets[] = $sheet;
        }
      }
    }
    //Initialize overall totals
    $totals = new stdClass();
    $totals->total = 0;
    $totals->cc_20_total = 0;
    $totals->cc_20_count = 0;
    $totals->cc_30_total = 0;
    $totals->cc_30_count = 0;
    $totals->fairplay_count = 0;
    $totals->accesscon_count = 0;
    $totals->afrocon_count = 0;
    $totals->femcon_count = 0;
    $totals->indigicon_count = 0;
    $totals->poccon_count = 0;
    $totals->queercon_count = 0;
    $totals->is_local_count = 0;
    $totals->hit_count = 0;
    $totals->new_count = 0;
    $totals->spokenword = 0;
    $totals->ads = 0;

    //create show_totals array
    $show_totals = array ();
    //get totals for each playsheet
    foreach ($playsheets as $p) {
      $playsheet = $p;
      $playsheet->playitems = $p->playitems;
      $playsheet->show = $p->show;
      $playsheet->socan = $p->is_socan();
      $playsheet->ads = $p->ads;

      //initialize this playsheet's totals
      $playsheet->totals = new stdClass();
      $playsheet->totals->total = 0;
      $playsheet->totals->cc_20_total = 0;
      $playsheet->totals->cc_20_count = 0;
      $playsheet->totals->cc_30_total = 0;
      $playsheet->totals->cc_30_count = 0;
      $playsheet->totals->fairplay_count = 0;
      $playsheet->totals->accesscon_count = 0;
      $playsheet->totals->afrocon_count = 0;
      $playsheet->totals->femcon_count = 0;
      $playsheet->totals->indigicon_count = 0;
      $playsheet->totals->poccon_count = 0;
      $playsheet->totals->queercon_count = 0;
      $playsheet->totals->is_local_count = 0;
      $playsheet->totals->hit_count = 0;
      $playsheet->totals->new_count = 0;
      $playsheet->totals->spokenword = 0;
      $playsheet->totals->ads = 0;
      $playsheet->ads_played = [];

      //If this show hasn't been seen before, initialize it
      if (!isset ($show_totals[$playsheet->show_name])) {
        $show_totals[$playsheet->show['name']] = new stdClass();
        $show_totals[$playsheet->show['name']]->total = 0;
        $show_totals[$playsheet->show['name']]->cc_20_total = 0;
        $show_totals[$playsheet->show['name']]->cc_20_count = 0;
        $show_totals[$playsheet->show['name']]->cc_20_req = $playsheet->show->cc_20_req;
        $show_totals[$playsheet->show['name']]->cc_30_total = 0;
        $show_totals[$playsheet->show['name']]->cc_30_count = 0;
        $show_totals[$playsheet->show['name']]->cc_30_req = $playsheet->show->cc_30_req;
        $show_totals[$playsheet->show['name']]->fem_req = $playsheet->show->fem_req;
        $show_totals[$playsheet->show['name']]->fairplay_count = 0;
        $show_totals[$playsheet->show['name']]->accesscon_count = 0;
        $show_totals[$playsheet->show['name']]->afrocon_count = 0;
        $show_totals[$playsheet->show['name']]->femcon_count = 0;
        $show_totals[$playsheet->show['name']]->indigicon_count = 0;
        $show_totals[$playsheet->show['name']]->poccon_count = 0;
        $show_totals[$playsheet->show['name']]->queercon_count = 0;
        $show_totals[$playsheet->show['name']]->is_local_count = 0;
        $show_totals[$playsheet->show['name']]->hit_count = 0;
        $show_totals[$playsheet->show['name']]->new_count = 0;
        $show_totals[$playsheet->show['name']]->spokenword = 0;
        $show_totals[$playsheet->show['name']]->ads = 0;
        $show_totals[$playsheet->show['name']]->show = $playsheet->show;
      }
      foreach ($playsheet->playitems as $playitem) {
        $playsheet->totals->total++;
        //Cat 20 and 30
        if ($playitem['crtc_category'] == '20') {
          $playsheet->totals->cc_20_total++;
          if ($playitem['is_canadian'] == '1')
            $playsheet->totals->cc_20_count++;
        } else {
          $playsheet->totals->cc_30_total++;
          if ($playitem['is_canadian'] == '1')
            $playsheet->totals->cc_30_count++;
        }
        //Fairplay
        if ($playitem->is_fairplay == '1') {
          $playsheet->totals->fairplay_count++;
          $playitem['is_fairplay'] = 1;
        }
        //Accesscon
        if ($playitem['is_accesscon'] == '1')
          $playsheet->totals->accesscon_count++;
        //Afrocon
        if ($playitem['is_afrocon'] == '1')
          $playsheet->totals->afrocon_count++;
        //Femcon
        if ($playitem['is_fem'] == '1')
          $playsheet->totals->femcon_count++;
        //Indigicon
        if ($playitem['is_indigicon'] == '1')
          $playsheet->totals->indigicon_count++;
        //Poccon
        if ($playitem['is_poccon'] == '1')
          $playsheet->totals->poccon_count++;
        //Queercon
        if ($playitem['is_queercon'] == '1')
          $playsheet->totals->queercon_count++;
        //Local
        if ($playitem['is_local'] == '1')
          $playsheet->totals->is_local_count++;
        //Hit
        if ($playitem['is_hit'] == '1')
          $playsheet->totals->hit_count++;
        //New
        if ($playitem['is_new'] == '1')
          $playsheet->totals->new_count++;

        //return Response::json($playsheet->totals);
      }
      $playsheet->totals->spokenword = $playsheet->spokenword_duration;
      $playsheet_totals[] = $playsheet;
      //Update corresponding show totals, and overall
      foreach ($playsheet->totals as $key => $item) {
        $show_totals[$playsheet->show['name']]->$key += $item;
        $totals->$key += $item;
      }
    }
    usort($playsheet_totals, function ($a, $b) {
      $s1 = strtotime($a['start_time']);
      $s2 = strtotime($b['start_time']);
      return $s1 - $s2;
    });
    return Response::json(array ('playsheets' => $playsheet_totals, 'totals' => $totals, 'show_totals' => $show_totals));

  });

  Route::group(array ('prefix' => '{id}'), function ($id = id) {
    //Update Playsheet Information
    Route::post('/', function ($id) {
      return Playsheet::find($id)->update((array) Input::get()['playsheet']);
    });
    Route::group(array ('prefix' => 'playitem'), function ($id) {
      //Add a playitem to the playsheet
      Route::put('/', function ($id) {
        try {
          return Playitem::create((array) Input::get()['playitem']);
        } catch (Exception $e){
          $message = $e->getMessage();
          $error_response = array ('message' => $message, 'playitem' => $id);
          return Response::json($error_response, 400);
        }
      });
    });
  });

  Route::get('/', function () {
    return $playsheets = Playsheet::orderBy('id', 'desc')->select('id')->get();
  });

  Route::post('/', function () {
    $ps = Playsheet::create(Input::get()['playsheet']);
    $podcast_in = Input::get()['podcast'];
    $podcast_in['playsheet_id'] = $ps->id;
    $podcast_in['title'] = $ps->title;
    $podcast_in['subtitle'] = $ps->summary;
    $podcast = Podcast::create($podcast_in);

    $show = $ps->show()->first();
    $show->last_show = $ps->create_date;
    $show->save();

    foreach (Input::get()['ads'] as $ad) {
      $ad['playsheet_id'] = $ps->id;
      if (isset ($ad['id'])) {
        $a = Ad::find($ad['id']);
        unset ($ad['id']);
        $response['ads'][] = $a->update((array) $ad);
      } else {
        $response['ads'][] = Ad::create((array) $ad);
      }
    }

    foreach (Input::get()['playitems'] as $playitem) {
      $playitem['playsheet_id'] = $ps->id;
      try {
        Playitem::create($playitem);
      } catch (Exception $e){
        $message = $e->getMessage();
        $error_response = array ('message' => $message, 'playitem' => $playitem);
        return Response::json($error_response, 400);
      }
    }

    $response['id'] = $ps->id;
    $response['podcast_id'] = $podcast->id;
    file_put_contents("/tmp/djland-sync-wp", "");
    return Response::json($response);
  });

  //Searching by Playsheet ID
  Route::group(array ('prefix' => '{id}'), function ($id = id) {
    //Get Existing Playsheet
    Route::get('/', function ($id) {
      require_once (dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php');
      $playsheet = new stdClass();
      $playsheet->playsheet = Playsheet::find($id);
      if ($playsheet->playsheet != null) {
        $playsheet->playitems = Playsheet::find($id)->playitems;
        $show = Playsheet::find($id)->show;
        $playsheet->show = $show;
        $playsheet->podcast = Playsheet::find($id)->podcast;
        $playsheet->promotions = Playsheet::find($id)->ads;
        //convert 1 and 0 to true/false values expected by javascript
        $playsheet->playsheet->socan = $playsheet->playsheet->socan == 1 ? true : false;
        $playsheet->playsheet->web_exclusive = $playsheet->playsheet->web_exclusive == 1 ? true : false;
      }
      return Response::json($playsheet);
    });
    //Save Existing Playsheet
    Route::post('/', function ($id) {
      $ps = Playsheet::find($id);
      $ps->update((array) Input::get()['playsheet']);
      $response['playsheet'] = $ps;
      $ps->podcast()->update((array) Input::get()['podcast']);
      $response['podcast'] = $ps->podcast;
      
      

      $playitems = Input::get()['playitems'];
      foreach ($ps->playitems as $delete) {
        $delete->delete();
      }
      foreach ($playitems as $playitem) {
        # encoding fail occurs here
        try {
          $new_playitem = Playitem::create((array) $playitem);
        } catch (Exception $e){
          $message = $e->getMessage();
          $error_response = array ('message' => $message, 'playitem' => $playitem);
          return Response::json($error_response, 400);
        }
        $response['playitems'][] = $new_playitem;
        
      }
      if (isset (Input::get()['ads'])) {
        foreach (Input::get()['ads'] as $ad) {
          $ad['playsheet_id'] = $ps->id;
          if (isset ($ad['id'])) {
            $a = Ad::find($ad['id']);
            unset ($ad['id']);
            $response['ads'][] = $a->update((array) $ad);
          } else {
            $response['ads'][] = Ad::create((array) $ad);
          }
        }
      }
      file_put_contents("/tmp/djland-sync-wp", "");
      return Response::json($response);
    });
    Route::delete('/', function ($id) {
      return Response::json(Playsheet::find($id)->delete());
    });
    Route::post('episode', function ($id) {
      $playsheet = Playsheet::find($id);
      $podcast = $playsheet->podcast;
      $response = $playsheet->update((array) Input::get()['playsheet']) && $podcast->update((array) Input::get()['podcast']);
      if ($response) {
        $podcast->show->make_show_xml();
      }
      return Response::json($response ? "true" : "false");
    });
  });
  Route::get('member/{member_id}/{offset}', function ($member_id = member_id, $offset = offset) {
    if (Member::find($member_id)->isStaff()) {
      $shows = Show::all();
    } else {
      $shows = Member::find($member_id)->shows;
    }
    foreach ($shows as $show) {
      $show_ids[] = $show->id;
    }
    $playsheets = array ();
    foreach (Playsheet::orderBy('start_time', 'desc')->whereIn('show_id', $show_ids)->limit('200')->offset($offset)->get() as $ps) {
      $playsheet = new stdClass();
      $playsheet = $ps;
      //$playsheet->show_info = Show::find($ps->show_id);
      $playsheet->socan = $playsheet->is_socan();
      $playsheets[] = $playsheet;
    }
    return Response::json($playsheets);
  });
  Route::get('member/{member_id}', function ($member_id = member_id) {
    $permissions = Member::find($member_id)->user->permission;
    if ($permissions->staff == 1 || $permissions->administrator == 1) {
      $shows = Show::all();
    } else {
      $shows = Member::find($member_id)->shows;
    }
    foreach ($shows as $show) {
      $show_ids[] = $show->id;
    }
    $socan = Socan::all();
    $playsheets = array ();
    foreach (Playsheet::orderBy('start_time', 'desc')->whereIn('show_id', $show_ids)->limit('200')->get() as $ps) {
      $playsheet = new stdClass();
      $playsheet = $ps;
      $playsheet->show_info = Show::find($ps->show_id);
      $playsheet->socan = $playsheet->is_socan();
      $playsheets[] = $playsheet;
    }
    return Response::json($playsheets);
  });

  Route::get('list', function () {
    return DB::table('playsheets')
      ->join('shows', 'shows.id', '=', 'playsheets.show_id')
      ->select('playsheets.id', 'shows.host', 'playsheets.start_time')
      ->limit('100')
      ->orderBy('playsheets.id', 'desc')
      ->get();
  });
  Route::get('list/{limit}', function ($limit = limit) {
    $playsheets = Playsheet::orderBy('id', 'desc')->limit($limit)->get();
    $list = array();
    foreach ($playsheets as $playsheet) {
      if ($playsheet != null) {
        $ps = new stdClass();
        $ps->id = $playsheet->id;
        $ps->start_time = $playsheet->start_time;
        $ps->show = Show::find($playsheet->show_id);
        $list[] = $ps;
      }
    }
    return Response::json($list);
  });
  Route::get('/{offset}/{limit}', function ($offset = offset, $limit = limit) {
    return Playsheet::select('id', 'edit_date')->orderBy('edit_date', 'desc')->offset($offset)->limit($limit)->get();
  });
});


function fast_report()
{


  /*
   * Example output
   *
   * {
   * 		playsheets: [
   * 			// Just raw playsheets in here with playitems and show model
   * 		],
   * 		show_totals: {
   * 			Show Name: {
   * 				ads:
   * 				cc_20_count:
   * 				cc_20_total:
   * 				cc_30_count:
   * 				cc_30_total:
   * 				fairplay_count:
   * 				accesscon_count:
   * 				afrocon_count:
   * 				femcon_count:
   * 				indigicon_count:
   * 				poccon_count:
   * 				queercon_count:
   * 				hit_count:
   * 				new_count:
   * 				show: {} // This is the show model just kinda raw
   * 				spokenword:
   * 				total:
   * 			}
   * 		},
   * 		totals: {
   * 			ads:
   * 			cc_20_count:
   * 			cc_20_total:
   * 			cc_30_count:
   * 			cc_30_total:
   * 			fairplay_count:
   * 			accesscon_count:
   * 			afrocon_count:
   * 			femcon_count:
   * 			indigicon_count:
   * 			poccon_count:
   * 			queercon_count:
   * 			hit_count:
   * 			new_count:
   * 			spokenword:
   * 			total:
   * 		}
   * }
   */

  $output = collect([
    'playsheets' => [],
    'show_totals' => [],
    'totals' => [
      'ads' => 0,
      'cc_20_count' => 0,
      'cc_20_total' => 0,
      'cc_30_count' => 0,
      'cc_30_total' => 0,
      'fairplay_count' => 0,
      'accesscon_count' => 0,
      'afrocon_count' => 0,
      'femcon_count' => 0,
      'indigicon_count' => 0,
      'poccon_count' => 0,
      'queercon_count' => 0,
      'hit_count' => 0,
      'new_count' => 0,
      'spokenword' => 0,
      'total' => 0,
    ]
  ]);
  // Load the member with user model and permission
  $member = App\Member::with('user.permission')->find($member_id);
  // Query for the shows that have playsheets within this time frame
  $shows_query = App\Show::whereHas('playsheets', function ($query) use ($from, $to, $report_type) {
    $from_dt_string = $from . ($report_type == 'crtc' ? " 06:00:00" : " 00:00:00");
    $query->where('start_time', '>=', $from_dt_string)
      ->where('start_time', '<=', $to . " 23:59:59");
  });
  // Grab all the shows possible if the user is a staff or administrator
  if ($member->user->permission->staff || $member->user->permission->administrator) {
    $shows = $shows_query->get();
    // Else grab all the shows that belong to the user
  } else {
    $shows = $shows_query->whereHas('members', function ($query) use ($member) {
      $query->where('member_id', '=', $member->id);
    })->get();
  }
  if (!$shows->count()) {
    return Response::json("No shows with playsheets in the report timeframe");
  }
  // Load the playsheets for the time specified. Not sure how this will affect memory
  $shows->load([
    'playsheets' => function ($query) use ($from, $to, $report_type) {
      $from_dt_string = $from . ($report_type == 'crtc' ? " 06:00:00" : " 00:00:00");
      $query->orderBy('start_time', 'asc')
        ->where('start_time', '>=', $from_dt_string)
        ->where('start_time', '<=', $to . " 23:59:59");
    }
  ]);
  // Go through the shows one by one and grab playsheets. This is to reduce memory usage.
  while ($shows->count()) {
    // Grab the last show of the collection
    $show = $shows->pop();
    $show_output = [
      "cc_20_count" => 0, // Only for cancon
      "cc_20_total" => 0,
      "cc_30_count" => 0, // Only for cancon
      "cc_30_total" => 0,
      "fairplay_count" => 0,
      "accesscon_count" => 0,
      "afrocon_count" => 0,
      "femcon_count" => 0,
      "indigicon_count" => 0,
      "poccon_count" => 0,
      "queercon_count" => 0,
      "hit_count" => 0,
      "new_count" => 0,
      "show" => $show,
      "spokenword" => $show->playsheets->sum('spokenword_duration'),
      "total" => 0,
    ];
    //  $output['playsheets']
    // Lazy load the playitems
//  $show->playsheets->load('playitems');
    // Go through each playsheet and add stuff up
    foreach ($show->playsheets as $playsheet) {
      $show_output['total'] += $playsheet->playitems->count();
      foreach ($playsheet->playitems as $playitem) {
        $show_output["cc_20_count"] += ($playitem->crtc_category == '20' && $playitem->is_canadian) ? 1 : 0;
        $show_output["cc_20_total"] += ($playitem->crtc_category == '20') ? 1 : 0;
        $show_output["cc_30_count"] += ($playitem->crtc_category == '20' && $playitem->is_canadian) ? 1 : 0;
        $show_output["cc_30_total"] += ($playitem->crtc_category == '20') ? 1 : 0;
        $show_output["fairplay_count"] += ($playitem->is_fairplay) ? 1 : 0;
        $show_output["accesscon_count"] += ($playitem->is_accesscon) ? 1 : 0;
        $show_output["afrocon_count"] += ($playitem->is_afrocon) ? 1 : 0;
        $show_output["femcon_count"] += ($playitem->is_fem) ? 1 : 0;
        $show_output["indigicon_count"] += ($playitem->is_indigicon) ? 1 : 0;
        $show_output["poccon_count"] += ($playitem->is_poccon) ? 1 : 0;
        $show_output["queercon_count"] += ($playitem->is_queercon) ? 1 : 0;
        $show_output["hit_count"] += ($playitem->is_hit) ? 1 : 0;
        $show_output["new_count"] += ($playitem->is_new) ? 1 : 0;
      }
    }
    $output['show_totals'][$show->name] = $show_output;
  }

  return Response::json($output);


}