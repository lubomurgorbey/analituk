<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Sites;
use Illuminate\Pagination\{Paginator, LengthAwarePaginator};
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $request->flash();
        $search=$request->input('search');
        $normalQuery = Sites::orderBy('created_at','DESC');
        $normalQuery->where('site_name','=',"$search%")->orWhere('domain','like',"$search%");
        $page = $request->input('page', 1);
        $limit = 12;
        $mytime = Carbon::now();
        $countAll = $normalQuery->count();
        $sites = $normalQuery->offset($limit * ($page - 1))->limit($limit)->get();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagination = new LengthAwarePaginator($sites, $countAll, $limit, $page,['path'=>'']);
        return view('index', compact('sites', 'pagination','mytime'));
    }
    public function addSite(Request $request)
    {
        $validator= $request->validate([
            'site_name' => 'required|unique:sites',
            'domain' => 'required|unique:sites',
        ]);
        $messages = $validator->messages();
        $sites = Sites::create([
            'site_name' => $request['site_name'],
            'domain' => $request['domain'],
            'status' => $request->input('status','off'),
        ]);
        $sites -> save();
        return redirect('/');
    }
    public function deleteSite(Request $request, $id)
    {
        $sites= Sites::where('id', $id)->firstOrFail();
        $sites->delete();
        return response()->json(['success' => true]);
    }
    public function pages(Request $request, $id)
    {
        $request->flash();
        $site_id = $id;
        $search = $request->input('search');
        $get_http = $request->input('status');
        $normalQuery = Pages::where('site_id', $site_id);
        if(!empty($get_http)){
            $normalQuery->where(function ($query) use ($get_http) {
                $query->whereIn('http_code', $get_http);
            });
        }
        if (!empty($search)) {
            $normalQuery->where(function ($query) use ($search) {
                $query->where('http_code', 'like', "$search%")
                    ->orWhere('domain', 'like', "$search%");
            });
        }
        $avgTime = Pages::avg('total_time');
        $maxTime = Pages::whereRaw('total_time = (select max(`total_time`) from pages)')->first();
        $statusCode = Pages::select('http_code', DB::raw('count(http_code) as total'))->where('site_id', $site_id)->groupBy('http_code')->get();
        $page = $request->input('page', 1);
        $limit = 12;
        $mytime = Carbon::now();
        $countAll = $normalQuery->count();
        $sites = $normalQuery->offset($limit * ($page - 1))->limit($limit)->get();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagination = new LengthAwarePaginator($sites, $countAll, $limit, $page, ['path' => '']);
        return view('pages', compact('sites', 'pagination', 'mytime', 'site_id','avgTime','maxTime','statusCode'));
    }
    public function scanSite(Request $request, $id)
    {
        $site = Sites::findOrFail($id);
        Artisan::call('parse:build', ['--id' => $site->id]);
        return response()->json(['success' => true]);
    }
}
