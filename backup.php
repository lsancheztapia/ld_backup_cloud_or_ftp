<?php
require( 'rackspace-php-cloudfiles/cloudfiles.php' );
require( 'backup_config.php' );

/*
$backup_folders = array(
  array (
    'machine_name' => 'etek',
    'path' => '/var/www/vhosts/etektraining.com/httpdocs'
  ),
  array (
    'machine_name' => 'etek_dev',
    'path' => '/var/www/vhosts/dev.etektraining.com'
  ),
  array (
    'machine_name' => 'southflorida',
    'path' => '/var/www/vhosts/southflorida-erp-crm.com/httpdocs'
  )
);
*/

$today = date("Y") . '-week' . date("W-Ymd-His");
/*
$username='jeffetek';
$api_key="4dca9675e90c820f9393445faa649ebf";
*/

// deletings previuos backups
print shell_exec( 'rm -rf /root/work/incrementals/*.tgz' );

// access to cloud files
$auth = new CF_Authentication($username, $api_key);
$auth->authenticate();
 
if ( $auth->authenticated() )
  echo "CF Authentication successful \n";
else
  echo "Authentication faile \n";

$conn = new CF_Connection($auth);
//$backup_container = $conn->get_container('etek_backups');
$backup_container = $conn->get_container( $cloud_folder );


foreach ( $backup_folders as $backup ) {

  $incremental_file = $temp_local_folder . '/' . $backup['machine_name'] . '_' . $today . '.tgz';;
  $to_be_backuped = $backup['path'];
  $tar_incremental_g_file = $temp_local_folder . '/' . $backup['machine_name'] . '_g.txt';
  
  $day =  date("D");
  if ( $day == 'Mon' ) { shell_exec( "rm $tar_incremental_g_file" ); } // for starting a full backup

  $cmd = "tar -g $tar_incremental_g_file -zcpvf $incremental_file $to_be_backuped";
  print shell_exec( $cmd );

  // uploading to cloud files
  $backup = $backup_container->create_object( $backup['machine_name'] . '_' . $today . '.tgz' );
  $backup->load_from_filename( $incremental_file );
  $backup_container->make_private();

  //$container_list = $conn->list_containers();
  //print_r($container_list);

  sleep(20);


}


die('end');








