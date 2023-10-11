<?php
/**
 * Calendar class
 *
 * @package Welcart
 */
class calendarData {

	/**
	 * Year
	 *
	 * @var string
	 */
	public $_year;

	/**
	 * Month
	 *
	 * @var string
	 */
	public $_month;

	/**
	 * Day
	 *
	 * @var string
	 */
	public $_day;

	/**
	 * Row weeks
	 *
	 * @var string
	 */
	public $_row;

	/**
	 * Date
	 *
	 * @var string
	 */
	public $_date;

	/**
	 * Date text
	 *
	 * @var string
	 */
	public $_datetext;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_row      = 0;
		$this->_date     = array();
		$this->_datetext = array();
	}

	/**
	 * Set Today
	 *
	 * @param int $year Year.
	 * @param int $month Month.
	 * @param int $day Day.
	 * @return void
	 */
	public function setToday( $year, $month, $day ) {
		$this->_year  = $year;
		$this->_month = $month;
		$this->_day   = $day;
	}

	/**
	 * Get date
	 *
	 * @param int $row Row.
	 * @param int $d Day.
	 * @return string
	 */
	public function getDate( $row, $d ) {
		return $this->_date[ $row ][ $d ];
	}

	/**
	 * Get date text
	 *
	 * @param int $row Row.
	 * @param int $d Day.
	 * @return string
	 */
	public function getDateText( $row, $d ) {
		return $this->_datetext[ $row ][ $d ];
	}

	/**
	 * Get row
	 *
	 * @return int
	 */
	public function getRow() {
		return $this->_row;
	}

	/**
	 * Set Calendar
	 */
	public function setCalendarData() {
		$day     = 1; /* 当月開始日に設定 */
		$firstw  = getWeek( $this->_year, $this->_month, $day ); /* 当月開始日の曜日 */
		$lastday = getLastDay( $this->_year, $this->_month ); /* 当月最終日 */
		$lastw   = getWeek( $this->_year, $this->_month, $lastday ); /* 当月最終日の曜日 */

		/* 1週目 */
		for ( $d = 0; $d <= 6; $d++ ) {
			if ( (int) $firstw === $d ) {
				$this->_date[0][ $d ]     = sprintf( '%04d-%02d-%02d', $this->_year, $this->_month, $day );
				$this->_datetext[0][ $d ] = $day;
			} elseif ( $firstw < $d ) {
				list( $this->_year, $this->_month, $day ) = getNextDay( $this->_year, $this->_month, $day );
				$this->_date[0][ $d ]                     = sprintf( '%04d-%02d-%02d', $this->_year, $this->_month, $day );
				$this->_datetext[0][ $d ]                 = $day;
			} else {
				$this->_date[0][ $d ]     = '';
				$this->_datetext[0][ $d ] = '';
			}
		}
		/* 2～4週目 */
		for ( $d = 0; $d <= 6; $d++ ) {
			list( $this->_year, $this->_month, $day ) = getNextDay( $this->_year, $this->_month, $day );
			$this->_date[1][ $d ]                     = sprintf( '%04d-%02d-%02d', $this->_year, $this->_month, $day );
			$this->_datetext[1][ $d ]                 = $day;
		}
		for ( $d = 0; $d <= 6; $d++ ) {
			list( $this->_year, $this->_month, $day ) = getNextDay( $this->_year, $this->_month, $day );
			$this->_date[2][ $d ]                     = sprintf( '%04d-%02d-%02d', $this->_year, $this->_month, $day );
			$this->_datetext[2][ $d ]                 = $day;
		}
		for ( $d = 0; $d <= 6; $d++ ) {
			list( $this->_year, $this->_month, $day ) = getNextDay( $this->_year, $this->_month, $day );
			$this->_date[3][ $d ]                     = sprintf( '%04d-%02d-%02d', $this->_year, $this->_month, $day );
			$this->_datetext[3][ $d ]                 = $day;
		}
		/* 5週目 */
		for ( $d = 0; $d <= 6; $d++ ) {
			if ( (int) $lastday === (int) $day ) {
				break;
			} else {
				list( $this->_year, $this->_month, $day ) = getNextDay( $this->_year, $this->_month, $day );
				$this->_date[4][ $d ]                     = sprintf( '%04d-%02d-%02d', $this->_year, $this->_month, $day );
				$this->_datetext[4][ $d ]                 = $day;
			}
		}
		if ( 0 < $d && $d <= 6 ) {
			while ( $d <= 6 ) {
				$this->_date[4][ $d ]     = '';
				$this->_datetext[4][ $d ] = '';
				$d++;
			}
		} elseif ( 0 !== $d ) {
			/* 6週目 */
			for ( $d = 0; $d <= 6; $d++ ) {
				if ( (int) $lastday === (int) $day ) {
					break;
				} else {
					list( $this->_year, $this->_month, $day ) = getNextDay( $this->_year, $this->_month, $day );
					$this->_date[5][ $d ]                     = sprintf( '%04d-%02d-%02d', $this->_year, $this->_month, $day );
					$this->_datetext[5][ $d ]                 = $day;
				}
			}
			if ( $d > 0 ) {
				while ( $d <= 6 ) {
					$this->_date[5][ $d ]     = '';
					$this->_datetext[5][ $d ] = '';
					$d++;
				}
			}
		}

		$this->_row = ( is_array( $this->_date ) ) ? count( $this->_date ) : 0;
	}
}
