<?php

// Specify your FTP host, login, password and port (21 for FTP and 22 for SFTP)
$FTP_host='';
$FTP_login='';
$FTP_pwd='';
$FTP_port='21';

// Just after the connexion, do you wish to change to a specific folder.  If yes, mention its name here
$FTP_folder='';

// Mention here the name of the file you wish to download; for instance 'backup.zip'
$FTP_DownloadFileName='backup.zip';

// Tells if the file is a binary one (so use FTP_BINARY) or a text file (so use FTP_ASCII)
$FTP_Mode=FTP_BINARY;

// Do you wish to rename the file on your local system ?
// Let this variable empty to reuse the same name (backup.zip) or specify a new name here ("mysite_backup.zip" f.i.)
$FTP_LocalFileName='';

// --------------------------------------------------------------------------------------------------
//
// Probably this portion of code shouldn't be changed

require_once('libs/FtpClient.php');
require_once('libs/FtpException.php');
require_once('libs/FtpWrapper.php');

$ftp = new \FtpClient\FtpClient();
$handle=$ftp->connect($FTP_host);

try {
    $obj=$ftp->login($FTP_login, $FTP_pwd);
} catch (Exception $ex) {
    $msg_err=$ex->getMessage();
    $obj=null;
}

if ($obj!=null) {
    if ($FTP_folder!='') {
        $ftp->chdir($FTP_folder);
    }

   // Downloads a file from the FTP server and saves to an open file
    if (trim($FTP_LocalFileName)=='') {
        $FTP_LocalFileName=$FTP_DownloadFileName;
    }

    $ftp->get($FTP_LocalFileName, $FTP_DownloadFileName, $FTP_Mode, 0);
} else {
    echo '<strong>FTP connexion error : '.$msg_err.'</strong>';
} // if ($obj!=null)

unset($ftp);
