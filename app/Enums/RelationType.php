<?php

namespace App\Enums;

enum RelationType: string
{
	case FATHER = 'Father';
	case MOTHER = 'Mother';
	case BROTHER = 'Brother';
	case SISTER = 'Sister';

	public static function getValues()
	{
		return array_column(self::cases(), 'value');
	}

	public static function getKeyValues()
	{
		return array_column(self::cases(), 'value', 'value');
	}
}
