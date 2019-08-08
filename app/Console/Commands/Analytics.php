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
    protected $signature = 'parse:build {--url=} {--csvfile=}';

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

        $url=$this->option('url');
        $url=$normalQuery = Sites::where('status', 'on')->get();
//        dispatch(new ConvertDocument($url))->onQueue('convert');
        foreach ($url as $item) {
            $get_url=$item->domain."sitemap.xml";
            $site_id=$item->id;
//            dd($site_id);
            $xml = simplexml_load_file($get_url);
            var_dump($xml);
            foreach ($xml as $item) {
                $xml_list = $item->loc[0]->__toString();
//                $xml_list='https://instructions-and-manuals.com/sitemaps/sitemap_2.xml';
                $xml_content = simplexml_load_file($xml_list);
                foreach ($xml_content as $item) {
                    $xml_url = $item->loc[0]->__toString();
                    dispatch(new ProcessPodcast($xml_url,$site_id))->onQueue('analytics');
//                dd($xml_url);
//                $xml_url="http://instructions-and-manuals.com/10042-kawai-r-50-manual?page=58";
//                    $test = "/<a href=\"(.*?)\"\s*class=\"page-link/";
//                dd($test);
//                    $pagination = $this->getPagination($xml_url, $test);
//                    dd($pagination);
//                dd($pagination);
//                    $ch = curl_init($xml_url);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
//                    curl_setopt($ch, CURLOPT_COOKIEJAR, $config);
//                    curl_setopt($ch, CURLOPT_COOKIEFILE, $config);
//                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//                    $mas_html = curl_exec($ch);
//                    $info = curl_getinfo($ch);
//                dd($info);
//                    curl_close($ch);
//                    preg_match_all("/class=\"page-link\"\s*\S*\">(.*?)<\/a/", $mas_html, $get_url);
//                    preg_match_all("/<a href=\"(.*?)\"\s*class=\"page-link/", $mas_html, $get_pagination);
//                    $total_time = $info["total_time"];
//                    $status = $info["http_code"];
//                    $size = $info["size_download"];
//                    $all_url = [];
//                $pagination=isset($get_pagination[1][0])? $get_pagination[1][0] : false;
//                dd($pagination);
//                    if ($pagination != false) {
//                        dd('Тест');
//                        for ($i = 2; $pagination != false; $i++) {
//                            $all_url[] = $xml_url . '?page=' . $i;
//                            $xml_ur_pages = $xml_url . '?page=' . $i;
//                    dd($xml_ur_pages);
//                            $ch1 = curl_init($xml_ur_pages);
//                            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
//                            curl_setopt($ch1, CURLOPT_USERAGENT, $agent);
//                            curl_setopt($ch1, CURLOPT_COOKIEJAR, $config);
//                            curl_setopt($ch1, CURLOPT_COOKIEFILE, $config);
//                            curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, true);
//                            $max_html = curl_exec($ch1);
//                            $info = curl_getinfo($ch1);
//                    dd($info);
//                            curl_close($ch1);
//                            $total_time = $info["total_time"];
//                            $status = $info["http_code"];
//                            $size = $info["size_download"];
//                            $redirect = $info["redirect_count"];
//                            preg_match_all("/<a href=\"(.*?)\"\s*class=\"page-link/", $max_html, $get_pagination);
//                    if($redirect==1|| $pagination=isset($get_pagination[1][0])? $get_pagination[1][0] : false) break;
//                            $xml_ur_pages = $get_pagination[1][0] ? $get_pagination[1][0] : false;
//                            var_dump($xml_ur_pages);
//                            if ($xml_ur_pages == false) break;
//                            var_dump($xml_ur_pages, $redirect);
//                            $this->saveAnalysis($xml_ur_pages, $site_id);
//                        }
//                    }
//                    $this->saveAnalysis($xml_url,$site_id);
                }
            }
        }
    }

}
