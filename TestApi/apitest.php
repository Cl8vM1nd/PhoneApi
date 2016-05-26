<?php 
error_reporting(E_ERROR);
require('class/Curl.php');

$curl = new Curl();

/**
*	First you have to be registered. [1]
*	After, you receive you unique id like this => $2y$10$yfue\/5DSDyxkjZ\/MkrMPJ.KU2ZbgnfJBKGRsDs1J9es0MSy7PsRBa
*	Then, you have to get your unique token to make requests [2]
*	You will receive array with @param [
*		time 	: 1464171236  - is how many seconds pass from Unix Epoch, when token was generated
*		expires	: 7200 		  - is how long your token will be stored in memcache. After it finishes you have to regenerate token
*		token 	: $2y$10$tY.. - is your token, which you have to add to your every request /select?token=...
*	]
*	Then you can make any requests.
*	When user click on one of instruments request /select is sended [3]
*	/select has @param like {
*		type 	: is type of musical instrument. [Guitar, Electric, Bass, Banjo]
*		name 	: name of a user 
*		token 	: your token
*	}
*	In return you will get an Array with name of music instruments, its description, and percentage on how many people vote.
*	!IMPORTANT
*	When app make any request, it should check first if token is still valid.
*	Just get current timestep and minus it with time + expires of token. If it is not valid any more repeat option [2]
*
**/

/* 
* # 1
* Registration
**/
$curl ->connect('https://app.test/register?id=W|eU/9h.pBIVi^Ei"V^PcIT)Cbsvlfi)-eH1U\(z', 1);

/* 
* # 2
* getToken
**/

// uncomment to check
//$curl ->connect('https://app.test/getToken?id=$2y$10$ganPlh/l1uHJwp/d1DRkjur8H.om0iZdkThadmLrGmmaTXhrtfYzi', 1);

/*
* # 3
* select
**/

// uncomment to check
//$curl ->connect('https://app.test/select?type=Electric&name=Ilya&token=$2y$10$G48n66UMBOrO1sKL5HbBI.7LgWi1MDi5EXTwKvVduhvplEoFPQC7u', 1);


?>