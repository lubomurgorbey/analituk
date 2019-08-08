<?php

namespace App\Jobs;

use App\Models\Pages;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $url;
    protected $site_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($xml_url,$site_id)
    {
        $this->url = $xml_url;
        $this->site_id = $site_id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $agent ='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
        $config = '/tmp/cookies.txt';


//                dd($xml_url);
//                $xml_url="http://instructions-and-manuals.com/10042-kawai-r-50-manual?page=58";
                    $test = "/<a href=\"(.*?)\"\s*class=\"page-link/";
//                dd($test);
                    $pagination = $this->getPagination($this->url, $test);
//                    dd($pagination);
//                dd($pagination);
                    $ch = curl_init($this->url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                    curl_setopt($ch, CURLOPT_COOKIEJAR, $config);
                    curl_setopt($ch, CURLOPT_COOKIEFILE, $config);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    $mas_html = curl_exec($ch);
                    $info = curl_getinfo($ch);
//                dd($info);
                    curl_close($ch);
                    preg_match_all("/class=\"page-link\"\s*\S*\">(.*?)<\/a/", $mas_html, $get_url);
                    preg_match_all("/<a href=\"(.*?)\"\s*class=\"page-link/", $mas_html, $get_pagination);
                    $total_time = $info["total_time"];
                    $status = $info["http_code"];
                    $size = $info["size_download"];
                    $all_url = [];
//                $pagination=isset($get_pagination[1][0])? $get_pagination[1][0] : false;
//                dd($pagination);
                    if ($pagination != false) {
//                        dd('Тест');
                        for ($i = 1; $pagination != false; $i++) {
                            $all_url[] = $this->url . '?page=' . $i;
                            $xml_ur_pages = $this->url . '?page=' . $i;
//                    dd($xml_ur_pages);
                            $ch1 = curl_init($xml_ur_pages);
                            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch1, CURLOPT_USERAGENT, $agent);
                            curl_setopt($ch1, CURLOPT_COOKIEJAR, $config);
                            curl_setopt($ch1, CURLOPT_COOKIEFILE, $config);
                            curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, true);
                            $max_html = curl_exec($ch1);
                            $info = curl_getinfo($ch1);
//                    dd($info);
                            curl_close($ch1);
                            $total_time = $info["total_time"];
                            $status = $info["http_code"];
                            $size = $info["size_download"];
                            $redirect = $info["redirect_count"];
                            preg_match_all("/<a href=\"(.*?)\"\s*class=\"page-link/", $max_html, $get_pagination);
//                    if($redirect==1|| $pagination=isset($get_pagination[1][0])? $get_pagination[1][0] : false) break;
                            $xml_ur_pages = $get_pagination[1][0] ? $get_pagination[1][0] : false;
                            var_dump($xml_ur_pages);
                            if ($xml_ur_pages == false) break;
                            var_dump($xml_ur_pages, $redirect);
                            $this->saveAnalysis($xml_ur_pages, $this->site_id);
                        }
                    }
                    $this->saveAnalysis($this->url,$this->site_id);
        //
    }
    protected  function saveAnalysis($url,$site_id){
        $agent ='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
        $config = '/tmp/cookies.txt';
        $ch2 = curl_init($url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch2, CURLOPT_COOKIEJAR, $config);
        curl_setopt($ch2, CURLOPT_COOKIEFILE, $config);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
        $mas_html = curl_exec($ch2);
        $info = curl_getinfo($ch2);
        $total_time=$info["total_time"];
        $status=$info["http_code"];
        $size=$info["size_download"];
        $url_list=$info["url"];
        curl_close($ch2);
        var_dump($url_list);
        var_dump($url);
        $time = Carbon::now();
        $pages = Pages::updateOrCreate(
            [
                'domain' =>$url_list,
            ],
            [
                'total_time' => $total_time,
                'http_code' => $status,
                'size'=> $size,
                'last_check'=> $time,
                'site_id'=>$site_id,
            ]
        );
    }
    protected function  getPagination($url, $content){
        $agent ='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
        $config = '/tmp/cookies.txt';
        $ch1 = curl_init($url);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch1, CURLOPT_COOKIEJAR, $config);
        curl_setopt($ch1, CURLOPT_COOKIEFILE, $config);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, true);
        $mas_html = curl_exec($ch1);
        $info = curl_getinfo($ch1);
        curl_close($ch1);
        preg_match_all( $content, $mas_html, $get_pagination);
        $pagination=isset($get_pagination[1][0])? $get_pagination[1][0] : false;
//        dd($pagination);
        return $pagination;
    }
}
