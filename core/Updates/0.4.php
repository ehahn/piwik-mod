<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: 0.4.php 1589 2009-11-16 01:35:45Z vipsoft $
 *
 * @category Piwik
 * @package Updates
 */

/**
 * @package Updates
 */
class Piwik_Updates_0_4 implements Piwik_iUpdate
{
	static function update()
	{
		Piwik_Updater::updateDatabase(__FILE__, array(
			'UPDATE `'. Piwik::prefixTable('log_visit') .'`
				SET location_ip=location_ip+CAST(POW(2,32) AS UNSIGNED) WHERE location_ip < 0' => false,
			'ALTER TABLE `'. Piwik::prefixTable('log_visit') .'`
				CHANGE `location_ip` `location_ip` BIGINT UNSIGNED NOT NULL' => false,
			'UPDATE `'. Piwik::prefixTable('logger_api_call') .'`
				SET caller_ip=caller_ip+CAST(POW(2,32) AS UNSIGNED) WHERE caller_ip < 0' => false,
			'ALTER TABLE `'. Piwik::prefixTable('logger_api_call') .'`
				CHANGE `caller_ip` `caller_ip` BIGINT UNSIGNED' => false,
			// 0.4 [1140]
			'ALTER TABLE `'. Piwik::prefixTable('log_visit') .'`
				CHANGE `location_ip` `location_ip` BIGINT UNSIGNED NOT NULL' => false,
			'ALTER TABLE `'. Piwik::prefixTable('logger_api_call') .'`
				CHANGE `caller_ip` `caller_ip` BIGINT UNSIGNED' => false,
		));
	}
}
