<?php

namespace App\Http\Controllers;

use App\Forms\UserCreateForm;
use App\Forms\UserInterestsForm;
use App\Forms\UserEditForm;
use Illuminate\Http\Request;

use Kris\LaravelFormBuilder\FormBuilder;
use App\User;
use Spatie\Tags\Tag;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(class_basename(UserCreateForm::class), [
            'method' => 'POST',
            'url' => route('members.store'),
            'model' => auth()->user()
        ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        auth()->login($user, true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id = null, FormBuilder $formBuilder)
    {
//        $user = auth()->user(); // Change this to allow different users for admins
//
//        return view('members.settings')->withUser($user);

        $form = $formBuilder->create(class_basename(UserEditForm::class), [
            'method' => 'PUT',
            'url' => route('members.update', auth()->user()->id),
            'model' => auth()->user()
        ]);

        $form->addBefore('submit', 'interests', 'collection', [
            'type' => 'form',
            'options' => [
                'label' => false,
                'class' => $formBuilder->create(class_basename(UserInterestsForm::class)),]
        ]);

        return view('forms.basic', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $user->fill(array_except($request->all(), ['interests']));
        $user->save();

        if ($request->has('interests')) {
            $interests = array_keys($request['interests'][0]);
            $user->attachTags($interests);
        }
        dd($request->all());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}