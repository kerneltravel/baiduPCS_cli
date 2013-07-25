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
          // TODO
          echo "``./tools.php $argv[2] [_dir/file_]``\n";
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
        case 'mkdir':
          echo "``./tools.php $argv[2] _dir_\n";
        default:
          break;
      }
    }
    else {
      echo <<<EOF
Basic commands:
  quota
  list / ls
  copy / cp
  move / mv
  remove / rm
  mkdir
  upload
  download

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
    $return_funListFiles = funListFiles($pcs);
    $return_funListFiles_length = sizeof($return_funListFiles["list"]);
    for ($loop = 0; $loop < $return_funListFiles_length; ++$loop) {
      echo $return_funListFiles["list"][$loop]["path"] . "\n";
    }
    break;
  case 'copy':
  case 'cp':
    if (! isset($argv[2]) || ! isset($argv[3])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    $return_funCopySingle = funCopySingle($pcs, $argv[2], $argv[3]);
    break;
  case 'move':
  case 'mv':
    if (! isset($argv[2]) || ! isset($argv[3])) {
      exit("Try ``./tool.php help $argv[1]`` for help\n");
    }
    $return_move_single = funMoveSingle($pcs, $argv[2], $argv[3]);
    break;
  case 'remove':
  case 'rm':
    if (! isset($argv[2])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    $return_delete_single = funDeleteSingle($pcs, $argv[2]);
    break;
  case 'mkdir':
    if (! isset($argv[2])) {
      exit("Try ``./tools.php help $argv[1]`` for help\n");
    }
    $return_make_dir = funMakeDir($pcs, $argv[2]);
    break;
  case 'upload':
    break;
  case 'download':
    break;
  default:
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
 * funMakeDir()
 *
 */
//$return_make_dir = funMakeDir($pcs, 'foo');
//print_r($return_make_dir);

/*
 * funCreateSuperFile()
 * BUG
 */
// BUG
//$return_create_super_file = funCreateSuperFile($pcs, '/home/jason/github/baiduPCS_cli/sdk/1.pdf');
//print_r($return_create_super_file);

/*
 * methUploadFile()
 *
 */
//$return_upload_file = methUploadFile($access_token, '1.pdf', '/home/jason/github/baiduPCS_cli/sdk/1.pdf');
//print_r($return_upload_file);

/*
 * methUploadFileStream()
 * BUG
 */
//$return_upload_file_stream = methUploadFileStream($access_token, '1.pdf', '/home/jason/github/baiduPCS_cli/sdk/1.pdf');
//print_r($return_upload_file_stream);

/*
 * funGetMeta()
 *
 */
/*
$return_get_meta = funGetMeta($pcs, '1.pdf');
$return_get_meta_list_ctime = $return_get_meta['list']['0']['ctime'];
$return_get_meta_list_mtime = $return_get_meta['list']['0']['mtime'];
echo date('Y-m-d h:i:s', $return_get_meta_list_ctime) . ' | ';
echo date('Y-m-d h:i:s', $return_get_meta_list_mtime) . ' | ';
echo $return_get_meta['list']['0']['path'];

if ($return_get_meta['list']['0']['isdir']) {
  echo '/';
}
echo "\n";
 */
//print_r($return_get_meta);

/*
 * funSearch()
 *
 */
//$search1 = '1';
//$search2 = 'jpg';
//$return_search1 = funSearch($pcs, $search1, 1);
//$return_search2 = funSearch($pcs, $search2, 1);
//print_r($return_search1);
//print_r($return_search2);

/*
 * funCopySingle()
 *
 */
/*
$return_funCopySingle = funCopySingle($pcs, '1.pdf', 'copy2.pdf');
if ($return_funCopySingle) {
  echo 'copy done' . "\n";
}
echo "\n";
 */

?>
