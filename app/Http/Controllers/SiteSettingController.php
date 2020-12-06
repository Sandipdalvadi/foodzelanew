<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSettings;
use Validator;
class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $siteSettings = SiteSettings::first();
        if(empty($siteSettings)){
            $siteSettings = new SiteSettings;
        }
        return view('admin.sitesetting.form',['siteSettings'=>$siteSettings]);
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'map_api_key'=>'required|max:255',
        ]);
        
        if($validator->fails())
        {
            return redirect()->route('admin.sitesetting.index')->withErrors($validator)->withInput();
        } 
        $siteSettings = SiteSettings::first();
        if(empty($siteSettings)){
            $siteSettings = new SiteSettings;
        }
        if ($files = $request->file('logo')) 
        {
            $destinationPath = public_path('sitesetting/');
            $logoImage = time() . "." . $files->getClientOriginalName();
            $files->move($destinationPath, $logoImage);


            old_file_remove('sitesetting',$siteSettings->logo);
            $siteSettings->logo = $logoImage;    
        }

        if ($files = $request->file('dark_logo')) 
        {
            $destinationPath = public_path('sitesetting/');
            $logoImage = time() . "." . $files->getClientOriginalName();
            $files->move($destinationPath, $logoImage);


            old_file_remove('sitesetting',$siteSettings->dark_logo);
            $siteSettings->dark_logo = $logoImage;    
        }
        if ($files = $request->file('favicon_logo')) 
        {
            $destinationPath = public_path('sitesetting/');
            $logoImage = time() . "." . $files->getClientOriginalName();
            $files->move($destinationPath, $logoImage);


            old_file_remove('sitesetting',$siteSettings->favicon_logo);
            $siteSettings->favicon_logo = $logoImage;    
        }
        
        $siteSettings->map_api_key = $request->map_api_key;
        $siteSettings->save();
        $message = "Setting Updated Successfully";
        return redirect()->route('admin.sitesetting.index')->with('message', $message );
    }
}
