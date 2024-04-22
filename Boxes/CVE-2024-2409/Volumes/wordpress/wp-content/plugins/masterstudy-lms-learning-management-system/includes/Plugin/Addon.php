<?php

namespace MasterStudy\Lms\Plugin;

use MasterStudy\Lms\Plugin;

interface Addon {
	public function get_name(): string;
	public function register( Plugin $plugin): void;
}
