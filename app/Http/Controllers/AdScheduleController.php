<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kris\LaravelFormBuilder\FormBuilder;
use Carbon\Carbon;
use App\Forms\AdScheduleForm;

use App\AdSchedule;

class AdScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adSchedules = AdSchedule::all();

        return response()->json($adSchedules);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(AdScheduleForm::class), [
            'method' => 'POST',
            'url' => route('ad-scheduler.store'),
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
        $form = $formBuilder->create(class_basename(AdScheduleForm::class));

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get array of all the fillable fields
        $fillable = app(AdSchedule::class)->getFillable();

        $attributes = $request->only($fillable);

        $attributes = collect($attributes)->filter(function ($value, $key) {
            return $value !== null;
        })->toArray();

        // Build start/end datetimes
        if ($request->input('start_date', false) && $request->input('start_time', false)) {
            $attributes['active_datetime_start'] = new Carbon($request->input('start_date').' '.$request->input('start_time'));
        }
        if ($request->input('end_date', false) && $request->input('end_time', false)) {
            $attributes['active_datetime_end'] = new Carbon($request->input('end_date').' '.$request->input('end_time'));
        }

        $adSchedule = AdSchedule::newModelInstance($attributes);

        $saved = ($adSchedule->isDirty()) ? $adSchedule->save() : false;

        if (!$adSchedule->wasRecentlyCreated) {
            return response()->json($adSchedule, 409);
        }

        if ($saved) {
            return response()->json($adSchedule, 201);
        }

        return response('Ad Schedule not created', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdSchedule  $adSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(AdSchedule $adSchedule)
    {
        dd($adSchedule);
        return response()->json($adSchedule);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdSchedule  $adSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(AdSchedule $adSchedule, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(AdScheduleForm::class), [
            'method' => 'PUT',
            'model' => $adSchedule,
            'url' => route('ad-scheduler.update', ['id' => $adSchedule->id]),
        ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdSchedule  $adSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdSchedule $adSchedule, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(AdScheduleForm::class));

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get array of all the fillable fields
        $fillable = app(AdSchedule::class)->getFillable();

        $attributes = $request->only($fillable);

        $attributes = collect($attributes)->filter(function ($value, $key) {
            return $value !== null;
        })->toArray();

        // Build start/end datetimes
        if ($request->input('start_date', false) && $request->input('start_time', false)) {
            $attributes['active_datetime_start'] = new Carbon($request->input('start_date').' '.$request->input('start_time'));
        }
        if ($request->input('end_date', false) && $request->input('end_time', false)) {
            $attributes['active_datetime_end'] = new Carbon($request->input('end_date').' '.$request->input('end_time'));
        } elseif ($request->has('end_date') && $request->has('end_time')) {
            $attributes['active_datetime_end'] = null;
        }

        $adSchedule->fill($attributes);

        if ($adSchedule->isDirty()) {
            if ($adSchedule->save()) {
                return response('Ad Schedule updated');
            }

            return response('Error updating ad schedule', 500);
        }

        return response('Ad Schedule not updated. No changes found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdSchedule  $adSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdSchedule $adSchedule)
    {
        //
    }
}
