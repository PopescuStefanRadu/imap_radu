<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'kint-master/Kint.class.php';


use Fetch\Attachment;
use Fetch\Message;
use Fetch\Server;


// TODO errors missing when port not correct???

$imapPath = "imap.mail.yahoo.com";
$user = "nw407elixir@yahoo.com";
$port = 993;
$password = "mmloismsahbojkbm";

// TODO throw error when try to get mailboxes but no auth set???
$imapServer = new Server($imapPath, $port);
$imapServer->setAuthentication($user, $password);
$mailboxes = $imapServer->listMailBoxes();
$imapServer->setMailBox('Sent');
echo $imapServer->numMessages();

echo "<ul>";
foreach ($mailboxes as $mailbox) {
  echo "<li>$mailbox</li>";
}
echo "</ul>";

// TODO throw error when try to get messages with
$messages = array();
$messages = $imapServer->getRecentMessages(4);
$messages = $imapServer->search("SINCE \"22-Jul-2012\"",4);

/** @var Message $message */
foreach ($messages as $message) {
  echo "<hr>";

  d($message);
  d($message->getHeaders()->message_id);
  d($message->getHeaders()->in_reply_to);
  echo "<hr>";
  $attachments = $message->getAttachments();
  if ($attachments) {
    foreach ($attachments as $attachment) {
      echo $attachment->getMimeType();
      $attachment->saveToDirectory("/home/radu/Work/imap_radu/attachments");
    }
  }
  echo "<hr>";
}

