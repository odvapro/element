<?php
if($_GET['task'] == 'resetdb')
{
	echo exec('cd ../ && vendor/bin/codecept run api AuthCest::auth');
}
