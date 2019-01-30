<?php

namespace App\Http\Controllers\Auth;

use App\Forms\UserCreateForm;
use App\Forms\UserInterestsForm;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Kris\LaravelFormBuilder\FormBuilder;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create(array_except($data, ['interests']));
        auth()->user();
        if (array_has($data,'interests')) {
            $interests = array_keys($data['interests'][0]);
            $user->attachTags($interests);
        }

        return $user;
    }

    public function showRegistrationForm(FormBuilder $formBuilder) {
        $form = $formBuilder->create(class_basename(UserCreateForm::class), [
            'method' => 'POST',
            'url' => route('register'),
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
}
