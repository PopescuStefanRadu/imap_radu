<?php

echo "<p> xxx </p>";
$mailRef = '{imap.mail.yahoo.com:993/imap/ssl}';
$mailbox = $mailRef . 'INBOX';
$username = 'nw407elixir@yahoo.com';
$password = 'mmloismsahbojkbm';
$options = 0;
$nRetries = 0;
$params = NULL;
$msgNum = 0;

$imap_stream = '';
echo '<p>' . $mailbox . '</p>';

//$imapStream = imap_open($mailbox, $username,$password,$options,$n_retries,$params);
$imapStream = imap_open($mailbox, $username, $password);
echo var_dump($imapStream);

$imapList = imap_list($imapStream, $mailRef, '*');
echo "<ul>";
foreach ($imapList as $folder) {
  $folder = str_replace($mailRef, "", imap_utf7_decode($folder));
  echo "<li>" . $folder . '</li>';
}
echo "</ul>";
//https://help.yahoo.com/kb/SLN4075.html


echo "<hr><hr><h1>Dis be my inbox</h1>";

$msgNum = imap_num_msg($imapStream);
echo "<p>You have $msgNum mails in inbox</p>";

$folder = 'INBOX';
for ($i = $msgNum; $i > ($msgNum - 20); $i--) {
  $header = imap_header($imapStream, $i);
  $fromInfo = $header->from[0];
  $replyInfo = $header->reply_to[0];
  $attachment = imap_fetchstructure($imapStream, $msgNum);
  echo "<ul>";
  foreach ($attachment as $key => $value) {
    echo "<li>$key</li>";
    if ($key=='parts'){
      echo "<ul>";
      foreach($value as $key2=>$value2){
        echo "<li>$key2</li>";
        if ($key2){
          echo "<ul>";
          foreach ($value2 as $key3=>$value3){
            echo "<li><p>Key:$key3</p><p>Value:$value3</p></li>";
          }
          echo "</ul>";
        }
      }
      echo "</ul>";
    }
  }
  echo "</ul>";

  echo "<p>".var_dump($attachment)."</p>";

  $details = array(
    "fromAddr" => (isset($fromInfo->mailbox) && isset($fromInfo->host))
      ? $fromInfo->mailbox . "@" . $fromInfo->host : "",
    "fromName" => (isset($fromInfo->personal))
      ? $fromInfo->personal : "",
    "replyAddr" => (isset($replyInfo->mailbox) && isset($replyInfo->host))
      ? $replyInfo->mailbox . "@" . $replyInfo->host : "",
    "replyName" => (isset($replyInfo->personal))
      ? $replyInfo->personal : "",
    "subject" => (isset($header->subject))
      ? $header->subject : "",
    "udate" => (isset($header->udate))
      ? $header->udate : ""
  );

  $uid = imap_uid($imapStream, $i);

  echo "<ul>";
  echo "<li><strong>From:</strong>" . $details["fromName"];
  echo " " . $details["fromAddr"] . "</li>";
  echo "<li><strong>Subject:</strong> " . $details["subject"] . "</li>";
  echo '<li><a href="mail.php?folder=' . $folder . '&uid=' . $uid . '&func=read">Read</a>';
  echo " | ";
  echo '<a href="mail.php?folder=' . $folder . '&uid=' . $uid . '&func=delete">Delete</a></li>';
  echo "</ul>";
}
