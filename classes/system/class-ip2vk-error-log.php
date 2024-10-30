<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Writes plugin logs
 *
 * @package			iCopyDoc Plugins (v1, core 15-04-2023)
 * @subpackage		Import Products to VK
 * @since			0.1.0
 * 
 * @version			0.1.0 (14-04-2023)
 * @author			Maxim Glazunov
 * @link			https://icopydoc.ru/
 * @see				
 * 
 * @param	mixed	$text_to_log (require)
 * @param	string	$log_dir_name (not require)
 *
 * @return			
 *
 * @depends			classes:	
 *					traits:		
 *					methods:	
 *					functions:	
 *					constants:	YFYM_PLUGIN_UPLOADS_DIR_PATH
 *					options:	ip2vk_keeplogs
 *
 */

final class IP2VK_Error_Log {
	/**
	 * Text to log
	 * @var 
	 */
	protected $text_to_log;
	/**
	 * Path to the log file
	 * @var 
	 */
	protected $log_file_path; // /home/site.ru/public_html/wp-content/uploads/import-products-to-vk/plugin.log

	/**
	 * Summary of __construct
	 * 
	 * @param mixed $text_to_log
	 * @param string $log_dir_name
	 * 
	 * @return void
	 */
	public function __construct( $text_to_log, $log_dir_name = IP2VK_PLUGIN_UPLOADS_DIR_PATH ) {
		$this->text_to_log = $text_to_log;
		if ( is_dir( $log_dir_name ) ) {
			$this->log_file_path = $log_dir_name . '/plugin.log';
		} else {
			if ( mkdir( $log_dir_name ) ) {
				$this->log_file_path = $log_dir_name . '/plugin.log';
			} else {
				$this->log_file_path = false;
				error_log( 'ERROR: IP2VK_Error_Log: No folder "' . $log_dir_name . '"; Line: ' . __LINE__, 0 );
			}
		}

		if ( $this->keeplogs_status() ) { // если включено вести логи
			if ( $this->get_file_path() === false ) {
				return;
			} else {
				$this->save_log( $text_to_log );
			}
		}
	}

	/**
	 * Summary of __toString
	 * 
	 * @return mixed
	 */
	public function __toString() {
		return $this->get_array_as_string( $this->text_to_log );
	}

	/**
	 * Save log to file
	 * 
	 * @param mixed $text_to_log
	 * 
	 * @return void
	 */
	protected function save_log( $text_to_log ) {
		if ( is_array( $text_to_log ) ) {
			$r = get_array_as_string( $text_to_log );
			unset( $text_to_log );
			$text_to_log = $r;
		}
		file_put_contents(
			$this->get_file_path(),
			'[' . date( 'Y-m-d H:i:s' ) . '] ' . $text_to_log . PHP_EOL, FILE_APPEND
		);
	}

	/**
	 * Returns the path to the log file
	 * 
	 * @return string
	 */
	protected function get_file_path() {
		return $this->log_file_path;
	}

	/**
	 * Checks whether logs is enabled
	 * 
	 * @return bool
	 */
	protected function keeplogs_status() {
		if ( is_multisite() ) {
			$v = get_blog_option( get_current_blog_id(), 'ip2vk_keeplogs' );
		} else {
			$v = get_option( 'ip2vk_keeplogs' );
		}
		if ( $v === 'on' ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Converts data to a string
	 * 
	 * @param mixed $text
	 * @param string $new_line
	 * @param int $i
	 * @param string $res
	 * 
	 * @return string
	 */
	protected function get_array_as_string( $text, $new_line = PHP_EOL, $i = 0, $res = '' ) {
		$tab = '';
		for ( $x = 0; $x < $i; $x++ ) {
			$tab = '---' . $tab;
		}
		if ( is_object( $text ) ) {
			$text = (array) $text;
		}
		if ( is_array( $text ) ) {
			$i++;
			foreach ( $text as $key => $value ) {
				if ( is_array( $value ) ) { // массив
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")";
					$res .= $tab . $this->get_array_as_string( $value, $new_line, $i );
				} else if ( is_object( $value ) ) { // объект
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")";
					$value = (array) $value;
					$res .= $tab . $this->get_array_as_string( $value, $new_line, $i );
				} else {
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")" . $value;
				}
			}
		} else {
			$res .= $new_line . $tab . $text;
		}
		return $res;
	}
}