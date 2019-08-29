<?php

namespace App\Console\Commands;

use App\Jobs\ConvertDocument;
use App\Jobs\ProcessParse;
use App\Jobs\ProcessPodcast;
use App\Models\Pages;
use App\Models\Parse;
use App\Models\Sites;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Analytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:build {--url=} {--csvfile=} {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function filter($string){
        return html_entity_decode($string, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $agent ='Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
        $config = '/tmp/cookies.txt';

        $url = $this->option('url');
        $id = $this->option('id');

        $urls = $id ? Sites::where('id', $id)->get() : Sites::where('status', 'on')->get();

//        foreach ($urls as $item) {
//            $get_url=$item->domain."sitemap.xml";
            $get_url='https://instructions-and-manuals.com/sitemaps/sitemap_documents.xml';
            $site_id=1;
            $xml = simplexml_load_file($get_url);
            foreach ($xml as $item) {
                $xml_list = $item->loc[0]->__toString();
                $gzip_content=gzopen($xml_list,'r');
                $contents = gzread($gzip_content, 125257900);
                preg_match_all("/ <loc>(.*?)</",   $contents,  $xml_content );
                foreach ($xml_content[1] as $item) {
                    dispatch(new ProcessPodcast($item ,$site_id))->onQueue('analytics');
//            }
                }
//                $test=gzopen($xml_list,'r');
//                $contents = gzread($test, 125257900);

//                preg_match_all("/ <loc>(.*?)</",   $contents, $tes);
//                $xml_content = simplexml_load_file($test);
//                foreach (  $tes[1] as $item) {
////////                    $xml_url = $item->loc[0]->__toString();
//                    $this->saveAnalysis($item ,$site_id);
//            }
        }
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
//        dd( $info);
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
}
