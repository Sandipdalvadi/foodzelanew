<?php 

namespace App\Http\Controllers;

use App\Models\Restaurents;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Auth, Hash, DB, Lang, URL;

class RestaurentsController extends Controller
{
    public function index()
    { 

        return view('admin.restaurents.index');
    }

    public function list(Request $request)
    {
        $columns = array( 
            0 =>'id', 
            1 =>'id', 
            2 =>'name',
            3 =>'name',
            4 =>'name',
            5 =>'name',
            6 =>'name',
        );
  
        $status = $request->input('status');
        $totalData = Restaurents::where('is_deleted',0);
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

            $posts =  Restaurents::where('is_deleted',0);
            if($status != 3){
                $posts = $posts->where('status',$status);
            }
            $posts = $posts->where(function($q) use($search) {
                $q->Where('id', 'LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%"); 
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Restaurents::where('is_deleted',0);
            if($status != 3){
                $totalFiltered = $totalFiltered->where('status',$status);
            }
            $totalFiltered = $totalFiltered->where(function($q) use($search) {
                $q->Where('id', 'LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%"); 
            })
            ->count();
        }   
        else
        {            
            $posts = Restaurents::where('is_deleted',0);
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
                $name = $post->name ? $post->name : '';
                $status0 = $status1 = $status2 = "";
                $status0 = $post->status == 0 ? 'selected' : ''; 
                $status1 = $post->status == 1 ? 'selected' : ''; 
                $status2 = $post->status == 2 ? 'selected' : ''; 
                $liceneseDelivery = '<img style="width:50px;height:50px;" class="b-r-10" src="'.file_exists_in_folder('liceneseDelivery', $post->licenese_delivery).'" alt="" />';
                $certificationShop = '<img style="width:50px;height:50px;" class="b-r-10" src="'.file_exists_in_folder('certificationShop', $post->certification_shop).'" alt="" />';
                $ownerLogo = '<img style="width:50px;height:50px;" class="b-r-10" src="'.file_exists_in_folder('ownerLogo', $post->owner_logo).'" alt="" />';
                
                $data['checkdata']="<input type='checkbox' class='case' id='$post->id' name='case' value='$post->id'>";
                $data['id'] = $post->id;
                $data['name'] = $name;
                $data['licenese_delivery'] = $liceneseDelivery;
                $data['certification_shop'] = $certificationShop;
                $data['owner_logo'] = $ownerLogo;
                $data['status'] = "<select class='js-example-basic-single form-control' onchange=changeStatus(this,'".route('admin.restaurents.changeStatus',['id'=>$post->id])."')>
                <option value='0' ".$status0.">In Active</option>
                <option value='1' ".$status1.">Active</option>
                <option value='2' ".$status2.">Pending</option></select>";
                
                $data['action'] = "<div style='display: flex;'>
                <a class='btn btn-primary' href=".route('admin.restaurents.detail',['id'=>$post->id]).">Detail<i data-feather='eye'></i></a>
                <form style='float:left;margin-left:6px;' method='POST' action=".route('admin.restaurents.delete',['id'=>$post->id]).">";
               
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
        $restaurents = Restaurents::findOrFail($id);
        $restaurents->is_deleted = 1;
        $restaurents->save();
        return redirect()->route('admin.restaurents.index')->with('message',"Restaurents Deleted Successfully");
      
    }
    public function detail($id)
    {   
        $restaurents = Restaurents::findOrFail($id);
        return view('admin.restaurents.detail');
      
    }

    public function alldeletes(Request $request)
    {   
        $multiId = $request->id; 
        foreach ($multiId as $singleId) 
        {
            $restaurents = Restaurents::findOrFail($singleId);
            $restaurents->is_deleted = 1;
            $restaurents->save();
        }     
    }
    public function changeStatus($id, Request $request)
    {   
        $restaurents = Restaurents::findOrFail($id);
        $restaurents->status = $request->input('status');
        $restaurents->save();
    }
}