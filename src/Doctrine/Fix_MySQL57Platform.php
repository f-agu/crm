<?php
namespace App\Doctrine;

/**
 * Manual fix for: Doctrine\DBAL\Platforms\MySQL57Platform
 */
class Fix_MySQL57Platform
{
	protected function doModifyLimitQuery($query, $limit, $offset)
	{
		if ($limit !== null || $offset > 0) {
			$query .= ' LIMIT ';
			if ($offset > 0) {
				$query .= $offset . ', ';
			}
			$query .= $limit;
		}
		return $query;
	}
}

