<?php 
/**
*@author yudha tira pamunkas
*@version 1.2
*@copyright anak anjing luh yg cuma ganti author
*/

function curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);

	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function check_email($email) {
	$check_email = curl('https://api-website-yudha.000webhostapp.com/email.php?email='.$email);

	if (strrpos($check_email, '2')) {
		$result = "registered";
	} elseif (strrpos($check_email,  '1')) {
		$result = "ok";
	} else {
		$result = "failed";
	}
	return $result;

}

function create_account($email,$password) {
	$create = curl('https://api-website-yudha.000webhostapp.com/create.php?email='.$email.'&password='.$password);

	if (strrpos($create, 'success')) {
		echo "[+] Success | ".$email." | ".$password."\n";
		$data = "[+] Success | ".$email." | ".$password."\r\n";
		$fopen = fopen('result_spotify.txt', 'a');
		fwrite($fopen, $data);
		fclose($fopen);
	} else {
		echo "[!] Failed | ".$email." | ".$password."\n";
	}
}


function start() {
	print("=============================\n");
	print("   Spotify account creator  \n");
	print("         Version 1.2        \n");
	print("   Created by yudha tira p  \n");
	print("=============================\n");
	echo "\n[#] Email: ";
	$email = trim(fgets(STDIN));

	if ($email != "") {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "[!] Invalid email address\n";
		} else {
			echo "[~] Checking email";
			for ($i=0; $i < 3; $i++) { 
				usleep(500000);
				echo ".";
			}
			echo "\n";
			$check = check_email($email);

			if ($check == "registered") {
				echo "[!] Email already register | ".$email."\n";
			} elseif ($check == "ok") {
				echo "[#] Password: ";
				$password = trim(fgets(STDIN));
				if ($password != "") {
					create_account($email, $password);
				} else {
					echo "[!] Password cannot be blank\n";
				}
			} elseif ($check == "failed") {
				echo "[!] Failed | ".$email."\n";
			}
		}		
	} else {
		echo "[!] Email cannot be blank\n";
	}
}

start();

while (true) {
	echo "\nWanna register again? (y/n): ";
	$select = trim(fgets(STDIN));
	if ($select == "y") {
		echo "\n";
		start();
	} else {
		die("Done!\n");
	}
}

?>
