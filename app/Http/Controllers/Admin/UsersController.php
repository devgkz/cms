<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRoleList;
use App\Mail\SendUserCredentials;
use Validator;
use Session;

class UsersController extends Controller
{
    //
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = User::sortable(['name' => 'asc'])->paginate(20);
        
        return view('admin/users/index', ['items'=>$items]);
    }
    
    /*
    * $number - количество символов в пароле
    * $lit - тип символов. передается массивом array(0,1,2); 0 - маленькие буквы, 1 - большие, 2 - цифры
    */
    private function genPassword($len)
    {
        // массивы символов
        $array = [
            ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z'],
            ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'v', 'x', 'y', 'z'],
            ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z'],
            ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'],
        ];

        $passwd = '';
        for ($rnd = 0; $rnd < $len; $rnd++) {
            $set = $array[mt_rand(0, 3)];
            $passwd .= $set[mt_rand(0, count($set) - 1)];
        }
        return $passwd;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        return view('admin/users/add', [
            'password' => $this->genPassword(8)
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required',
            'role' => 'required',
        ]);
        
        if ($validator->fails()) {
            /* $request->flash();
            return view('admin/users/add', [
                'errors' => $validator->errors()
            ]) ;*/
            return redirect()->to(config('cms.admin_uri').'/users/add')->withInput()->withErrors($validator->errors());
        } else {
            $item = User::create($request->all());
            $item->password = \Hash::make($request->password);
            $item->save();
            if ($request->send_password) {
                $mail = new SendUserCredentials();
                $mail->name = $item->name;
                $mail->email = $item->email;
                $mail->role = UserRoleList::get($item->role);
                $mail->password = $request->password;
                Mail::to($item->email)->send($mail);
            }
            
            return response('<div class="alert success">Пользователь добавлен '.Auth::user()->email.'</div><script>window.location.reload()</script>');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $item = User::findOrFail($id);
        
        return view('admin/users/view', [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = User::findOrFail($id);
        
        return view('admin/users/edit', [
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            /* 'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ], */
            //'password' => 'required',
            'phone' => 'required',
            //'role' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->to(config('cms.admin_uri').'/users/edit/'.$id)
                ->withInput()
                ->withErrors($validator->errors());
        } else {
            $data = [
                'name' => $request->name,
                //'email' => $request->email,
                //'role' => $request->role,
                'phone' => $request->phone,
                'comment' => $request->comment,
            ];
            if ($request->password) {
                $data['password'] = \Hash::make($request->password);
            }
            
            $item = User::find($id);
            $item->update($data);
            
            if ($request->password && $request->send_password) {
                $mail = new SendUserCredentials();
                $mail->name = $item->name;
                $mail->email = $item->email;
                $mail->role = UserRoleList::get($item->role);
                $mail->password = $request->password;
                Mail::to($item->email)->send($mail);
            }
            
            return response('<div class="alert success">Пользователь изменен</div><script>window.location.reload()</script>');
        }
    }

      
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        $user = \Auth::user();
        
        if (in_array($user->id, [$id])) {
            Session::flash('error', 'Себя удалить нельзя!');
            return redirect()->to(config('cms.admin_uri').'/users');
        }
        
        $item = User::findOrFail($id);
        foreach ($item->bookings as $booking) {
            $booking->status = 2;
            $booking->save();
        }
        $item->delete();
        

        return redirect()->to(config('cms.admin_uri').'/users');
    }
}
