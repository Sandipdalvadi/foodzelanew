<?php 

namespace App\Http\Controllers;

use App\User;
use App\Models\Permissions;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Auth, Hash, DB, Lang, URL;

class ManagersController extends Controller
{
    public function index()
    { 

        return view('admin.managers.index');
    }

    public function list(Request $request)
    {
        $columns = array( 
            0 =>'id', 
            1 =>'id', 
            2 =>'name',
            3 =>'email',
            4 =>'phone',
            5 =>'name',
        );
  
        $totalData = User::where('role',5)->where('is_deleted',0)->count();
        $totalFiltered = $totalData; 
        $limit = $request->request->get('length');
        $start = $request->request->get('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(!empty($request->input('search.value')))
        {            
            $search = $request->input('search.value'); 

            $posts =  User::where('role',5)->where('is_deleted',0)
                ->where(function($q) use($search) {
                    $q->Where('id', 'LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->orWhere('phone', 'LIKE',"%{$search}%"); 
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = User::where('role',5)->where('is_deleted',0)
            ->where(function($q) use($search) {
                $q->Where('id', 'LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->orWhere('email', 'LIKE',"%{$search}%")
                ->orWhere('phone', 'LIKE',"%{$search}%"); 
            })
            ->count();
        }   
        else
        {            
            $posts = User::where('role',5)->where('is_deleted',0)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
        }
        $data = array();
        $data1=array();

        if($posts)
        {
            foreach ($posts as $post) 
            {
                $name = $post->name ? $post->name : '-';
                $phone = $post->phone ? $post->phone : '-';
                $email = $post->email ? $post->email : '-';
                $img= $post->profile_pic ? $post->profile_pic : '';
                $image = '<img style="width:90px;height:90px;" class="b-r-10" src="'.file_exists_in_folder('profile_pic', $img).'"alt="" />';

                $data['checkdata']="<input type='checkbox' class='case' id='$post->id' name='case' value='$post->id'>";
                $data['id'] = $post->id;
                $data['name'] = $name;
                $data['image'] = $image;
                $data['phone'] = $phone;
                $data['email'] = $email;
                
                $data['action'] = "<a style='float:left;' href=".route('admin.managers.form',['id'=>$post->id])." title='EDIT' class='btn btn-primary' ><i class='icofont icofont-ui-edit'></i></a>
                <form style='float:left;margin-left:6px;' method='POST' action=".route('admin.managers.delete',['id'=>$post->id]).">";
               
                $data['action'] .=  csrf_field();
                $data['action'] .= method_field("DELETE");
                $data['action'] .=  "<button class='btn btn-danger'><i class='icofont icofont-ui-delete'></i></button></form>";

                $data1[]=$data;
            }
        }
        $json_data = array(
            "draw"            => intval($request->request->get('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data1   
        );
        echo json_encode($json_data); 
    }
    
    public function form(Request $request)
    {   
        $manager = $request->id ? User::find($request->id) : new User ;  
        $permissions= Permissions::all();
        return view('admin.managers.create',['user'=>$manager,'permissions'=>$permissions]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email,'. $request->id,
            'password' =>  $request->id ? '' : 'required',
            'phone' => 'required|max:255',
        ]);

        if($validator->fails())
        {
            return redirect()->route('admin.managers.form',['id'=>$request->id])->withErrors($validator)->withInput();
        } 
        $user = $request->id ? User::findOrFail($request->id) : new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->permissions = $request->permissions ? implode(",",$request->permissions) : '';
        if($request->id == 0 || $request->password != '' )
        {
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->status;
        $user->role = 5;
        if ($files = $request->file('profile_pic')) 
        {
            $destinationPath = public_path('profile_pic/'); // upload path
            $profileImage = time() . "." . $files->getClientOriginalName();
            $files->move($destinationPath, $profileImage);
            old_file_remove('profile_pic',$user->profile_pic);
            $user->profile_pic = $profileImage;    
        }
        $user->save();

        $message = $request->id ? "User Updated Successfully" :"New User Created Successfully";
        return redirect()->route('admin.managers.index')->with('message', $message );
    }

    public function destroy($id)
    {   
        $user = User::findOrFail($id);
        $user->is_deleted = 1;
        $user->save();
        return redirect()->route('admin.managers.index')->with('message',"User Deleted Successfully");
      
    }

    public function alldeletes(Request $request)
    {   
        $multiId = $request->id; 
        
        foreach ($multiId as $singleId) 
        {
            $user = User::findOrFail($singleId);
            $user->is_deleted = 1;
            $user->save();
        }     
    }
}