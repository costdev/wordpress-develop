<?php

/**
 * Base class for WP_Filesystem_Direct tests.
 */

namespace WordPress\Tests\WP_Admin\Includes\WP_Filesystem_Direct;

abstract class WP_Filesystem_Direct_UnitTestCase extends \WP_UnitTestCase {

	/**
	 * The filesystem object.
	 *
	 * @var WP_Filesystem_Direct
	 */
	protected static $filesystem;

	protected static $file_structure = array();

	/**
	 * Sets up test assets before the class.
	 */
	public static function set_up_before_class() {
		parent::set_up_before_class();

		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

		self::$filesystem = new \WP_Filesystem_Direct( null );

		$test_data_dir = get_temp_dir() . trailingslashit( 'filesystem-direct' );

		self::$file_structure = array(
			// Directories first.
			'test_dir'     => array(
				'type' => 'd',
				'path' => $test_data_dir,
			),
			'subdir'       => array(
				'type' => 'd',
				'path' => $test_data_dir . 'subdir/',
			),

			// Then files.
			'visible_file' => array(
				'type'     => 'f',
				'path'     => $test_data_dir . 'a_file_that_exists.txt',
				'contents' => "Contents of a file.\r\nNext line of a file.\r\n",
			),
			'hidden_file'  => array(
				'type'     => 'f',
				'path'     => $test_data_dir . '.a_hidden_file',
				'contents' => "A hidden file.\r\n",
			),
			'subfile'      => array(
				'type'     => 'f',
				'path'     => $test_data_dir . 'subdir/subfile.txt',
				'contents' => "A file in a subdirectory.\r\n",
			),
		);
	}

	/**
	 * Creates any missing test assets before each test.
	 */
	public function set_up() {
		parent::set_up();

		foreach ( self::$file_structure as $entry ) {
			if ( 'd' === $entry['type'] ) {
				$this->create_directory_if_needed( $entry['path'] );

				$this->assertDirectoryExists(
					$entry['path'],
					'Path: "' . $entry['path'] . '" does not exist.'
				);
			} elseif ( 'f' === $entry['type'] ) {
				$this->create_file_if_needed(
					$entry['path'],
					isset( $entry['contents'] ) ? $entry['contents'] : ''
				);

				$this->assertFileExists(
					$entry['path'],
					'Path: "' . $entry['path'] . '" does not exist.'
				);
			}
		}
	}

	/**
	 * Removes any existing test assets after each test.
	 */
	public function tear_down() {
		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		foreach ( array_reverse( self::$file_structure ) as $entry ) {
			if ( 'f' === $entry['type'] ) {
				@unlink( $entry['path'] );

				$this->assertFileNotExists(
					$entry['path'],
					'Path: "' . $entry['path'] . '" does not exist.'
				);
			} elseif ( 'd' === $entry['type'] ) {
				@rmdir( $entry['path'] );

				$this->assertDirectoryNotExists(
					$entry['path'],
					'Path: "' . $entry['path'] . '" does not exist.'
				);
			}
		}
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged

		parent::tear_down();
	}

	private static function create_directory_if_needed( $path ) {
		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		if ( @is_dir( $path ) ) {
			return;
		}

		if ( @file_exists( $path ) ) {
			return;
		}

		@mkdir( $path );
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged
	}

	private static function create_file_if_needed( $path, $contents = '' ) {
		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		if ( @file_exists( $path ) ) {
			return;
		}

		if ( @is_dir( $path ) ) {
			return;
		}

		@file_put_contents( $path, $contents );
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_paths_that_exist() {
		return array(
			'a file that exists'      => array(
				'path'     => 'a_file_that_exists.txt',
				'expected' => true,
				'type'     => 'f',
			),
			'a directory that exists' => array(
				'path'     => '',
				'expected' => true,
				'type'     => 'd',
			),
		);
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public static function data_paths_that_do_not_exist() {
		return array(
			'a file that does not exist'      => array(
				'path'     => 'a_file_that_does_not_exist.txt',
				'expected' => false,
				'type'     => 'f',
			),
			'a directory that does not exist' => array(
				'path'     => 'a_directory_that_does_not_exist',
				'expected' => false,
				'type'     => 'd',
			),
		);
	}

}
