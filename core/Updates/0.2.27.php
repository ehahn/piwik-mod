<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: 0.2.27.php 1589 2009-11-16 01:35:45Z vipsoft $
 *
 * @category Piwik
 * @package Updates
 */

/**
 * @package Updates
 */
class Piwik_Updates_0_2_27 implements Piwik_iUpdate
{
	static function update()
	{
		$sqlarray = array(
			'ALTER TABLE `'. Piwik::prefixTable('log_visit') .'`
				ADD `visit_goal_converted` VARCHAR( 1 ) NOT NULL AFTER `visit_total_time`' => false,
			// 0.2.27 [826]
			'ALTER IGNORE TABLE `'. Piwik::prefixTable('log_visit') .'`
				CHANGE `visit_goal_converted` `visit_goal_converted` TINYINT(1) NOT NULL' => false,
		);

		$tables = Piwik::getTablesCreateSql();
		$sqlarray[ $tables['log_conversion'] ] = false;
		$sqlarray[ $tables['goal'] ] = false;

		$tables = Piwik::getTablesInstalled();
		foreach($tables as $tableName)
		{
			if(preg_match('/archive_/', $tableName) == 1)
			{
				$sqlarray[ 'CREATE INDEX index_all ON '. $tableName .' (`idsite`,`date1`,`date2`,`name`,`ts_archived`)' ] = false;
			}
		}

		Piwik_Updater::updateDatabase(__FILE__, $sqlarray);
	}
}
