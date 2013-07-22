#!/usr/bin/php -d disable_functions -d safe_mode=Off
<?

// $access_token
require_once('conf/access_token.php');
require_once('libs/BaiduPCS.class.php');
require_once('libs/BaiduWrapper.php');
require_once('libs/FunctionWrapper.php');

// init
$pcs = new BaiduPCS($access_token);

// test case

/*
 * funQuota()
 *
 */
$return_funQuota = funQuota($pcs);
$return_funQuota_quota = $return_funQuota->{'quota'} / 1024 / 1024;
$return_funQuota_used = $return_funQuota->{'used'} / 1024 / 1024;
echo 'Quota: ' . $return_funQuota_used . ' GB /' . $return_funQuota_quota . " GB \n";
echo "\n";

/*
 * funDiff()
 *
 */
//$return_funDiff = funDiff($pcs);
//print_r($return_funDiff);
//echo 'entries: ' . $return_funDiff->{'reset'}."\n";
//echo "\n";

/*
 * funListFiles()
 *
 */
//$return_funListFiles = funListFiles($pcs);
//print_r($return_funListFiles);
//echo 'list: ' . $return_funListFiles->{'list'}."\n";
//echo "\n";

/*
 * funUploadFile()
 *
 */
// BUG
//$return_funUploadFile = funUploadFile($pcs, '/home/jason/temp/kvm/201209-285.pdf');
//print_r($return_funUploadFile);

/*
 * funDeleteSingle()
 *
 */
//$return_delete_single = funDeleteSingle($pcs, 'demo');
//print_r($return_delete_single);

/*
 * funMakeDir()
 *
 */
//$return_make_dir = funMakeDir($pcs, 'foo');
//print_r($return_make_dir);

/*
 * funMoveSingle()
 *
 */
//$return_move_single = funMoveSingle($pcs, '23.jpg', '233.jpg');
//print_r($return_move_single);

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
//$return_get_meta = funGetMeta($pcs, '1.pdf');
//print_r($return_get_meta);

/*
 * funSearch()
 *
 */
$search1 = '1';
$search2 = 'jpg';
$return_search1 = funSearch($pcs, $search1, 1);
$return_search2 = funSearch($pcs, $search2, 1);
print_r($return_search1);
print_r($return_search2);
?>
