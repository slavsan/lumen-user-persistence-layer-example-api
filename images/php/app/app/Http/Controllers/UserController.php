<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{

    /**
     * Display a list of users
     * @return mixed
     */
    public function showUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Displays one user
     * @param $id
     * @return Response
     */
    public function showUser($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|min:1',
        ]);

        $status = Response::HTTP_BAD_REQUEST;
        $user = '';

        if (!$validator->fails()) {
            $status = Response::HTTP_NOT_FOUND;
            $user = User::find($id);

            if ($user) {
                $status = Response::HTTP_OK;
            }
        }

        return new Response($user, $status);
    }

    /**
     * Create new user
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:user',
            'forename' => 'required|min:2|alpha',
            'surname' => 'required|min:2|alpha',
            'created' => 'date',
        ]);

        $user = new User();

        $user->email = $request->input('email');
        $user->forename = $request->input('forename');
        $user->surname = $request->input('surname');
        $created = $request->input('created');
        if ($created) {
            $user->created = $request->input('created');
        }

        $user->save();

        $user = User::find($user->id);

        return new Response($user, Response::HTTP_CREATED, [
            'Location' => route('fetchUser', ['id' => $user->id])
        ]);
    }

    /**
     * Update existing
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|min:1',
        ]);

        $status = Response::HTTP_BAD_REQUEST;
        $content = '';

        if (!$validator->fails()) {
            $user = User::find($id);
            $status = Response::HTTP_NOT_FOUND;

            if ($user) {
                $status = Response::HTTP_OK;

                $this->validate($request, [
                    'email' => 'email|unique:user,email,'.$user->id,
                    'forename' => 'min:2|alpha',
                    'surname' => 'min:2|alpha',
                    'created' => 'date',
                ]);

                $fields = ['email', 'forename', 'surname', 'created'];

                foreach ($fields as $field) {
                    if ($request->input($field)) {
                        $user[$field] = $request->input($field);
                    }
                }

                $user->save();
                
                $content = User::find($user->id);
            }
        }

        return new Response($content, $status);
    }

    /**
     * Delete user (hard delete)
     * @param $id
     * @return Response
     */
    public function deleteUser($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|min:1',
        ]);

        $status = Response::HTTP_BAD_REQUEST;

        if (!$validator->fails()) {
            $user = User::find($id);

            $status = Response::HTTP_NOT_FOUND;

            if ($user) {
                $user->delete();
                $status = Response::HTTP_NO_CONTENT;
            }
        }

        return new Response('', $status);
    }
}
