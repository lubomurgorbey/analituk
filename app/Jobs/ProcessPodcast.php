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

//        $agent ='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
//        $config = '/tmp/cookies.txt';
//        $gzip_content=gzopen($this->url,'r');
//        $contents = gzread($gzip_content, 125257900);
//        preg_match_all("/ <loc>(.*?)</",   $contents,  $xml_content );

            $this->saveAnalysis($this->url ,$this->site_id);
//            }



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
//        var_dump($url);
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
}
