<?php

namespace AquilaFeatures;

class Hello {
	function __construct() {
		echo 'hi';
		wp_die('hey');
	}
}

new Hello();
