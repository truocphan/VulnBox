<?php

namespace MasterStudy\Lms\Enums;

final class LessonVideoType extends Enum {
	public const EMBED         = 'embed';
	public const EXT_LINK      = 'ext_link';
	public const HTML          = 'html';
	public const PRESTO_PLAYER = 'presto_player';
	public const SHORTCODE     = 'shortcode';
	public const VIMEO         = 'vimeo';
	public const YOUTUBE       = 'youtube';
}
