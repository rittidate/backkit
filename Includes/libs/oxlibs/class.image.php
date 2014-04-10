<?php
//
//class Gd
//{
//         var $image;
//         function __construct($name){
//                $this->image = imagecreatefromjpeg($name) or die("Cannot Initialize new GD image stream");
//         }
//         function resize($width, $height, $width_new, $height_new) {
//                 $temp = imagecreatetruecolor($width_new, $height_new);
//                 imagecopyresized($temp, $this->image,0, 0, 0, 0,$width_new,$height_new,$width,$height);
//                 $this->image = $temp;
//         }
//         function addimage($png, $text_x, $text_y ,$size_x, $size_y) {
//                        $imgpng = imagecreatefrompng($png);
//                        var_dump($this->image);
//                        imagecopy($this->image,$imgpng, $text_x, $text_y , 0, 0, $size_x, $size_y);
//         }
//         function saveImage($imagePath){
//                        imagejpeg($this->image,$imagePath);
//         }
//         function showImage(){
//                        imagejpeg($this->image);
//         }
//         function __destruct() {
//                if($this->image){
//                   imagedestroy($this->image);
//                }
//         }
//}

class GD
{
    var $fileName;
    var $content;
    var $variableName;
    var $imageName;
    var $image;
    var $imageResize;
    var $png;

    function  __construct($variableName, $imageName) {
        $this->variableName = $variableName;
        $this->imageName = $imageName;
        $this->factoryUploadedFile();
    }

    function factoryUploadedFile() {
        
        if (!empty($_FILES[$this->variableName]['error'])) {
            return new PEAR_Error('Could not read the file: '.$filePath);
        }
        $fileName = basename($_FILES[$this->variableName]['name']);
        $filePath = $_FILES[$this->variableName]['tmp_name'];
        if (!@is_readable($filePath)) {
            return new PEAR_Error('Could not read the file: '.$filePath);
        }

        $this->content = @file_get_contents($filePath);

        $stored_url = $this->php_ImageStore($this->content);

        $this->php_ImageMerge($stored_url);

    }


    function php_ImageStore($buffer, $overwrite = false)
    {
        $pathName = MAX_PATH."var/image/pimage/".$this->imageName;
        if ($fp = @fopen( $pathName, 'wb')) {
                @fwrite($fp, $buffer);
                @fclose($fp);
                return $stored_url = $pathName;
        }
    }

    function php_ImageMerge($pathName){
        
        $this->image = imagecreatefromjpeg($pathName);
        $this->png = imagecreatefrompng(MAX_PATH."Includes/assets/img/ktv_pic.png");

        imagealphablending($this->png, false);
        imagesavealpha($this->png, true);
        list($w,$h)=getimagesize($_FILES[$this->variableName]['tmp_name']);
        $text_x = $w-140;
        $text_y = $h-100;

        if($w>$h){
            if($w > 200){
                $new_w = 200;
                $new_h = ($h*200)/$w;
            }else{
                $new_w = $w;
                $new_h = $h;
            }
        }else{
            if($h > 200){
                $new_w = ($w*200)/$h;
                $new_h = 200;
            }else{
                $new_w = $w;
                $new_h = $h;
            }
        }
        imagecopy($this->image, $this->png, $text_x, $text_y, 0, 0, 150, 80); //have to play with these numbers for it to work for you, etc.
        imagejpeg($this->image,MAX_PATH."var/image/pimage/large/".$this->imageName);
        $this->resize($w, $h, $new_w, $new_h);
        imagejpeg($this->imageResize,MAX_PATH."var/image/pimage/small/".$this->imageName);
        
        $this->storeFTP();
    }

    function resize($width, $height, $width_new, $height_new) {
         $temp = imagecreatetruecolor($width_new, $height_new);
         imagecopyresized($temp, $this->image,0, 0, 0, 0,$width_new,$height_new,$width,$height);
         $this->imageResize = $temp;
    }

    function __destruct() {
        if($this->image){
            @unlink(MAX_PATH."var/image/pimage/".$this->imageName);
           imagedestroy($this->image);
           imagedestroy($this->png);
        }
    }
    
    function storeFTP(){
        $aConf = $GLOBALS["CONF"];;
        $server = array();
        $server['host'] = $aConf['store']['ftpHost'];
        $server['path'] = $aConf['store']['ftpPath'];
        if (($server['path'] != "") && (substr($server['path'], 0, 1) == "/")) {
            $server['path'] = substr($server['path'], 1);
        }
        $server['user'] = $aConf['store']['ftpUsername'];
        $server['pass'] = $aConf['store']['ftpPassword'];

        //connect server
        $conn_id = @ftp_connect($server['host']);
        if ($server['pass'] && $server['user']) {
                $login = @ftp_login($conn_id, $server['user'], $server['pass']);
        }
        
        if (($conn_id) || ($login)) {
            
            $remotePathSmall = $server['path'].'small/';
            $remotePathLarge = $server['path'].'large/';
            
            if (ftp_put($conn_id, $remotePathLarge.$this->imageName, MAX_PATH."var/image/pimage/large/".$this->imageName, FTP_ASCII)) {
                @unlink(MAX_PATH."var/image/pimage/large/".$this->imageName);
            }
            
            if (ftp_put($conn_id, $remotePathSmall.$this->imageName, MAX_PATH."var/image/pimage/small/".$this->imageName, FTP_ASCII)) {
                @unlink(MAX_PATH."var/image/pimage/small/".$this->imageName);
            }
            
        }
    }

    function pathServerZipStore($file, $dir, $directoryzip, &$aVariables){
        $aConf = $GLOBALS['_MAX']['CONF'];
        if ($aConf['store']['mode'] == 'ftp') {
                // FTP mode
                $server = array();
                $server['host'] = $aConf['store']['ftpHost'];
                $server['path'] = $aConf['store']['ftpPath'];
                if (($server['path'] != "") && (substr($server['path'], 0, 1) == "/")) {
                    $server['path'] = substr($server['path'], 1);
                }
                $server['user'] = $aConf['store']['ftpUsername'];
                $server['pass'] = $aConf['store']['ftpPassword'];
                $server['passiv'] = !empty( $aConf['store']['ftpPassive'] );

                //connect server
                $conn_id = @ftp_connect($server['host']);
                if ($server['pass'] && $server['user']) {
                        $login = @ftp_login($conn_id, $server['user'], $server['pass']);
                } else {
                        $login = @ftp_login($conn_id, "anonymous", $pref['admin_email']);
                }
                if( $server['passiv'] ) {
                        ftp_pasv( $conn_id, true );
                }
                if (($conn_id) || ($login)) {
                    $remotePathIframe =$server['path'].'/iframe/';
                    $remotepath = $remotePathIframe.$directoryzip;

                    //create all folder
                    if(!is_dir($remotePathIframe)){
                        if(ftp_mkdir($conn_id, $remotePathIframe)){
                        }
                    }

                    $afolder = $this->pathDirectoryOnly($dir);
                    foreach ($afolder as $val){
                        foreach ($afolder as $val){
                            if(!is_dir($remotepath.$val)){
                                if(ftp_mkdir($conn_id, $remotepath.$val)){
                                }
                            }
                        }
                    }

                    $file = $this->filenameToArray($dir,true);
                    $afilename = $this->pathAllDirectoryOnly($dir);
                    if($this->checkAllFileSupport($file, $dir)){

                        if(!is_dir($remotepath)){
                            if(ftp_mkdir($conn_id, $remotepath)){
                            }
                        }

                        foreach($afilename as $val){
                            $this->createPath($conn_id, $val, $remotepath);
                            if (ftp_put($conn_id, $remotepath.$val, $dir.$val, FTP_ASCII)) {
                                if($this->checkHtmlFile($val)){
                                    $filename = $val;

                                    $check_file_exist = $remotepath.$val;
                                    $contents_on_server = ftp_nlist($conn_id, $remotepath);
                                    if (!in_array($check_file_exist, $contents_on_server))
                                    {
                                        $this->checkForErrorZipFileUploaded("This is zip file not support.");
                                    }

                                }else{
                                    $aVariables["fileAsset"][] = $remotepath.$val;
                                }
                            }
                        }
                        $this->deleteDir($dir);
                    }
                    return 'iframe/'.$directoryzip.$filename;
                }
       } else {
            if(!is_dir($aConf['store']['webDir'].'/iframe')){
               mkdir($aConf['store']['webDir'].'/iframe', 0777);
            }
            $file = $this->filenameToArray($dir,true);
            $this->checkAllFileSupport($file, $dir);
            foreach($file as $val){
                if($this->checkHtmlFile($val)){
                   $filename = $val;
                    if( !file_exists($aConf['store']['webDir'].'/iframe/'.$directoryzip.'/'.$filename) ){
                         $this->checkForErrorZipFileUploaded("This is zip file not support.");
                    }
               }
            }
            return 'iframe/'.$directoryzip.'/'.$filename;
       }

    }
}

?>
