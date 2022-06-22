<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
// use Role;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $request;
    protected $route;
    protected $view;
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->view    = '.user.';
        $this->route   = 'master.user.';
    }


    public function index()
    {
        $title = 'Master Data User';
        return view($this->view . 'index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->request->ajax()) {
            return redirect(route('home'));
            exit();
        }
        $title = 'Tambah Data User';
        $level = Role::get();
        return view($this->view . 'form', compact('title', 'level'));
    }

    public function getbangunan()
    {
        $title = 'Master Data User';
        return view($this->view . 'select', compact('title'));
    }

    public function api()
    {
        $data = User::select(
            'users.id',
            'users.fullname',
            'users.email',
            'users.username',
            'role.rolename'

        )->join('role', 'users.role_id', '=', 'role.id', 'left')->get();
        return DataTables::of($data)
            ->editColumn('id', function ($p) {
                return "<input type='checkbox' name='cbox[]' value='" . $p->id . "' />";
            })
            ->editColumn('action', function ($p) {
                return  '<a href="" class="btn btn-warning btn-sm" id="edit" data-id="' . $p->id . '">Edit </a><a href="" class="btn btn-danger btn-sm" id="delete" data-id="' . $p->id . '"><i class="fa fa-delete"></i>Delete </a> ';
            }, true)
            ->editColumn('nama', function ($p) {
                return $p->name;
            }, true)
            ->addIndexColumn()
            ->rawColumns(['action', 'id'])
            ->toJson();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'email' => 'required|unique:users,email',
        ]);
        try {
            $data = new User;
            $data->username = $this->request->username;
            $data->password = bcrypt($this->request->password);
            $data->email = $this->request->email;
            $data->fullname = $this->request->fullname;
            $data->role_id = $this->request->role_id;
            $data->save();

            return response()->json([
                'status' => 1,
                'msg' => 'data user berhasil dtambah'
            ]);
        } catch (User $t) {
            return response()->json([
                'status' => 1,
                'msg' =>  $t,
            ]);
        }
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

        if (!$this->request->ajax()) {
            return response()->json([
                'data' => 'data null',
                'aspx' => 'response aspx fail '
            ]);
        }
        //
        $data = User::findOrfail($id);
        $level = Role::get();

        $fullname = $data->fullname;
        $email = $data->email;
        $email_verified_at = $data->email_verified_at;
        $password = $data->password;
        $remember_token = $data->remember_token;
        $created_at = $data->created_at;
        $updated_at = $data->updated_at;
        $role_id = $data->role_id;
        $username = $data->username;

        // $tmlevel_id = $data->tmlevel_id;
        return view($this->view . 'form_edit', compact(
            'id',
            'fullname',
            'email',
            'email_verified_at',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
            'role_id',
            'username',

        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // $this->request->validate([
        //     // 'username' => 'required|unique:users,username',
        //     'password' => 'required',
        //     'email' => 'required|unique:user,email',
        //     'tmlevel_id' => 'required',
        //     'tmproyek_id' => 'required'
        // ]);
        try {
            // dd($this->request->file('foto'));
            $data = User::find($id);
            // dd($this->request->file('foto'));
            $data->username = $this->request->username;
            $data->password = bcrypt($this->request->password);
            $data->email = $this->request->email;
            $data->fullname = $this->request->fullname;
            $data->role_id = $this->request->role_id;
            $data->save();

            return response()->json([
                'status' => 1,
                'msg' => 'data user berhasil edit'
            ]);
        } catch (User $t) {
            return response()->json([
                'status' => 1,
                'msg' =>  $t,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            user::whereid($id)->delete();

            return response()->json([
                'status' => 1,
                'msg' => 'Data berhasil di hapus'
            ]);
        } catch (user $t) {
            return response()->json([
                'status' => 2,
                'msg' => $t
            ]);
        }
    }
}
