#!/usr/bin/php
<?php

$www_dir = dirname(dirname(__FILE__));
$_SERVER['DOCUMENT_ROOT'] = $www_dir;
require_once($www_dir.'/conf/configuration.php');
require_once($www_dir.'/db_connect.php');
require_once($www_dir.'/classes/ArticlePublish.php');
require_once($www_dir.'/classes/IssuePublish.php');
IssuePublish::DoPendingActions();
ArticlePublish::DoPendingActions();

?>