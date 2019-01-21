<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Carbon\Carbon;

use App\Episode;
use App\Forms\EpisodeForm;
use App\Forms\EpisodeItemForm;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $episodes = Episode::all();

        return response()->json($episodes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(EpisodeForm::class), [
            'method' => 'POST',
            'url' => route('episodes.store'),
        ]);

        $form->addBefore('submit', 'episode_items', 'collection', [
            'type' => 'form',
            'property' => 'id',
            'options' => [
                'label' => false,
                'class' => $formBuilder->create(class_basename(EpisodeItemForm::class)),
            ],
        ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(EpisodeForm::class));

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get array of all the fillable fields
        $fillable = app(Episode::class)->getFillable();

        $attributes = $request->only($fillable);

        // Build start/end datetimes
        if ($request->has('start_date') && $request->has('start_time')) {
            $attributes['start_datetime'] = new Carbon($request->input('start_date').' '.$request->input('start_time'));
        }
        if ($request->has('end_date') && $request->has('end_time')) {
            $attributes['end_datetime'] = new Carbon($request->input('end_date').' '.$request->input('end_time'));
        }

        $episode = Episode::firstOrNew($attributes);

        $saved = ($episode->isDirty()) ? $episode->save() : false;

        // New instance for episodeItem models
        $episodeItems = collect([]);

        if ($request->has('episode_items')) {
            foreach ($request->input('episode_items') as $episodeItem) {
                // Do what the episodeItem model's observers and mutators normally would
                if (preg_match('/^([0-9]*)[:]([0-9]*)$/', $episodeItem['duration'], $matches)) {
                    $episodeItem['duration'] = $matches[1]*60+$matches[2];
                }

                // Push the new or fetched episodeItem object into the collection
                $episodeItems->push($episode->episodeItems()->firstOrCreate($episodeItem));
            }
        }

        // Count how many episodeItems were recently created
        $new_episode_items = $episodeItems->reject(function ($episodeItem, $key) {
            return !$episodeItem->wasRecentlyCreated;
        })->count();

        // Fetch the episode's episodeItems because I wanna see that in the JSON
        $episode->load('episodeItems');

        if (!$episode->wasRecentlyCreated && !$new_episode_items) {
            return response()->json($episode, 409);
        }

        if ($saved) {
            return response()->json($episode, 201);
        }

        return response('Episode not created', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function show(Episode $episode)
    {
        return response()->json($episode);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function edit(Episode $episode, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(EpisodeForm::class), [
            'method' => 'PUT',
            'model' => $episode,
            'url' => route('episodes.update', ['id' => $episode->id]),
        ]);

        $form->addBefore('submit', 'episodeitems', 'collection', [
            'type' => 'form',
            'property' => 'id',
            'options' => [
                'label' => false,
                'class' => $formBuilder->create(class_basename(EpisodeItemForm::class)),
            ],
        ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Episode $episode, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(EpisodeForm::class));

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get array of all the fillable fields
        $fillable = app(Episode::class)->getFillable();

        $attributes = $request->only($fillable);

        // Build start/end datetimes
        if ($request->input('start_date', false) && $request->input('start_time', false)) {
            $attributes['start_datetime'] = new Carbon($request->input('start_date').' '.$request->input('start_time'));
        }
        if ($request->input('end_date', false) && $request->input('end_time', false)) {
            $attributes['end_datetime'] = new Carbon($request->input('end_date').' '.$request->input('end_time'));
        }

        $episode->fill($attributes);

        if ($episode->isDirty()) {
            if ($episode->save()) {
                return response('Episode updated');
            }

            return response('Error updating episode', 500);
        }

        return response('Episode not updated. No changes found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Episode $episode)
    {
        //
    }
}
