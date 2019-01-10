<?php

namespace App\Http\Controllers;

use App\Show;
use App\User;
use Illuminate\Http\Request;

use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\ShowForm;

class ShowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shows = Show::all();

        return response()->json($shows);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(ShowForm::class), [
            'method' => 'POST',
            'url' => route('shows.store'),
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
        $form = $formBuilder->create(class_basename(ShowForm::class));

        $rulesToOverride = [
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ];

        $form->validate($form->getRules($rulesToOverride));

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        // Get array of all the fillable fields
        $fillable = app(Show::class)->getFillable();

        $show = Show::with('users')->firstOrNew($request->only($fillable));

        $new_users = false;

        if ($show->exists && $show->users()->count()) {
            foreach ($show->users as $user) {
                if ($new_users) {
                    break;
                }
                if (!in_array($user->id, $request->input('users'))) {
                    $new_users = true;
                }
            }
            foreach ($request->input('users') as $user_id) {
                if ($new_users) {
                    break;
                }
                if (!$show->users->where('id', $user_id)->count()) {
                    $new_users = true;
                }
            }
        }

        $saved = false;

        if (!$show->exists) {
            $saved = $show->save();
        }

        $show->users()->sync($request->input('users'));

        if (!$show->wasRecentlyCreated && !$new_users) {
            return response()->json($show, 409);
        }

        if ($saved) {
            return response()->json($show, 201);
        }

        return response('Album not created', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function show(Show $show)
    {
        return response()->json($show);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function edit(Show $show, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(ShowForm::class), [
            'method' => 'PUT',
            'url' => route('shows.update', ['id' => $show->id]),
            'model' => $show,
        ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Show $show, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(ShowForm::class));

        $rulesToOverride = [
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ];

        $form->validate($form->getRules($rulesToOverride));

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $show->fill($request->except(['_token', 'users']));

        $is_dirty = $show->isDirty();

        $new_users = false;

        foreach ($show->users as $user) {
            if ($new_users) {
                break;
            }
            if (!in_array($user->id, $request->input('users'))) {
                $new_users = true;
            }
        }
        foreach ($request->input('users') as $user_id) {
            if ($new_users) {
                break;
            }
            if (!$show->users->where('id', $user_id)->count()) {
                $new_users = true;
            }
        }

        if ($new_users) {
            $show->users()->sync($request->input('users'));
        }

        $saved = ($show->isDirty()) ? $show->save() : null;

        if ($saved === false) {
            return response('Error updating show', 500);
        }

        if ($new_users || $saved) {
            return response('Show updated');
        }

        return response('Show not updated. No changes found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Show  $show
     * @return \Illuminate\Http\Response
     */
    public function destroy(Show $show)
    {
        //
    }
}
