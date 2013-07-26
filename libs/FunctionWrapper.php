<?

function getline() {
  return trim(fgets(STDIN));
}

function cmd($str) {
  $res = '';
  if ($str) {
    if (function_exists('exec')) {
      @exec($str, $res);
      $res = join("\n", $res);
    }
    elseif (function_exists('shell_exec')) {
      $res = @shell_exec($str);
    }
    elseif (function_exists('system')) {
      @ob_start();
      @system($str);
      $res = @ob_get_contents();
      @ob_end_clean();
    }
    elseif (function_exists('passthru')) {
      @ob_start();
      @passthru(@str);
      $res = @ob_get_contents();
      @ob_end_clean();
    }
    elseif (@is_resource($f = @popen($str, "r"))) {
      $res = '';
      while (!@feof($f)) {
        $res .= @fread($f, 1024);
      }
      @pclose($f);
    }
  }
  return $res;
}

function do_api($url, $param, $method) {
  if ($method == 'POST') {
    $cmd = "curl -X POST -k -L --data \"$param\" \"$url\"";
  }
  else {
    $cmd = "curl -X $method -k -L \"$url?$param\"";
  }

  return cmd($cmd);
}

function methUploadFile ($access_token, $path, $localfile, $ondup = 'newcopy') {
  $path = '/apps/cli/' . $path;
  $url = "https://c.pcs.baidu.com/rest/2.0/pcs/file?method=upload&access_token=$access_token&path=$path&ondup=$ondup";
  $add = "--form file=@$localfile";

  if (! file_exists($localfile)) {
    exit("file not exists\n");
  }
  $cmd = "curl -X POST -k -L $add \"$url\"";
  $cmd = cmd($cmd);
  $json_cmd = json_decode($cmd);

  return $json_cmd;
}

// BUG
function methUploadFileSlice($access_token, $file) {
  $handle = fopen($file, 'r');
  $url = "https://c.pcs.baidu.com/rest/2.0/pcs/file?method=upload&access_token=$access_token&type=tmpfile";
  $add = "--form file=@$handle";

  if (!$handle) {
    exit('file open error');
  }
  $cmd = "curl -X POST -k -L $add \"$url\"";
  $cmd = cmd($cmd);
  $json_cmd = json_decode($cmd);

  return $json_cmd;
}

// BUG
// PHP 分割文件
// 断点续传
function methUploadFileStream($access_token, $path, $localfile, $ondup = 'newcopy') {
  $access = $access_token;
  $path = '/apps/cli/' . $path;

  if (!file_exists($localfile)) {
    exit('file not exists');
  }
  $file_size = filesize($localfile);
  $file_block = array();
  $block_size = 20480;
  $handle = fopen($localfile, 'rb');

  if ($file_size < $block_size) {
    exit('file size less than 20K');
  }

  while (!feof($handle)) {
    // BUG
    // Mon Jul 22 21:12:45 CST 2013
    $file_content = fread($handle, $block_size);
    $temp = methUploadFileSlice($access, $file_content);
    if (!is_array($temp)) {
      $temp = json_decode($temp, TRUE);
    }
    array_push($file_block, $temp);
  }
  fclose($handle);

  if (count($file_block) > 1) {
    $params = array();
    foreach ($file_block as $value) {
      array_push($params, $value['md5']);
    }

    $url = "https://c.pcs.baidu.com/rest/2.0/pcs/file?method=createsuperfile&access_token=$access&path=$path&params=$params&ondup=$ondup";
    $cmd = "curl -X POST -k \"$url\"";
    $cmd = cmd($cmd);
    $json_cmd = json_decode($cmd);

    return $json_cmd;
  }

}

?>
