<?php

/**
 *  this a file for general backup by ftp, cloud files, etc ...
 */

require( 'backup_config.php' );

// checking the $temp_local_folder
if ( ! file_exists($temp_local_folder) ) { shell_exec( 'mkdir -p ' . $temp_local_folder ); }

$today = date("Y") . '-week' . date("W-Ymd-His");

// deletings previuos backups
print shell_exec( 'rm -rf ' . $temp_local_folder . '/*.tgz' );

// files backup
foreach ( $backup_folders as $backup ) {

  // incremental backup
  $incremental_file_name = $backup['machine_name'] . '_' . $today . '.tgz';
  $incremental_file = $temp_local_folder . '/' . $incremental_file_name;
  $to_be_backuped = $backup['path'];
  $tar_incremental_g_file = $temp_local_folder . '/' . $backup['machine_name'] . '_g.txt';
  
  $day =  date("D");
  if ( $day == 'Mon' ) { shell_exec( "rm $tar_incremental_g_file" ); } // for starting a full backup

  $cmd = "tar -g $tar_incremental_g_file -zcpvf $incremental_file $to_be_backuped";
  print shell_exec( $cmd );

  // send to a storage place
  if ( $backup_type == 'ftp' ) {

    $ftp_conn = ftp_connect($backup_type_ftp_host) or die("Could not connect to $backup_type_ftp_host");
    $login = ftp_login($ftp_conn, $backup_type_ftp_user, $backup_type_ftp_password);

    if ( $login ) {
  
      if ( ! ftp_chdir($ftp_conn, 'files/' . $backup['machine_name']) ) {
        ftp_mkdir( $ftp_conn, 'files/' . $backup['machine_name'] );
        ftp_chdir( $ftp_conn, 'files/' . $backup['machine_name'] );
      }

      if ( ftp_put($ftp_conn, $incremental_file_name, $incremental_file, FTP_BINARY) ) {
        echo "Successfully uploaded $incremental_file.";
      } else {
        echo "Error uploading $incremental_file.";
      }

    } else {
      die("Could not login to $backup_type_ftp_host");
    }

    ftp_close( $ftp_conn );

  }
}


// database backup
foreach ( $mysql_backup_databases as $backup ) {
  $today = date("Y") . '-week' . date("W-Ymd-His");
  $db_base_file_name = $backup['machine_name'] . '_' . $today;
  $db_file = $temp_local_folder . '/' . $db_base_file_name . '.sql';
  $cmd = "mysqldump -u$mysql_database_user " . $backup['database_name'] . " > $db_file";
  shell_exec( $cmd );
  $cmd = "tar cvfz " . $temp_local_folder . '/' . $db_base_file_name . ".tgz " . $temp_local_folder . '/' . $db_base_file_name . ".sql ; rm " . $temp_local_folder . '/' . $db_base_file_name . ".sql";
  shell_exec( $cmd );

  // send to a storage place
  if ( $backup_type == 'ftp' ) {
    $ftp_conn = ftp_connect($backup_type_ftp_host) or die("Could not connect to $backup_type_ftp_host");
    $login = ftp_login($ftp_conn, $backup_type_ftp_user, $backup_type_ftp_password);

    if ( $login ) {
      if ( ! ftp_chdir($ftp_conn, 'databases/' . $backup['machine_name']) ) {
        ftp_mkdir( $ftp_conn, 'databases/' . $backup['machine_name'] );
        ftp_chdir( $ftp_conn, 'databases/' . $backup['machine_name'] );
      }
      if ( ftp_put( $ftp_conn, $db_base_file_name . '.tgz', $temp_local_folder . '/' . $db_base_file_name . '.tgz', FTP_BINARY) ) {
        echo "Successfully uploaded " . $temp_local_folder . '/' . $db_base_file_name . '.tgz';
      } else {
        echo "Error uploading " . $temp_local_folder . '/' . $db_base_file_name . '.tgz';
      }
    } else {
      die("Could not login to $backup_type_ftp_host");
    }

    ftp_close( $ftp_conn );

  }
}