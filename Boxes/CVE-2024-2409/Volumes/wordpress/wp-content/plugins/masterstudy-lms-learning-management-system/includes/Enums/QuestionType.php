<?php

namespace MasterStudy\Lms\Enums;

final class QuestionType extends Enum {
	public const FILL_THE_GAP  = 'fill_the_gap';
	public const IMAGE_MATCH   = 'image_match';
	public const ITEM_MATCH    = 'item_match';
	public const KEYWORDS      = 'keywords';
	public const MULTI_CHOICE  = 'multi_choice';
	public const QUESTION_BANK = 'question_bank';
	public const SINGLE_CHOICE = 'single_choice';
	public const TRUE_FALSE    = 'true_false';
}
