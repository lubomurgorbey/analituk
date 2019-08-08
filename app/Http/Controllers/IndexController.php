<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Sites;
use Illuminate\Pagination\{Paginator, LengthAwarePaginator};
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $request->flash();
//        dd($request);
//        if($request->post()){
//            $sites = Sites::create([
//                'site_name' => $request['site_name'],
//                'domain' => $request['domain'],
//                'status' => $request->input('status','off'),
//
//
//            ]);
//        }
        $search=$request->input('search');
//    dd($search);
        $normalQuery = Sites::orderBy('created_at','DESC');
//        dd( $normalQuery);
        $normalQuery->where('site_name','=',"$search%")->orWhere('domain','like',"$search%");
//        dd( $normalQuery);
        $page = $request->input('page', 1);
//        dd(  $page);
        $limit = 12;
        $mytime = Carbon::now();
        $countAll = $normalQuery->count();
        $sites = $normalQuery->offset($limit * ($page - 1))->limit($limit)->get();
//        dd(  $sites);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
//        dd(  $currentPage);
        $pagination = new LengthAwarePaginator($sites, $countAll, $limit, $page,['path'=>'']);
//      dd(  $pagination);
        return view('index', compact('sites', 'pagination','mytime'));
    }

    public function addSite(Request $request)
    {
//dd('какого');
        $sites = Sites::create([
            'site_name' => $request['site_name'],
            'domain' => $request['domain'],
            'status' => $request->input('status','off'),


        ]);
        $sites -> save();
        return redirect('/');
//        return response()->json(['status' => true]);
    }
       public function pages(Request $request, $id)
       {
        $request->flash();
//        dd($id);
        $site_id=$id;
//        dd($site_id);
        $search=$request->input('search');
//        $normalQuery = Pages::orderBy('created_at','DESC')->where('site_id',  "1");
        $normalQuery = Pages::where('site_id', $site_id);
           if(!empty($search)){
               $normalQuery->where(function ($query) use ($search) {
                   $query->where('http_code','like',"$search%")
                       ->orWhere('domain','like',"$search%");
               });
           }

        $page = $request->input('page', 1);
        $limit = 12;
        $mytime = Carbon::now();
        $countAll = $normalQuery->count();
        $sites = $normalQuery->offset($limit * ($page - 1))->limit($limit)->get();
//        dd($sites);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagination = new LengthAwarePaginator($sites, $countAll, $limit, $page,['path'=>'']);
        return view('pages', compact('sites', 'pagination','mytime','site_id'));
       }
}
