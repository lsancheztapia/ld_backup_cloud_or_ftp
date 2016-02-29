Backup

Example of backup_config.php file:


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

$username='the_username';
$api_key="the_api_key";

$cloud_folder = 'etek_backups';
$temp_local_folder = '/root/work/incrementals';


