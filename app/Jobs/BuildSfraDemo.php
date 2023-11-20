<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use Log;
use Mail;
use Storage;

class BuildSfraDemo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $current_folder_id = null;
    protected $config;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->current_folder_id = "workspace/results/f" . uniqid();
    }

  /**
   * Send notification email
   */
  private function  sendSuccessEmail(){
    $to_name = $this->config['user_email'];
    $to_email = $this->config['user_email'];

    // hard coded the store ID for the SFRA store below
    $data = 'SFRA';

    Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
      $message->to($to_email, $message)
              ->subject('Your custom SFRA site is ready');
      $message->from('sfrademobuilder@gmail.com','SFRA Demo Builder');
      });
  }

  /**
   * Copy xml files - this is to copy the sfra catalog, prices and inventory to the cartridge that should be deployed
   *
   */
  private function copyXMLFiles(){

    $storeName = "SFRA";

    $dest1 = $this->current_folder_id . "/site/$storeName/catalogs/apparel-m-catalog/catalog.xml";
    $dest2 = $this->current_folder_id . "/site/$storeName/catalogs/electronics-m-catalog/catalog.xml";
    $dest3 = $this->current_folder_id . "/site/$storeName/catalogs/storefront-catalog-m-en/catalog.xml";
    $dest4 = $this->current_folder_id . "/site/$storeName/catalogs/storefront-catalog-m-en/catalog.xml";
    Storage::copy('workspace/xml/catalog/apparel-m-catalog/catalog.xml', $dest1);
    Storage::copy('workspace/xml/catalog/electronics-m-catalog/catalog.xml', $dest2);
    Storage::copy('workspace/xml/catalog/storefront-catalog-m-en/catalog.xml', $dest3);
    Storage::copy('workspace/xml/catalog/storefront-catalog-m-en/catalog.xml', $dest4);

    //$dest = $this->current_folder_id . "/site/$storeName/inventory-lists/inventory.xml";
    //Storage::put($dest, $xml);

    //$dest = $this->current_folder_id . "/site/$storeName/pricebooks/pricebook.xml";
    //Storage::put($dest, $xml);

    //$dest = $this->current_folder_id . "/job/$storeName-jobs.xml";
    //Storage::put($dest, $xml);
  }

  /**
   * Build results folder structure
   *
   */
  private function initResultsFolder(){
    $storeName = "SFRA";
    copyStorageFolder('workspace/template_base/site', $this->current_folder_id. "/site/$storeName",$storeName);
  }

  /**
   * Upload files to a webdav folder
   */
  private function uploadFilesToWebDav($source, $subpath) {

      $baseUrl = $this->config['sandbox_base_url'] . "/on/demandware.servlet/webdav/Sites";
      $settings = array(
          'baseUri' => $baseUrl . "/$subpath/",
          'userName' => $this->config['sandbox_username'],
          'password' => $this->config['sandbox_password']
      );

      $client = new \Sabre\DAV\Client($settings);
      $tmpsource = storage_path($source);
      if(is_dir($tmpsource)){
        $files_array = \Storage::allFiles($source);
        foreach ($files_array as $file) {
          $target_filename = str_replace("$source/","",$file);
          $upload_result = $client->request('PUT', $target_filename, Storage::get($file));
        }
      }else{
        $target_filename = basename($source);
        $upload_result = $client->request('PUT', $target_filename, Storage::get($source));
      }

  }

  private function uploadCartridges(){

     $code_version = $this->config['sandbox_code_version'];

     /**
     * Upload sfra cartdridge if not installed yet
     */
    $sfra_folder = "workspace/template_base/sfra";

    $reqData=[
      'url'=> $this->config['sandbox_base_url'],
      'username'=> $this->config['sandbox_username'],
      'password' => $this->config['sandbox_password'],
    ];

    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/modules");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/modules", "Cartridges/" . $code_version ."/modules" );
    }
    Log::info('*** Passed the modules upload ***');
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/app_storefront_base");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/app_storefront_base", "Cartridges/" . $code_version ."/app_storefront_base" );
    }
    Log::info('*** Passed the app_storefront_base upload ***');
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/bm_app_storefront_base");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/bm_app_storefront_base", "Cartridges/" . $code_version ."/bm_app_storefront_base" );
    }
    Log::info('*** Passed the bm_app_storefront_base upload ***');
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/bm_pd_se_lib");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/bm_pd_se_lib", "Cartridges/" . $code_version ."/bm_pd_se_lib" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/plugin_pd_se_lib");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/plugin_pd_se_lib", "Cartridges/" . $code_version ."/plugin_pd_se_lib" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/plugin_cartridge_merge");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/plugin_cartridge_merge", "Cartridges/" . $code_version ."/plugin_cartridge_merge" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/plugin_instorepickup");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/plugin_instorepickup", "Cartridges/" . $code_version ."/plugin_instorepickup" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/plugin_wishlists");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/plugin_wishlists", "Cartridges/" . $code_version ."/plugin_wishlists" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/plugin_giftregistry");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/plugin_giftregistry", "Cartridges/" . $code_version ."/plugin_giftregistry" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/lib_productlist");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/lib_productlist", "Cartridges/" . $code_version ."/lib_productlist" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/plugin_sitemap");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/plugin_sitemap", "Cartridges/" . $code_version ."/plugin_sitemap" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/plugin_applepay");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/plugin_applepay", "Cartridges/" . $code_version ."/plugin_applepay" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/plugin_datadownload");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/plugin_datadownload", "Cartridges/" . $code_version ."/plugin_datadownload" );
    }
  }

  /**
   * Send notification email
   */
  private function  sendErrorEmail($title,$trace){
    $to_name = $this->config['user_email'];
    $to_email = $this->config['user_email'];
    $data = array('title'=>$title,'trace'=>$trace);

    Mail::send('emails.mail-error', $data, function($message) use ($to_name, $to_email) {
      $message->to($to_email, $to_name)
              ->subject('Your custom SFRA site build failed');
      $message->from('sfrademobuilder@gmail.com','SFRA Demo Builder');
      });

  }

  /**
   * Send message to DB technical manager
   */
  private function  sendInformationEmail(){

    $to_name = config('demo.technical_manager_email');
    $to_email = config('demo.technical_manager_email');
    $data = array('email'=>$this->config['user_email'],'url'=>$this->config['store_root_url']);

    Mail::send('emails.mail-infos', $data, function($message) use ($to_name, $to_email) {
      $message->to($to_email, $to_name)
              ->subject('New Demo from DB');
      $message->from('sfrademobuilder@gmail.com','SFRA Demo Builder');
      });

  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle(){
    try {

      set_time_limit(300);

      $this->initResultsFolder();
      //$this->copyXMLFiles();
      $this->uploadCartridges();
      $this->sendSuccessEmail();
      $this->sendInformationEmail();

     } catch (\Exception $e) {
       Log::error($e->getTraceAsString());
       error_log($e->getTraceAsString());
       $this->sendErrorEmail($e->getMessage(),$e->getTraceAsString());
    }
  }
}
