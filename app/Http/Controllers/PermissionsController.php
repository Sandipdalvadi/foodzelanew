<?php 

namespace App\Http\Controllers;

use App\Models\Permissions;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Auth, Hash, DB, Lang, URL;

class PermissionsController extends Controller
{
    public function index()
    { 

        return view('admin.permissions.index');
    }

    public function list(Request $request)
    {
        $columns = array( 
            0 =>'id', 
            1 =>'id', 
            2 =>'name',
            3 =>'url',
            4 =>'full_url',
        );
        // permissions
  
        $totalData = Permissions::count();
        $totalFiltered = $totalData; 
        $limit = $request->request->get('length');
        $start = $request->request->get('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(!empty($request->input('search.value')))
        {            
            $search = $request->input('search.value'); 

            $posts =  Permissions::where(function($q) use($search) {
                    $q->Where('id', 'LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%")
                    ->orWhere('url', 'LIKE',"%{$search}%")
                    ->orWhere('full_url', 'LIKE',"%{$search}%"); 
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Permissions::where(function($q) use($search) {
                $q->Where('id', 'LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->orWhere('url', 'LIKE',"%{$search}%")
                ->orWhere('full_url', 'LIKE',"%{$search}%"); 
            })
            ->count();
        }   
        else
        {            
            $posts = Permissions::offset($start)
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
                $url = $post->url ? $post->url : '-';
                $fullUrl = $post->full_url ? $post->full_url : '-';
                $data['checkdata']="<input type='checkbox' class='case' id='$post->id' name='case' value='$post->id'>";
                $data['id'] = $post->id;
                $data['name'] = $name;
                $data['url'] = $url;
                $data['full_url'] = $fullUrl;
                
                $data['action'] = "<a style='float:left;' href=".route('admin.permissions.form',['id'=>$post->id])." title='EDIT' class='btn btn-primary' ><i class='icofont icofont-ui-edit'></i></a>
                <form style='float:left;margin-left:6px;' method='POST' action=".route('admin.permissions.delete',['id'=>$post->id]).">";
               
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
        $permissions = $request->id ? Permissions::find($request->id) : new Permissions ;  
        return view('admin.permissions.create',['permissions'=>$permissions]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'url' => 'required|max:255',
            'full_url' => 'required|max:255',
        ]);

        if($validator->fails())
        {
            return redirect()->route('admin.permissions.form',['id'=>$request->id])->withErrors($validator)->withInput();
        } 
        $permissions = $request->id ? Permissions::findOrFail($request->id) : new Permissions;
        $permissions->name = $request->name;
        $permissions->url = $request->url;
        $permissions->full_url = $request->full_url;
        $permissions->save();

        $message = $request->id ? "Permission Updated Successfully" :"New Permission Created Successfully";
        return redirect()->route('admin.permissions.index')->with('message', $message );
    }

    public function destroy($id)
    {   
        $permissions = Permissions::findOrFail($id);
        $permissions->delete();
        return redirect()->route('admin.permissions.index')->with('message',"Permissions Deleted Successfully");
      
    }

    public function alldeletes(Request $request)
    {   
        $multiId = $request->id; 
        
        foreach ($multiId as $singleId) 
        {
            $permissions = Permissions::findOrFail($singleId);
            $permissions->delete();
        }     
    }
}