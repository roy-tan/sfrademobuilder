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

class BuildDemo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $products = [];
    protected $categories = [];
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
     * Main processing method
     *
     * @return void
     */
    private function processCategories(){
      $categories = $this->config['categories'];
      foreach ($categories as $category){
        $this->processCategory($category);
      }
    }

    /**
     * Process a category entry (url + path)
     *
     * @return void
     */
    private function processCategory($category){

      $current_category_id = $this->registerCategory($category['path']);//register category

      $max_products_count = $this->config['category_max_products_count'];
      $category_url = $category['url'];


      $source = getPageFromUrl($category_url,$this->config['server_addr']);
      if($source === FALSE){
        return;
      }
      $html = str_get_html($source);
      $selector_pdp_url = $this->config['selector_pdp_url'];


      $selector_array = explode('|',$selector_pdp_url);
      $real_selector = trim($selector_array[0]);
      $custom_attribute = null;
      if(isset($selector_array[1])){
          $custom_attribute = trim($selector_array[1]);
      }

      $products_elements = $html->find($real_selector);

      $products_count = min([count($products_elements),$max_products_count]);
      $current_count=0;
      foreach ($products_elements as $product_elt){
        if($current_count<$max_products_count){
          $href = ($custom_attribute ? $product_elt->getAttribute($custom_attribute) : $product_elt->href);
          $product_url = realUrl($href,$category_url);
          $this->processProduct($product_url,$current_category_id);
        }
        $current_count++;
      }
    }

    /**
     * Gets the category object by name
     */
    private function getCategoryByName($name){
      foreach ($this->categories as $key => $category) {
        if($category['name']==$name){
          return $category;
        }
      }
      return null;
    }

    /**
     * Registers a category using its path
     */
    private function registerCategory($category_paths){
      $count = count($category_paths);
      $parent_array = array_slice($category_paths, 1, $count-2);
      $parent_id = (count($parent_array) ? abc_string(implode("_", $parent_array)) : 'root');
      $parent_name = (count($parent_array) ? end($parent_array) : 'root');
      $parentCat = $this->getCategoryByName($parent_name);

      if(!$parentCat OR $parentCat['parent']=='root'){
        $parentCat = ['id'=>$parent_id,'name'=>$parent_name,'parent'=>'root'];
        $this->categories[$parent_id]=$parentCat;
      }

      $current_array = array_slice($category_paths, 1, $count-1);
      //$current_id = abc_string(implode("_", $current_array));
      $current_name = end($category_paths);
      $current_id = $parentCat['id'] . "_" . abc_string($current_name);
      $this->categories[$current_id]=['id'=>$current_id,'name'=>$current_name,'parent'=>$parentCat['id']];
      //Log::debug($this->categories);

      return $current_id;

    }

    /**
     * Process a produc
     */
    private function processProduct($product_url, $category_id){
      $source = getPageFromUrl($product_url,$this->config['server_addr']);
      if($source === FALSE){
        return;
      }

      $html = str_get_html($source);
      $index = count($this->products); // current number of products

      $new_product = [];
      $new_product['category'] = $category_id;
      $new_product['id'] = "P_" . $this->config['store_id'] . "_" . str_pad($index+1,6,"0",STR_PAD_LEFT);
      $new_product['sku'] = $new_product['id'];

      foreach ($this->config as $formfield => $selector) {

        if(Str::startsWith($formfield, 'selector_product')){
          $key = config("demo.products_selectors_map.$formfield");
          if($selector){

            $selector_array = explode('|',$selector);
            $real_selector = trim($selector_array[0]);
            $custom_attribute = null;
            if(isset($selector_array[1])){
                $custom_attribute = trim($selector_array[1]);
            }

            $el = $html->find($real_selector,0);
            if($el){
                //picture data
              if(strpos($key, 'picture') !== false) {
                $imagelink = ($custom_attribute ? $el->getAttribute($custom_attribute) : $el->src);
                $imagelink = realUrl($imagelink, $product_url );
                $new_product[$key] = trim($imagelink);
              //price data
              }elseif (strpos($key, 'price') !== false) {
                $price = ($custom_attribute ? $el->getAttribute($custom_attribute) : $el->plaintext);
                $price = htmlspecialchars(strip_tags(trim($price)));
                $price = trim($price);
                //$price = str_replace("€", ",", $price);
                $price = str_replace(",", ".", $price);
                $price = preg_replace("/[^0-9,.]/", "", $price);
                $price = (float)$price;
                if(!$price){
                  $price = rand(35, 110);
                }
                $new_product[$key] = $price;
              //other
              }else{
                $txt = ($custom_attribute ? $el->getAttribute($custom_attribute) : $el->plaintext);
                $txt = htmlspecialchars(strip_tags(trim($txt)));
                $txt = trim($txt);
                $new_product[$key] = ($txt);
              }
            }
          }
        }
      }

      if(!isset($new_product['name'])){
        $new_product['name'] = $new_product['id'];
      }

      if(!isset($new_product['price'])){
        $new_product['price'] = rand(20,200);
      }

      if(!isset($new_product['short-description'])){
        $new_product['short-description'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
      }

      if(!isset($new_product['long-description'])){
        $new_product['long-description'] = $new_product['short-description'];
      }

      if(!isset($new_product['brand'])){
        $new_product['brand'] = $this->config['store_name'];
      }

      if(!isset($new_product['manufacturer'])){
        $new_product['manufacturer'] = $this->config['store_name'];
      }

      $this->products[] = $new_product;

    }

  /**
   * Process all pictures
   */
  private function processPictures(){
    try {
    $base = $this->config['store_root_url'];
    $storeName = $this->config['store_id'];

    $finalSiteFolder = $this->current_folder_id . "/site/$storeName";

    foreach ($this->products as $key => $product) {
      //picture 1
      if(isset($product['picture'])){
        $picture_url = $product['picture'];
        Log::info('Product Picture picture_url'.$picture_url);
        $filename = basename($picture_url);
        Log::info('Product Picture filename'.$filename);
        $file_parts = pathinfo($filename);
        if(isset($file_parts['extension']) AND $file_parts['extension']!=''){
          $extension = $file_parts['extension'];
          if(Str::contains($extension, '?')){
            $arr = explode("?",$extension);
            $extension = $arr[0];
          }
        }else{
          $extension = "jpg";
        }
        $filename = uniqid() . ".$extension";
        $this->products[$key]["picture"] = "medium/" . $filename;
        $dest = $finalSiteFolder . "/catalogs/$storeName-Catalog/static/default/images/medium/" . $filename;
        $filecontent = getPageFromUrl($picture_url,$this->config['server_addr']);
        if(!($filecontent === FALSE)){
          Storage::put($dest, $filecontent);
        }
      }
      //picture 2
      if(isset($product['picture2'])){
        $picture_url = $product['picture2'];
        $filename = basename($picture_url);
        $file_parts = pathinfo($filename);
        if(isset($file_parts['extension']) AND $file_parts['extension']!=''){
          $extension = $file_parts['extension'];
          if(Str::contains($extension, '?')){
            $arr = explode("?",$extension);
            $extension = $arr[0];
          }
        }else{
          $extension = "jpg";
        }
        $filename = uniqid() . ".$extension";
        $this->products[$key]["picture2"] = "medium/" . $filename;
        $dest = $finalSiteFolder . "/catalogs/$storeName-Catalog/static/default/images/medium/" . $filename;
        $filecontent = getPageFromUrl($picture_url,$this->config['server_addr']);
        if(!($filecontent === FALSE)){
          Storage::put($dest, $filecontent);
        }
      }
    }
    }
    catch (\Exception $e) {
    Log::error($e->getTraceAsString());
    error_log($e->getTraceAsString());
    $this->sendErrorEmail($e->getMessage(),$e->getTraceAsString());
    }
  }

  /**
   * generate xml files
   *
   */
  private function generateXMLFiles(){

    $storeName = $this->config['store_id'];

    $xml = "<?xml version=\"1.0\" ?>\n" . view('templates.catalog')
      ->with('products',$this->products)
      ->with('categories',$this->categories)
      ->with('config',$this->config)
      ->render();
    $dest = $this->current_folder_id . "/site/$storeName/catalogs/$storeName-Catalog/catalog.xml";
    Storage::put($dest, $xml);

    $xml = "<?xml version=\"1.0\" ?>\n" . view('templates.inventory')
      ->with('products',$this->products)
      ->with('config',$this->config)
      ->render();
    $dest = $this->current_folder_id . "/site/$storeName/inventory-lists/inventory.xml";
    Storage::put($dest, $xml);

    $xml = "<?xml version=\"1.0\" ?>\n" . view('templates.pricesbook')
      ->with('products',$this->products)
      ->with('config',$this->config)
      ->render();
    $dest = $this->current_folder_id . "/site/$storeName/pricebooks/pricebook.xml";
    Storage::put($dest, $xml);

    $xml = "<?xml version=\"1.0\" ?>\n" . view('templates.jobs')
      ->with('config',$this->config)
      ->render();
    $dest = $this->current_folder_id . "/job/$storeName-jobs.xml";
    Storage::put($dest, $xml);
  }

  /**
   * Copy a file from a File field to a folder and returns the name of the file
   *
   */
  private function processFileField($fileField,$destinationPath,$fileName){
    $content = base64_decode($this->config[$fileField]);
    $extension = $this->config[$fileField."_extension"];
    $newFile = $fileName . ".$extension";
    Storage::put($destinationPath . "/" . $newFile,$content);
    return $newFile;
  }

  /**
   * Build results folder structure
   *
   */
  private function initResultsFolder(){
    $storeName = $this->config['store_id'];
    copyStorageFolder('workspace/template_base/cartridge/CustomDemo', $this->current_folder_id . "/cartridge/$storeName",$storeName);
    copyStorageFolder('workspace/template_base/site', $this->current_folder_id. "/site/$storeName",$storeName);

  }

  /**
   * Copy SFRA source and customize it
   *
   */
  private function buildNewSiteFromTemplate(){

    $storeName = $this->config['store_id'];

    $finalSiteFolder = $this->current_folder_id . "/site/$storeName/sites/$storeName";
    replaceStringsInStorageFile("$finalSiteFolder/site.xml", "CustomDemo_CURRENCY", $this->config["store_currency"]);
    replaceStringsInStorageFile("$finalSiteFolder/site.xml", "CustomDemo", $storeName);
    replaceStringsInStorageFile("$finalSiteFolder/preferences.xml", "CustomDemo_LOCALE", $this->config["store_locale"]);
    replaceStringsInStorageFile("$finalSiteFolder/preferences.xml", "CustomDemo", $storeName);

    //Home page products (random)
    $productsCount = count($this->products);
    $newSlotFile = "$finalSiteFolder/slots.xml";

    if($productsCount){
      $homeProductsIds = array_rand($this->products,5);
      for ($i=1; $i <=5 ; $i++) {
        replaceStringsInStorageFile($newSlotFile, "CustomDemoProduct_$i", $this->products[$homeProductsIds[$i-1]]['id']);
      }
    }

    replaceStringsInStorageFile($newSlotFile, "CustomDemo", $storeName);

    $newLibraryFile = $this->current_folder_id . "/site/$storeName/libraries/$storeName"."SharedLibrary/library.xml";
    $newLibaryImagesFolder = $this->current_folder_id . "/site/$storeName/libraries/$storeName"."SharedLibrary/static/default/images/sfrademobuilder";

    foreach (['top','bottom'] as $position) {
      $pictureName = $this->processFileField("file_home_".$position."_picture",$newLibaryImagesFolder,"home_$position");
      replaceStringsInStorageFile($newLibraryFile, "CustomDemo_" . $position . "Picture", $pictureName);
      replaceStringsInStorageFile($newLibraryFile, "CustomDemo_" . $position . "Title", $this->config["home_".$position."_title"]);
    }

    for ($i=1; $i <=4 ; $i++) {
      $pictureName = $this->processFileField("file_home_category_picture_$i",$newLibaryImagesFolder,"category_picture_$i");
      replaceStringsInStorageFile($newLibraryFile, "CustomDemoCategoryPicture_$i", $pictureName);
      replaceStringsInStorageFile($newLibraryFile, "CustomDemoCategoryTitle_$i", $this->config["home_category_title_$i"]);
      replaceStringsInStorageFile($newLibraryFile, "CustomDemoCategoryId_$i", getCategoryIdByName($this->config["home_category_title_$i"],$this->categories));

    }
    replaceStringsInStorageFile($newLibraryFile, "CustomDemo", $storeName);


  }

  /**
   * Build the cartdridge
   */
  private function buildCartridge(){

    $storeName = $this->config['store_id'];
    $newCartridgeFolderName = $this->current_folder_id . "/cartridge/$storeName";

    //Logo
    $cartridgeImagesFolder = "$newCartridgeFolderName/cartridge/static/default/images";
    $headerFile = "$newCartridgeFolderName/cartridge/templates/default/components/header/pageHeader.isml";
    $pictureName = $this->processFileField("file_home_logo",$cartridgeImagesFolder,"logo");
    replaceStringsInStorageFile($headerFile, "CustomDemoLogo", $pictureName);

    //Main color
    $base_color = "#00a1e0";
    replaceStringsInStorageFile("$newCartridgeFolderName/cartridge/static/default/css/global.css", $base_color, $this->config['home_color']);
    replaceStringsInStorageFile("$newCartridgeFolderName/cartridge/$storeName.properties", "CustomDemo", $storeName);
    replaceStringsInStorageFile("$newCartridgeFolderName/_project.xml", "CustomDemo", $storeName);
  }

  /**
   * Build the zip file
   */
  private function buildZipSite(){
    //Zip the website
    $storeName = $this->config['store_id'];
    $folder = storage_path($this->current_folder_id);
    $cmd = "cd $folder/site ; zip -r $folder/site/$storeName.zip ./$storeName/ ; ";
    $compress_command = exec($cmd ." 2>&1" );
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

  /**
   * Upload files to the user sandbox
   */
  private function uploadFilesToSandbox(){

    $storeName = $this->config['store_id'];

     /**
     * Upload website ZIP
     */
    $website_export_file = $this->current_folder_id. "/site/$storeName.zip";
    $this->uploadFilesToWebDav($website_export_file, "Impex/src/instance" );

    /**
     * Cartridge
     */
    $cartridge_folder = $this->current_folder_id . "/cartridge";
    $code_version = $this->config['sandbox_code_version'];
    $this->uploadFilesToWebDav($cartridge_folder, "Cartridges/" . $code_version );

    /**
     * Upload job
     */
    $job_files = $this->current_folder_id . "/job/$storeName-jobs.xml";
    $this->uploadFilesToWebDav($job_files, "Impex/src/operations" );

     /**
     * Upload sfra cartdridge if not installed yet
     */
    $sfra_folder = "workspace/template_base/sfra" ;
    $reqData=[
      'url'=> $this->config['sandbox_base_url'],
      'username'=> $this->config['sandbox_username'],
      'password' => $this->config['sandbox_password'],
    ];
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/modules");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/modules", "Cartridges/" . $code_version ."/modules" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/app_storefront_base");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/app_storefront_base", "Cartridges/" . $code_version ."/app_storefront_base" );
    }
    $res = checkWebDavFolder($reqData,"/on/demandware.servlet/webdav/Sites/Cartridges/$code_version/bm_app_storefront_base");
    if($res=="Not Found"){
      $this->uploadFilesToWebDav($sfra_folder."/bm_app_storefront_base", "Cartridges/" . $code_version ."/bm_app_storefront_base" );
    }
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
  private function  sendSuccessEmail(){
    $to_name = $this->config['user_email'];
    $to_email = $this->config['user_email'];
    $data = array('store_id'=>$this->config['store_id']);
    Log::info('Sending success email to '.$to_email);
    Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
      $message->to($to_email, $to_name)
              ->subject('Your custom SFRA site is ready');
      $message->from('sfrademobuilder@gmail.com','SFRA Demo Builder');
      });
    Log::info('Success email sent to '.$to_email);
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

  private function  deleteResultsFolder(){
    Storage::deleteDirectory($this->current_folder_id);
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

      $this->processCategories();

      $this->buildNewSiteFromTemplate();

      $this->processPictures();

      $this->generateXMLFiles();

      $this->buildCartridge();

      $this->buildZipSite();

      $this->uploadFilesToSandbox();

      $this->sendSuccessEmail();

      $this->sendInformationEmail();

      $this->deleteResultsfolder();

     } catch (\Exception $e) {
       Log::error($e->getTraceAsString());
       error_log($e->getTraceAsString());
       $this->sendErrorEmail($e->getMessage(),$e->getTraceAsString());
    }
  }
}
