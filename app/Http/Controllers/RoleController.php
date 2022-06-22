<?php

namespace App\Http\Controllers;

// use App\Models\Tmlevel;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use App\Models\Role;

class RoleController extends Controller
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
        $this->view    = 'role.';
        $this->route   = 'master.user.';
    }


    public function index()
    {
        $title = 'Master Role';
        return view($this->view . 'index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        $data = Role::get();
        return DataTables::of($data)
            ->editColumn('id', function ($p) {
                return "<input type='checkbox' name='cbox[]' value='" . $p->id . "' />";
            })
            ->editColumn('action', function ($p) {
                return  '<a href="" class="btn btn-warning btn-xs" id="edit" data-id="' . $p->id . '"><i class="fa fa-edit"></i>Edit </a> ';
            }, true)

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
            'rolename' => 'required|unique:role,rolename',

        ]);
        try {
            $data = new Role;

            $data->rolename = $this->request->rolename;
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

        // if (!$this->request->ajax()) {
        //     return response()->json([
        //         'data' => 'data null',
        //         'aspx' => 'response aspx fail '
        //     ]);
        // }
        //
        $data = Role::findOrfail($id);
        $id  = $id;
        $rolename = $data->rolename;

        return view($this->view . 'form_edit', compact(
            'id',
            'rolename'
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
        $this->request->validate([
            'rolename' => 'required|unique:role,rolename',

        ]);
        try {
            $data = Role::find($id);
            $data->rolename = $this->request->rolename;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (is_array($this->request->id)) {
                $datas =  user::whereIn('id', $this->request->id);
                foreach ($datas as $data) {
                    $tgl = Carbon::now()->format('y-m-d');
                    $ext = $this->request->file('foto');
                    $setname = rand(122, 333) . '-' . $tgl . '.' . $ext->getClientOriginalExtension();

                    $ext->move('./file/photo_user/', $setname);
                    $data = User::find($id);
                    $filename = public_path('./file/photo_user/' . $data->photo);
                    if (File::exists($filename)) {
                        File::delete($filename);
                    }
                }
                $data->delete();
            } else {
                user::whereid($this->request->id)->delete();
            }
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
