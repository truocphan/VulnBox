<?php

namespace MasterStudy\Lms\Enums;

final class CurriculumMaterialType extends Enum {
	public const ASSIGNMENT  = 'stm-assignments';
	public const LESSON      = 'stm-lessons';
	public const QUIZ        = 'stm-quizzes';
	public const GOOGLE_MEET = 'stm-google-meets';
}
