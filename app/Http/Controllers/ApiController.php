<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Log;
use Illuminate\Support\Str;
use App\Jobs\BuildDemo;
use App\Jobs\BuildSfraDemo;
use Mail;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    /**
     * API to check selector
     * Get all elements corresponding to the selector string
     * that are present in the provided page
     */
    public function checkSelector (Request $request){

        $request_data = $request->all();
        $validator = Validator::make($request_data, [
            'selector_string' => 'required',
            'selector_page_url' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors() ]);
        }

        $url = $request_data['selector_page_url'];
        $selector_array = explode('|',$request_data['selector_string']);
        $selector = trim($selector_array[0]);
        $custom_attribute = null;
        if(isset($selector_array[1])){
            $custom_attribute = trim($selector_array[1]);
        }
        $source = getPageFromUrl($url, $_SERVER['REMOTE_ADDR']);
        if($source===FALSE){
            return response('error', 200);
        }

        $html = str_get_html($source);

        $res = $html->find($selector);

        $arr=[];
        foreach($res as $index=>$elt){
            $title = null;
            if($custom_attribute){
                $title = $elt->getAttribute($custom_attribute);
            }
            if($elt->tag=="img"){
                $arr[]=['title'=>trim(!$title ? $elt->src : $title), 'url'=>realUrl((!$title ? $elt->src : $title),$url)];
            }elseif($elt->tag=="a"){
                $arr[]=['title'=>trim(!$title ? $elt->plaintext : $title), 'url'=>realUrl($elt->href,$url)];
            }else{
                $arr[]=['title'=>trim(!$title ? $elt->plaintext : $title), 'url'=>null];
            }

        }

        $html->clear();
        unset($html);

        //$arr = json_encode( utf8ize( $arr ) );

		return response()->json($arr, 200);
    }


    /**
     * API to Check sandbox/password connectivity
     */
    public function checkSandboxCredentials (Request $request){

        $request_data = $request->all();
        $validator = Validator::make($request_data, [
            'url' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors() ], 200);
        }

        $res = checkWebDavFolder($request_data,"/on/demandware.servlet/webdav/Sites/Cartridges");
        if($res=="OK"){
            return response()->json(['result' => "OK" ], 200);
        }else{
            return response()->json(['result' => "KO" ], 200);
        }

    }


    /**
     * API to start the BuildDemo Job
     */

    public function buildDemo (Request $request){

        $request_data = $request->all();
        $validator = Validator::make($request_data, [

            'store_name' => 'required',
            'store_id' => 'required',
            'store_locale' => 'required',
            'store_root_url' => 'required',

            'category_url' => 'required',
            'category_name' => 'required',
            'category_parent_name' => 'required',

            'selector_product_name' => 'required',
            'selector_product_short_description' => 'required',
            'selector_product_primary_price' => 'required',
            'selector_product_picture' => 'required',

            'sandbox_base_url' => 'required',
            'sandbox_username' => 'required',
            'selector_code_version' => 'required',
            'selector_password' => 'required',

        ]);

        if ($validator->fails()) {
            //return response()->json(['errors' => $validator->errors() ]);
        }

        $request_data['store_root_url'] = rtrim($request_data['store_root_url'],'/');
        $request_data['sandbox_base_url'] = rtrim($request_data['sandbox_base_url'],'/');

        $request_data['server_addr'] = $_SERVER['REMOTE_ADDR'];

        foreach ($request_data as $key => $value) {
            if(Str::startsWith($key, 'file_')){
                $extension = $request->file($key)->getClientOriginalExtension();
                $request_data[$key] = base64_encode(file_get_contents( $request->file($key)->getRealPath() ));
                $request_data["$key"."_extension"] = $extension;
            }
        }

        //get currency from provided locale
        $request_data['store_currency'] = config('demo.locales')[$request_data['store_locale']];
        $request_data['categories'] = $this->buildDemoCategoriesFromRequestData(
            $request_data['category_url'],
            $request_data['category_name'],
            $request_data['category_parent_name']
        );


        //Start Building process
         BuildDemo::dispatch($request_data);
         return response()->view('builder.custombuilder', ['is_success' => true,
                                                        'configuration_export'=>$request_data['configuration_export']]);

    }

    /**
     * API to start the BuildSFRA Job
     */

     public function buildSfraDemo (Request $request){

        $request_data = $request->all();
        $validator = Validator::make($request_data, [

            'sandbox_base_url' => 'required',
            'sandbox_username' => 'required',
            'selector_code_version' => 'required',
            'selector_password' => 'required',
            'store_root_url' => 'required',
        ]);

        if ($validator->fails()) {
            //return response()->json(['errors' => $validator->errors() ]);
        }
        $request_data['store_root_url'] = 'commercecloudrocks';
        $request_data['sandbox_base_url'] = rtrim($request_data['sandbox_base_url'],'/');
        $request_data['server_addr'] = $_SERVER['REMOTE_ADDR'];

        //Start Building process
         //Log::info('*** Before BuildSfraDemo process ***');
         BuildSfraDemo::dispatch($request_data);
         //Log::info('*** after BuildSfraDemo process ***');
         return response()->view('builder.sfrabuilder', ['is_success' => true,
                                                        'configuration_export'=>$request_data['configuration_export']]);

    }

    /**
     * Transform arrays of urls, names and parents into array of categories
     * [ ['url'=>'category_url],'path'=>['root','parent','category'], ...]
     */
    private function buildDemoCategoriesFromRequestData($categories_urls, $categories_names, $categories_parents_names ){
        $res =[];
        for ($i=0; $i < count($categories_urls); $i++) {

            //build path array
            $path=['root'];
            //if parent is set, push parent before current category
            if($categories_parents_names[$i]){
                $path[] = $categories_parents_names[$i];
            }
            $path[] = $categories_names[$i];

            $res[] = [
                'url'=>$categories_urls[$i],
                'path'=>$path,
            ];
        }
        return $res;
    }



}
