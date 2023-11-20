<?php


if (!function_exists("digitOnly")) {
 function digitOnly($text) {

    $res = strtolower(preg_replace('/[^0-9_.]/', '', $text));
    return $res;

  }
  }

  // http://site.com/aa/bb/cc.html => http://site.com/aa/bb
  if (!function_exists("urlBasePath")) {
    function urlBasePath($url) {
      $url_info = parse_url($url);
      $path = $url_info['path'];
      $arr = explode("/",$path);
      if(count($arr)){
        $path = str_replace(end($arr),"",$path);
      }else{
        $path = "/";
      }
      return $url_info['scheme'] . '://' . $url_info['host'] . $path;
    }
  }


  if (!function_exists("realUrl")) {
    function realUrl($str, $basePath) {
      if(strpos($str, "http") !== false){
        return $str;
      }
      
      elseif(substr($str,0,2)=="//"){
        return "https:" . $str;
      }

      elseif(substr($str,0,2)=="./"){
        $url_info = parse_url($basePath);
        $currentPath = trim($str,"/");
        $currentPath=substr($currentPath,2);
        return $url_info['scheme'] . '://' . $url_info['host'] . '/' .trim($currentPath, '/'); 
      }

      elseif(substr($str,0,1)=="/"){
        $url_info = parse_url($basePath);
        return $url_info['scheme'] . '://' . $url_info['host'] . '/' .trim($str, '/');
      }
  
      else{
        
        return trim(urlBasePath($basePath), '/') . '/' . trim($str, '/') ;
      }
         
    }
  }



if (!function_exists("deleteDir")) {
  function deleteDir($dirname) {
           if (is_dir($dirname))
             $dir_handle = opendir($dirname);
       if (!$dir_handle)
            return false;
       while($file = readdir($dir_handle)) {
             if ($file != "." && $file != "..") {
                  if (!is_dir($dirname."/".$file))
                       unlink($dirname."/".$file);
                  else
                       deleteDir($dirname.'/'.$file);
             }
       }
       closedir($dir_handle);
       rmdir($dirname);
       return true;
  }
}


if (!function_exists("getDirContents")) {
function getDirContents($dir, &$results = array()){
  $files = scandir($dir);

  foreach($files as $key => $value){
      $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
      if(!is_dir($path)) {
          $results[] = $path;
      } else if($value != "." && $value != "..") {
          getDirContents($path, $results);
          $results[] = $path;
      }
  }

  return $results;
}
}




if (!function_exists("copyFolder")) {
function copyFolder($src,$dst) {
  $dir = opendir($src);
  @mkdir($dst);
  while(false !== ( $file = readdir($dir)) ) {
  if (( $file != '.' ) && ( $file != '..' )) {
  if ( is_dir($src . '/' . $file) ) {
    copyFolder($src . '/' . $file,$dst . '/' . $file);
  }
  else {
  copy($src . '/' . $file,$dst . '/' . $file);
  }
  }
  }
  closedir($dir);
  }
}

if (!function_exists("normalize")) {
  function normalize ($string) {
    $table = array(
      'Š'=>'S', 'š'=>'s', 'Ð'=>'D', 'Ž'=>'Z', 'ž'=>'z',
      'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
      'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
      'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
      'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
      'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
      'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
      'ÿ'=>'y', 'R'=>'R', 'r'=>'r',
  );
   
    return strtr($string, $table);
}
}

if (!function_exists("abc_string")) {

 function abc_string($input_text) {
  $text = normalize($input_text);
  $res = strtolower(substr(preg_replace('/[^a-zA-Z0-9_.]/', '-', $text),0,100));
  $res = preg_replace('/[-]+/', '-', $res);
  return $res;

}
}

if (!function_exists("replaceStringsInStorageFile")) {
  function replaceStringsInStorageFile($filename, $baseString, $replaceString) {

    $str=Storage::get($filename);
    //replace something in the file string - this is a VERY simple example
    $str=str_replace($baseString, $replaceString,$str);
    //write the entire string
    Storage::put($filename, $str);
  }
}


if (!function_exists("replaceStringsInFile")) {
  function replaceStringsInFile($filename, $baseString, $replaceString) {

    $str=file_get_contents($filename);
    //replace something in the file string - this is a VERY simple example
    $str=str_replace($baseString, $replaceString,$str);
    //write the entire string
    file_put_contents($filename, $str);
  }
}


if (!function_exists("baseUrl")) {
  function baseUrl($url) {
    $url_info = parse_url($url);
    return $url_info['scheme'] . '://' . $url_info['host'];
  }
}

if (!function_exists("getPageFromUrl")) {

 
  function getPageFromUrl ($url, $remote_address) {

    //try curl
    $useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
    $timeout= 120;
    $dir            = dirname(__FILE__);
    $cookie_file = null;
    if($remote_address){
      $cookie_file    = $dir . '/cookies/' . md5($remote_address) . '.txt';
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if($cookie_file){
      curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
      curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt($ch, CURLOPT_ENCODING, "" );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_AUTOREFERER, true );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com/');
    $content = curl_exec($ch);
    if(curl_errno($ch)){
      \Log::debug('error:' . curl_error($ch));
        return FALSE;//echo 'error:' . curl_error($ch);
    }else{
        return $content;        
    }
    curl_close($ch);
  }
}

if (!function_exists("getCategoryIdByName")) {

  function getCategoryIdByName($category_name,$categories) {
 
    foreach ($categories as $key => $category) {
      if($category['name']==$category_name){
        return $category['id'];
      }
    }
    return null;
 }
}

if (!function_exists("copyStorageFolder")) {
  function copyStorageFolder($folder,$destination,$id) {
    $files = \Storage::allFiles($folder);
    foreach ($files as $file) {
      $newFile = str_replace([$folder,"CustomDemo"], ["",$id], $file);
      \Storage::copy($file, "$destination/$newFile");
    }
  }
}

if (!function_exists("checkWebDavFolder")) {

  function checkWebDavFolder($request_data,$folderPath) {
 
    $settings = array(
      'baseUri' => rtrim($request_data['url'],"/"),
      'userName' => $request_data['username'],
      'password' => $request_data['password']
  );

  $client = new \Sabre\DAV\Client($settings);

  try {
      $folder_content = $client->propFind($folderPath, array(
          '{DAV:}getlastmodified',
          '{DAV:}getcontenttype',
      ),1);
      return ('OK');
  
  } catch (\Sabre\HTTP\ClientHttpException $th) {
      if(\Illuminate\Support\Str::contains($th, 'Unauthorized')){
        return ('Unauthorized');
      
      }elseif(\Illuminate\Support\Str::contains($th, 'Not Found')){
        return ('Not Found');
      
      }else{
        return ('Unknown');
      }
  }
 }
}
