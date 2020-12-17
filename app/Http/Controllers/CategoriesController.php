<?php 

namespace App\Http\Controllers;

use App\User;
use App\Models\Categories;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Auth, Hash, DB, Lang, URL;

class CategoriesController extends Controller
{
    public function index()
    { 

        return view('admin.categories.index');
    }

    public function list(Request $request)
    {
        $columns = array( 
            0 =>'id', 
            1 =>'id', 
            2 =>'name',
            3 =>'name',
            4 =>'name'
        );
  
        $totalData = Categories::where('is_deleted',0)->count();
        $totalFiltered = $totalData; 
        $limit = $request->request->get('length');
        $start = $request->request->get('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(!empty($request->input('search.value')))
        {            
            $search = $request->input('search.value'); 

            $posts =  Categories::where('is_deleted',0)
                ->where(function($q) use($search) {
                    $q->Where('id', 'LIKE',"%{$search}%")
                    ->orWhere('name', 'LIKE',"%{$search}%"); 
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Categories::where('is_deleted',0)
            ->where(function($q) use($search) {
                $q->Where('id', 'LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%"); 
            })
            ->count();
        }   
        else
        {            
            $posts = Categories::where('is_deleted',0)
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
                $img= $post->image ? $post->image : '';
                $image = '<img style="width:90px;height:90px;" class="b-r-10" src="'.file_exists_in_folder('categories', $img).'"alt="" />';

                $data['checkdata']="<input type='checkbox' class='case' id='$post->id' name='case' value='$post->id'>";
                $data['id'] = $post->id;
                $data['name'] = $name;
                $data['image'] = $image;
                $data['status'] = "<label class='switch'><input type='checkbox' ";
                if($post->status == 1){
                    $data['status'] .= "checked";
                }
                $data['status'] .= " onchange=changeStatus(this,'".route('admin.categories.changeStatus',['id'=>$post->id])."')><span class='slider round'></span>
                        </label>";
                
                
                $data['action'] = "<div style='display: flex;'><a style='float:left;' href=".route('admin.categories.form',['id'=>$post->id])." title='EDIT' class='btn btn-primary' ><i class='icofont icofont-ui-edit'></i></a>
                <form style='float:left;margin-left:6px;' method='POST' action=".route('admin.categories.delete',['id'=>$post->id]).">";
               
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
    
    public function form(Request $request)
    {   
        $categories = $request->id ? Categories::find($request->id) : new Categories ;  
        return view('admin.categories.create',['categories'=>$categories]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if($validator->fails())
        {
            return redirect()->route('admin.categories.form',['id'=>$request->id])->withErrors($validator)->withInput();
        } 
        $categories = $request->id ? Categories::findOrFail($request->id) : new Categories;
        $categories->name = $request->name;
        $categories->status = $request->status;
        if ($files = $request->file('image')) 
        {
            $destinationPath = public_path('categories/'); // upload path
            $categoriesImage = time() . "." . $files->getClientOriginalName();
            $files->move($destinationPath, $categoriesImage);
            // old_file_remove('categories',$user->profile_pic);
            $categories->image = $categoriesImage;    
        }
        $categories->save();

        $message = $request->id ? "Categories Updated Successfully" :"New Categories Created Successfully";
        return redirect()->route('admin.categories.index')->with('message', $message );
    }

    public function destroy($id)
    {   
        $categories = Categories::findOrFail($id);
        $categories->is_deleted = 1;
        $categories->save();
        return redirect()->route('admin.categories.index')->with('message',"Categories Deleted Successfully");
      
    }

    public function alldeletes(Request $request)
    {   
        $multiId = $request->id; 
        foreach ($multiId as $singleId) 
        {
            $categories = Categories::findOrFail($singleId);
            $categories->is_deleted = 1;
            $categories->save();
        }     
    }
    public function changeStatus($id, Request $request)
    {   
        $categories = Categories::findOrFail($id);
        $categories->status = $request->input('status');
        $categories->save();
    }
    
}