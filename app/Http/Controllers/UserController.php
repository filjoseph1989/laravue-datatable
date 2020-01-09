<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Return resource
     *
     * @param  Request $request
     * @return json
     */
    public function index(Request $request)
    {
        if ($request->input('showdata')) {
            $users = User::orderBy('id', 'desc')->get();
            return UserCollection::collection($users);
        }
    }

    /**
     * Store new user
     *
     * @param  Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name'                  => 'required',
            'email'                 => 'required|unique:users,email',
            'password'              => 'required|confirmed:password_confirmation',
            'password_confirmation' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'error' => $validated->messages()->first()
            ]);
        }

        $name     = $request->input('name');
        $email    = $request->input('email');
        $password = bcrypt($request->input('password'));

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => $password
        ]);

        if ($user->wasRecentlyCreated) {
            return response()->json([
                'success' => 'true'
            ]);
        }

        return response()->json([
            'error' => 'failed to add new user'
        ]);
    }

    /**
     * Return a single user
     *
     * @param  int $id
     * @return json
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update user
     *
     * @param  Request $request
     * @param  int  $id         User Id
     * @return json
     */
    public function update(Request $request, $id)
    {
        $data = [];

        if ($request->has('name')) {
            $data['name'] = $request->input('name');
        }
        if ($request->has('email')) {
            $data['email'] = $request->input('email');
        }
        if ($request->has('password')) {
            $data['password']              = $request->input('password');
            $data['password_confirmation'] = $request->input('password_confirmation');

            if (!is_null($data['password']) && $data['password'] == $data['password_confirmation']) {
                unset($data['password_confirmation']);
                $data['password'] = bcrypt($data['password']);
            } else {
                return response()->json([
                    'error' => 'password not match with confirmation password'
                ]);
            }
        }

        User::where('id', $id)->update($data);

        return response()->json([
            'success' => 'true'
        ]);
    }

    /**
     * Delete user
     * @param  int $id
     * @return json
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return response()->json([
            'success' => 'true'
        ]);
    }

    /**
     * Return users list of the given length
     *
     * @param  Request $request
     * @return json
     */
    public function usersList(Request $request)
    {
        $length = $request->input('length');

        $users = User::orderBy('id', 'desc')->paginate($length);
        return UserCollection::collection($users);
    }

    /**
     * Search users
     *
     * @param  Request $request
     * @return json
     */
    public function search(Request $request)
    {
        $columns = ['id', 'name', 'email', 'created_at'];

        $length  = $request->input('length'); # pagination length
        $column  = $request->input('column'); # column to sort
        $search  = $request->input('search'); # search key

        if (in_array($column, $columns)) {
            $query = User::select('id', 'name', 'email', 'created_at')->orderBy($column);
        }

        if ($search && isset($query)) {
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('created_at', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate($length);

        return UserCollection::collection($users);
    }
}
