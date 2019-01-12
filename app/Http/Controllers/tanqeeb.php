<?php

namespace App\Http\Controllers;


use Sunra\PhpSimple\HtmlDomParser;

class tanqeeb extends Controller
{
    protected $job = array();
    //
    public function index(){

        $this->Link_Parse();

        return $this->job;
    }

    public function SearchParse(){

        $tanqeeb_links = array();
        $url = "https://www.tanqeeb.com/ar/jobs/search?keywords=php+developer&countries%5B%5D=213&search_period=&order_by=most_recent&lang=all&search_in=f";
        $dom = HtmlDomParser::file_get_html( html_entity_decode( $url ) );
        $i=0;
        foreach($dom->find("div#jobs_list") as $element){
            foreach ($element->find("div.job-box") as $link){
                $tanqeeb_links[$i] =   "https://www.tanqeeb.com".$link->find('a',0)->href ;
                $this->job[$i]["job_data"] = $link->find("div.meta-desc",0)->plaintext;
                $i++;
            }
        }
        return $tanqeeb_links;
    }

    public function Link_Parse(){
        $links = $this->SearchParse();

        for ($i=0 ; $i<count($links) ; $i++){
            $dom = HtmlDomParser::file_get_html( html_entity_decode( $links[$i] ) );
            $element = $dom->find("div.col-xs-12",1) ;

            $this->job[$i]["link"] = $links[$i];
            $this->job[$i]["website"] = "TANQEEB";
            $this->job[$i]["job_title"] =  $element->find("h1.english_text",0)->plaintext;

            $element = $dom->find("table.job-details-table",0) ;
            $this->job[$i]["company_location"] =  $element->find("td",1)->plaintext;
            $this->job[$i]["company_name"] =  $element->find("td",2)->plaintext;

            $this->job[$i]["description"] =  $dom->find("div.job-details",0);

        }
    }

    public function job(array $job){
        return view('tanqeeb.job');
    }


}
