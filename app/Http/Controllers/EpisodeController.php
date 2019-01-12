<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Carbon\Carbon;

use App\Episode;
use App\Forms\EpisodeForm;

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

        // $form->addBefore('submit', 'playitems', 'collection', [
        //     'type' => 'form',
        //     'property' => 'id',
        //     'options' => [
        //         'label' => false,
        //         'class' => $formBuilder->create(class_basename(PlayitemForm::class)),
        //     ],
        // ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        if (!$episode->wasRecentlyCreated) {
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

        // $form->addBefore('submit', 'playitems', 'collection', [
        //     'type' => 'form',
        //     'property' => 'id',
        //     'options' => [
        //         'label' => false,
        //         'class' => $formBuilder->create(class_basename(PlayitemForm::class)),
        //     ],
        // ]);

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
        if ($request->has('start_date') && $request->has('start_time')) {
            $attributes['start_datetime'] = new Carbon($request->input('start_date').' '.$request->input('start_time'));
        }
        if ($request->has('end_date') && $request->has('end_time')) {
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
