<?php

class Datelib
{
	public function asiaJakartaDate()
	{
		date_default_timezone_set('Asia/Jakarta');
		return date('Y-m-d H:i:s');
	}

	public function asiaJakartaJustDate()
	{
		date_default_timezone_set('Asia/Jakarta');
		return date('Y-m-d');
	}

	public function asiaJakartaJustTime()
	{
		date_default_timezone_set('Asia/Jakarta');
		return date('H:i:s');
	}

	public function cutStripeForPassword($date)
	{
		$asPassword = str_replace('-', '', $date);
		$newPassword = trim($asPassword);
		return $newPassword;
	}
}
