<?php

namespace App\Http\Controllers;



use http\Env\Request;

class HomeController extends Controller
{
    //
    private $wuzzuf;
    private $tanqeeb;
    public $wuzzuf_jobs = array();
    public $tanqeeb_jobs = array();

    public $holder;


    public function index(){

        $this->wuzzuf = new wuzzuf();
        $this->tanqeeb = new tanqeeb();
        $this->wuzzuf_jobs = $this->wuzzuf->index();
        $this->tanqeeb_jobs = $this->tanqeeb->index();

        $this->merge();

        return view('index')->with('jobs',$this->holder);


    }

    private function merge(){

        $this->holder = array_merge($this->wuzzuf_jobs , $this->tanqeeb_jobs);

    }

    public function job(Request $request,$id){
        $job_ar = $this->holder[$id];
        print_r($job_ar);
//        if ($job_ar["website"] == "WUZZUF"){
//            $this->wuzzuf->job($job_ar);
//        }elseif ($job_ar["website"] == "TANQEEB"){
//            $this->tanqeeb->job($job_ar);
//        }
    }
}
