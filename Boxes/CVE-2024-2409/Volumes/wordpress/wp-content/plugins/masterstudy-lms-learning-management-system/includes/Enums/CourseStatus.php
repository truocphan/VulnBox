<?php

namespace MasterStudy\Lms\Enums;

/**
 * @method static self Hot()
 * @method static self New()
 * @method static self Special()
 */
final class CourseStatus extends Enum {
	public const HOT     = 'hot';
	public const NEW     = 'new';
	public const SPECIAL = 'special';
}
