<?php
/**
 * FileRestrictions class.
 *
 * @since 1.0.0
 */

namespace Masteriyo\FileRestrictions;

class FileRestrictions {
	/**
	 * Initialize file restriction handlers.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		LessonVideoRestriction::init();
	}
}
