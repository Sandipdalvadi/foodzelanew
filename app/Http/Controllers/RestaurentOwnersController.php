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

class RestaurentOwnersController extends Controller
{
    public function index()
    { 

        return view('admin.restaurent_owners.index');
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
            6 =>'name',
        );
        $status = $request->input('status');
        $totalData = User::where('role',2)->where('is_deleted',0);
        if($status == 3){
            $totalData = $totalData->count();
        }
        else{
            $totalData = $totalData->where('status',$status)->count();
        }
        $totalFiltered = $totalData; 
        $limit = $request->request->get('length');
        $start = $request->request->get('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(!empty($request->input('search.value')))
        {            
            $search = $request->input('search.value'); 

            $posts =  User::where('role',2)->where('is_deleted',0);
                if($status != 3){
                    $posts = $posts->where('status',$status);
                }
                $posts = $posts->where(function($q) use($search) {
                    $q->Where('id', 'LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->orWhere('phone', 'LIKE',"%{$search}%"); 
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = User::where('role',2)->where('is_deleted',0);
            if($status != 3){
                $totalFiltered = $totalFiltered->where('status',$status);
            }
            $totalFiltered = $totalFiltered->where(function($q) use($search) {
                $q->Where('id', 'LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->orWhere('email', 'LIKE',"%{$search}%")
                ->orWhere('phone', 'LIKE',"%{$search}%"); 
            })
            ->count();
        }   
        else
        {            
            $posts = User::where('role',2)->where('is_deleted',0);
            if($status != 3){
                $posts = $posts->where('status',$status);
            }
            $posts = $posts->offset($start)
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
                $status0 = $status1 = $status2 = "";
                $status0 = $post->status == 0 ? 'selected' : ''; 
                $status1 = $post->status == 1 ? 'selected' : ''; 
                $status2 = $post->status == 2 ? 'selected' : ''; 
                
                $image = '<img style="width:90px;height:90px;" class="b-r-10" src="'.file_exists_in_folder('profile_pic', $img).'"alt="" />';

                $data['checkdata']="<input type='checkbox' class='case' id='$post->id' name='case' value='$post->id'>";
                $data['id'] = $post->id;
                $data['name'] = $name;
                $data['image'] = $image;
                $data['phone'] = $phone;
                $data['email'] = $email;
                
                $data['status'] = "<select class='js-example-basic-single form-control' onchange=changeStatus(this,'".route('admin.restaurent_owners.changeStatus',['id'=>$post->id])."')>
                <option value='0' ".$status0.">In Active</option>
                <option value='1' ".$status1.">Active</option>
                <option value='2' ".$status2.">Pending</option></select>";
                
                
                $data['action'] = "<div style='display: flex;'>
                <form style='float:left;margin-left:6px;' method='POST' action=".route('admin.restaurent_owners.delete',['id'=>$post->id]).">";
               
                $data['action'] .=  csrf_field();
                $data['action'] .= method_field("DELETE");
                $data['action'] .=  "<button class='btn btn-danger'><i class='icofont icofont-ui-delete'></i></button></form></div>";

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
    
    public function destroy($id)
    {   
        $user = User::findOrFail($id);
        $user->is_deleted = 1;
        $user->save();
        return redirect()->route('admin.restaurent_owners.index')->with('message',"User Deleted Successfully"); 
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
    public function changeStatus($id, Request $request)
    {   
        $user = User::findOrFail($id);
        $user->status = $request->input('status');
        $user->save();
    }   
}