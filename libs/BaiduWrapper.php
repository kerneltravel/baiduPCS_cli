<?

// via Baidu_PCS_api
//   fun*
function funQuota($res) {
  $res_quota = $res->getQuota();
  $json_quota = json_decode($res_quota);

  return $json_quota;
}

function funListFiles($res, $path) {
  $app_name = 'cli';
  $path = '/apps' . '/' . $app_name . '/' . $path;
  $by = 'time';
  $order = 'asc';
  $limit = '0-9';

  $res_list_files = $res->listFiles($path, $by, $order, $limit);
  $json_list_files = json_decode($res_list_files, TRUE);

  return $json_list_files;
}

// but no output
function funUploadFile($res, $file) {
  $app_name = 'cli';
  $path = '/apps' . '/' . $app_name . '/';
  $file_name = basename($file);
  // NULL means the names are the same
  $new_file_name = '';

  if (!file_exists($file)) {
    exit('file not exists');
  }
  else {
    $file_size = filesize($file);
    $handle = fopen($file, 'rb');
    $file_content = fread($handle, $file_size);

    // upload (string $file_content, string $target_path, string $file_name, [string $new_file_name = null], [boolean $is_create_super_file = FALSE])
    $res_upload_file = $res->upload($file_content, $path, $file_name, $new_file_name);
    $json_upload_file = json_decode($res_upload_file);
    fclose($handle);

    return $json_upload_file;
  }
}

function funDeleteSingle($res, $file) {
  $app_name = 'cli';
  $root_dir = '/apps' . '/' . $app_name . '/';
  $path = $root_dir . $file;

  $res_delete_file = $res->deleteSingle($path);
  $json_delete_file = json_decode($res_delete_file, TRUE);

  return $json_delete_file;
}

function funMakeDir($res, $dir) {
  $app_name = 'cli';
  $root_dir = '/apps' . '/' . $app_name . '/';
  $path = $root_dir . $dir;

  $res_make_dir = $res->makeDirectory($path);
  $json_make_dir = json_decode($res_make_dir);

  return $json_make_dir;
}

function funMoveSingle($res, $from, $to) {
  $app_name = 'cli';
  $root_dir = '/apps' . '/' . $app_name . '/';
  $file_from = $root_dir . $from;
  $file_to = $root_dir . $to;

  $res_move_single = $res->moveSingle($file_from, $file_to);
  $json_move_single = json_decode($res_move_single, TRUE);

  return $json_move_single;
}

// maybe bug
function funCreateSuperFile($res, $file) {
  $app_name = 'cli';
  $root_dir = '/apps' . '/' . $app_name . '/';
  //
  $file_name = basename($file);
  $root_dir .= $file_name;
  // names are the same
  $new_file_name = 'new.pdf';

  if (!file_exists($file)) {
    exit('file not exists');
  }
  else {
    $file_size = filesize($file);
    $handle = fopen($file, 'rb');
    $files_block = array();
    $block_size = 20480;

    if ($file_size < $block_size) {
      exit('the size of the upload file must be more than 20480 bytes');
    }
    $is_create_super_file = True;
    while (!feof($handle)) {
      $file_content = fread($handle, $block_size);
      $temp = $res->upload($file_content, $root_dir, $file_name, $new_file_name, $is_create_super_file);
      if (!is_array($temp)) {
        $temp = json_decode($temp, true);
      }
      array_push($files_block, $temp);
    }
    fclose($handle);

    if (count($files_block) > 1) {
      $params = array();
      foreach ($files_block as $value) {
        array_push($params, $value['md5']);
      }

      // createSuperFile (string $target_path, string $file_name, array $params, [string $new_file_name = null])
      $res_create_super_file = $res->createSuperFile($root_dir, $file_name, $params, $new_file_name);
      $json_create_super_file = json_decode($res_create_super_file);

      return $json_create_super_file;
    }
  }
}

function funGetMeta($res, $file) {
  $app_name = 'cli';
  $root_dir = '/apps/' . $app_name . '/';
  $path = $root_dir . $file;

  $res_get_meta = $res->getMeta($path);
  $json_get_meta = json_decode($res_get_meta, TRUE);

  return $json_get_meta;
}

function funSearch($res, $word, $is_re) {
  $app_name = 'cli';
  $root_dir = '/apps/' . $app_name . '/';

  $res_search = $res->search($root_dir, $word, $is_re);
  $json_search = json_decode($res_search, TRUE);

  return $json_search;
}

function funCopySingle($res, $from, $to) {
   $app_name = 'cli';
   $root_dir = '/apps/' . $app_name . '/';

   // 绝对路径
   $from = $root_dir . $from;
   $to = $root_dir . $to;

   $res_copy_single = $res->copySingle($from, $to);
   $json_copy_single = json_decode($res_copy_single, TRUE);

   return $json_copy_single;
}

function funDiff($res) {
  $before = 'null';
  $res_diff = $res->diff($before);
  $json_diff = json_decode($res_diff);

  return $json_diff;
}
?>
