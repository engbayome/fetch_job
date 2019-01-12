<?php

namespace App\Http\Controllers;


use Sunra\PhpSimple\HtmlDomParser;

class wuzzuf extends Controller
{

    protected $job = array();
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->Link_Parse();

        return $this->job;

    }



    /**
     * @return array of links from search page
     *
     */
     private function SearchParse(){
        $wuzzuf_links = array();
        $url = "https://wuzzuf.net/search/jobs?q=php+developer";
        $dom = HtmlDomParser::file_get_html( html_entity_decode( $url ) );
        // Find all links
         $i=0;
         foreach($dom->find("div.item-details") as $element){
             foreach ($element->find('h2.job-title') as $link){
                 $wuzzuf_links[$i] =   $link->find('a',0)->href ;
                 $i++;
             }
         }
         return $wuzzuf_links;
    }

    /**
     *
     */
    private function Link_Parse(){
         $links = $this->SearchParse();

         for ($i=0 ; $i<count($links) ; $i++){
             $dom = HtmlDomParser::file_get_html( html_entity_decode( $links[$i] ) );
             $element = $dom->find("div.job-main-card",0) ;
             $this->job[$i]["link"] = $links[$i];
             $this->job[$i]["website"] = "WUZZUF";
             $this->job[$i]["job_title"] =  $element->find("h1.job-title",0)->plaintext;
             $this->job[$i]["company_name"] =  $element->find("p.job-subinfo",0)->plaintext;
             $this->job[$i]["company_location"] =  $element->find("span.job-company-location",0)->plaintext;
             $this->job[$i]["company_img"] = $element->find("img",0);
             $this->job[$i]["job_data"] = $element->find("p.job-post-date ",0)->title;

             $element = $dom->find("div.about-job",0);
             $table =  $element->find("div.job-summary",0)->find("table.table",0);
             $this->job[$i]["exp"] = $table->find("dd",0)->plaintext;
             $this->job[$i]["career_level"] = $table->find("dd",1)->plaintext;
             $this->job[$i]["job_type"] = $table->find("dd",2)->plaintext;
             $this->job[$i]["salary"] = $table->find("dd",3)->plaintext;
             $this->job[$i]["vacancies"] = $table->find("dd",4)->plaintext;

             $desc = $element->find("span[itemprop=description]",0)->innertext();
             $this->job[$i]["description"] = strstr($desc ,"<div class=\"labels-wrapper\">",true);

             $element = $dom->find("div.job-requirements",0);
             $this->job[$i]["requirements"] = $element->find("span[itemprop=responsibilities]",0)->innertext();

         }

    }

    public function job(array $job){
        return view('wuzzuf.job');
    }

}

