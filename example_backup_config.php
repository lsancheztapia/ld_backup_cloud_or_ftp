<?php

$backup_folders = array(
  array (
    'machine_name' => 'ernesto',
    'path' => '/home/www/ernesto'
  ),
  array (
    'machine_name' => 'ld_negocio_pe',
    'path' => '/var/www/logicaldesign/negocio.pe'
  ),

);

$mysql_backup_databases = array(
  array(
    'machine_name'  => 'ld_negocio_pe',
    'database_name' => 'ld_negocio'
  ),
   array(
    'machine_name'  => 'ld_troonic',
    'database_name' => 'new_troonic'
  ),
  array(
    'machine_name'  => 'ec_cicibet',
    'database_name' => 'ec_cicibet'
  ),
  array(
    'machine_name'  => 'ec_branson',
    'database_name' => 'ec_branson'
  ),
  array(
    'machine_name'  => 'ec_kalexa',
    'database_name' => 'ec_kalexa'
  ),
  array(
    'machine_name'  => 'ec_sneak',
    'database_name' => 'ec_sneak'
  )
);

$mysql_database_user = 'root';
$mysql_database_password = 'the pass';

$backup_type = 'ftp';

$backup_type_ftp_user = 'the_user';
$backup_type_ftp_password = 'the_pass';
$backup_type_ftp_host = 'the_host';


$temp_local_folder = '/home/backup_stuff/work/incrementals';

