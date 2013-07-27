#!/usr/bin/php -d disable_functions -d safe_mode=Off
<?

// $access_token
require_once('conf/access_token.php');
require_once('libs/BaiduPCS.class.php');
require_once('libs/BaiduWrapper.php');
require_once('libs/FunctionWrapper.php');

// init
$pcs = new BaiduPCS($access_token);

if (! isset($argv[1])) {
  $argv[1] = 'help';
}

switch($argv[1]) {
  case 'help':
    if (isset($argv[2])) {
      switch($argv[2]) {
        case 'quota':
          echo "``./tools.php $argv[2]``\n";
          break;
        case 'list':
        case 'ls':
          echo "``./tools.php $argv[2] [_dir_]``\n";
          break;
        case 'getmeta':
          echo "``./tools.php $argv[2] [_dir_/_file_]``\n";
          break;
        case 'move':
        case 'mv':
        case 'copy':
        case 'cp':
          echo "``./tools.php $argv[2] _src_ _dst_``\n";
          break;
        case 'remove':
        case 'rm':
          echo "``./tools.php $argv[2] _file_/_dir_ -- _dir_ didn't try\n";
          break;
        case 'mkdir':
          echo "``./tools.php $argv[2] _dir_\n";
          break;
        case 'upload':
          echo "``./tools.php $argv[2] _src_ _dst_``\n";
          echo "Upload the file as newcopy by default\n";
          echo "Modify the code if you want;)\n";
          break;
        case 'download':
          echo "``./tools.php $argv[2] _path_ [_localfile_]``\n";
          break;
        case 'search':
          echo "``./tools.php $argv[2] _keyword_``\n";
          echo "Search files recursively by default\n";
          echo "Modify the code if you want;)\n";
          break;
        default:
          break;
      }
    }
    else {
      echo <<<EOF
Basic commands:
  quota
  list / ls
  getmeta
  copy / cp
  move / mv
  remove / rm
  mkdir
  upload
  download
  search

Extended commands:
  todo
EOF;
      echo "\n";
    }
    break;
  case 'quota':
    $return_funQuota = funQuota($pcs);
    $return_funQuota_quota = $return_funQuota->{'quota'} / 1024 / 1024 / 1024;
    $return_funQuota_used = $return_funQuota->{'used'} / 1024 / 1024 / 1024;
    echo 'Quota: ' . $return_funQuota_used . ' GB /' . $return_funQuota_quota . " GB \n";
    break;
  case 'list':
  case 'ls':
    if (! isset($argv[2])) {
      $path = '';
    }
    else {
      $path = $argv[2];
    }
    $return_funListFiles = funListFiles($pcs, $path);
    $return_funListFiles_length = sizeof($return_funListFiles["list"]);
    for ($loop = 0; $loop < $return_funListFiles_length; ++$loop) {
      echo $return_funListFiles["list"][$loop]["path"] . "\n";
    }
    break;
  case 'getmeta':
    if (! isset($argv[2])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    $return_funGetMeta = funGetMeta($pcs, $argv[2]);
    if (isset($return_funGetMeta['list'])) {
      $return_funGetMeta_list_ctime = $return_funGetMeta['list']['0']['ctime'];
      $return_funGetMeta_list_mtime = $return_funGetMeta['list']['0']['mtime'];
      echo date('Y-m-d h:i:s', $return_funGetMeta_list_ctime) . ' | ';
      echo date('Y-m-d h:i:s', $return_funGetMeta_list_mtime) . ' | ';
      echo $return_funGetMeta['list']['0']['path'];
      if ($return_funGetMeta['list']['0']['isdir']) {
        echo "/";
      }
    }
    else {
      exit("file not exists\n");
    }
    echo "\n";
    break;
  case 'copy':
  case 'cp':
    if (! isset($argv[2]) || ! isset($argv[3])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    $return_funCopySingle = funCopySingle($pcs, $argv[2], $argv[3]);
    if (! isset($return_funCopySingle['extra']['list']['from'])) {
      exit("file not exists\n");
    }
    break;
  case 'move':
  case 'mv':
    if (! isset($argv[2]) || ! isset($argv[3])) {
      exit("Try ``./tool.php help $argv[1]`` for help\n");
    }
    $return_funMoveSingle = funMoveSingle($pcs, $argv[2], $argv[3]);
    if (! isset($return_funMoveSingle['extra']['list']['from'])) {
      exit("file not exists\n");
    }
    break;
  case 'remove':
  case 'rm':
    if (! isset($argv[2])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    $return_funDeleteSingle = funDeleteSingle($pcs, $argv[2]);
    // no return value if success
    if (isset($return_funDeleteSingle['error_code'])) {
      exit("file not exists\n");
    }
    break;
  case 'mkdir':
    if (! isset($argv[2])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    $return_funMakeDir = funMakeDir($pcs, $argv[2]);
    break;
  case 'upload':
    if (! isset($argv[2]) || ! isset($argv[3])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    //                                                  dst      src
    // TODO
    $return_upload_file = methUploadFile($access_token, $argv[3], $argv[2]);
    break;
  case 'download':
    if (! isset($argv[2])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    //$return_funDownload = funDownload($pcs, $argv[2]);
    if (! isset($argv[3])) {
      $argv[3] = basename($argv[2]);
    }
    $return_funDownload = methDownloadFile($access_token, $argv[2], $argv[3]);
    if (isset($return_funDownload['error_code'])) {
      exit("file not exists\n");
    }
    break;
  case 'search':
    if (! isset($argv[2])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    $return_funSearch = funSearch($pcs, $argv[2], 1);
    $return_funSearch_length = sizeof($return_funSearch["list"]);
    for ($loop = 0; $loop < $return_funSearch_length; ++$loop) {
      echo $return_funSearch["list"][$loop]["path"] . "\n";
    }
    break;
  default:
    echo "oops:(\n";
    echo "Try ``./tools.php help`` for help\n";
    break;
}

// test case

/*
 * funDiff()
 *
 */
//$return_funDiff = funDiff($pcs);
//print_r($return_funDiff);
//echo 'entries: ' . $return_funDiff->{'reset'}."\n";
//echo "\n";

/*
 * funUploadFile()
 *
 */
// BUG
//$return_funUploadFile = funUploadFile($pcs, '/home/jason/temp/kvm/201209-285.pdf');
//print_r($return_funUploadFile);

/*
 * funCreateSuperFile()
 * BUG
 */
// BUG
//$return_create_super_file = funCreateSuperFile($pcs, '/home/jason/github/baiduPCS_cli/sdk/1.pdf');
//print_r($return_create_super_file);

/*
 * methUploadFileStream()
 * BUG
 */
//$return_upload_file_stream = methUploadFileStream($access_token, '1.pdf', '/home/jason/github/baiduPCS_cli/sdk/1.pdf');
//print_r($return_upload_file_stream);

?>
