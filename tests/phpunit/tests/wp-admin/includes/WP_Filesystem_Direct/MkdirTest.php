<?php
/**
 * Tests for the WP_Filesystem_Direct::mkdir() method.
 *
 * @package WordPress
 */

namespace WordPress\Tests\WP_Admin\Includes\WP_Filesystem_Direct;

require_once __DIR__ . '/base.php';

/**
 * @ticket
 *
 * @group admin
 * @group filesystem
 * @group filesystem-direct
 *
 * @covers WP_Filesystem_Direct::mkdir
 */
class Tests_Mkdir extends WP_Filesystem_Direct_UnitTestCase {

	/**
	 * Tests that `WP_Filesystem_Direct::mkdir()` creates a directory.
	 *
	 * @dataProvider data_should_create_directory
	 *
	 * @param mixed $path The path to create.
	 */
	public function test_should_create_directory( $path ) {
		$path   = str_replace( 'TEST_DIR', self::$file_structure['test_dir']['path'], $path );
		$actual = self::$filesystem->mkdir( $path );

		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		if ( $path !== self::$file_structure['test_dir']['path'] && @is_dir( $path ) ) {
			@rmdir( $path );
		}
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged

		$this->assertTrue( $actual );
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_create_directory() {
		return array(
			'no trailing slash' => array(
				'path' => 'TEST_DIR/directory-to-create',
			),
			'a trailing slash'  => array(
				'path' => 'TEST_DIR/directory-to-create/',
			),
		);
	}

	/**
	 * Tests that `WP_Filesystem_Direct::mkdir()` does not create a directory.
	 *
	 * @dataProvider data_should_not_create_directory
	 *
	 * @param mixed $path     The path to create.
	 */
	public function test_should_not_create_directory( $path ) {
		$path = str_replace( 'TEST_DIR', self::$file_structure['test_dir']['path'], $path );

		$actual = self::$filesystem->mkdir( $path );

		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		if ( $path !== self::$file_structure['test_dir']['path'] && @is_dir( $path ) ) {
			@rmdir( $path );
		}
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged

		$this->assertFalse( $actual );
	}

	/**
	 * Data provider.
	 *
	 * @return array[]
	 */
	public function data_should_not_create_directory() {
		return array(
			'empty path'         => array(
				'path' => '',
			),
			'a path that exists' => array(
				'path' => 'TEST_DIR',
			),
		);
	}

	/**
	 * Tests that `WP_Filesystem_Direct::mkdir()` sets chmod.
	 */
	public function test_should_set_chmod() {
		$path = self::$file_structure['test_dir']['path'] . 'directory-to-create';

		$created = self::$filesystem->mkdir( $path, 0644 );
		$chmod   = substr( sprintf( '%o', fileperms( $path ) ), -4 );

		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		if ( $path !== self::$file_structure['test_dir']['path'] && @is_dir( $path ) ) {
			@rmdir( $path );
		}
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged

		$this->assertTrue( $created, 'The directory was not created.' );
		$this->assertSame( '0644', $chmod, 'The permissions are incorrect.' );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::mkdir()` sets the owner.
	 */
	public function test_should_set_owner() {

		$path = self::$file_structure['test_dir']['path'] . 'directory-to-create';

		// Get the default owner.
		self::$filesystem->mkdir( $path );
		$original_owner = fileowner( $path );
		rmdir( $path );

		$created = self::$filesystem->mkdir( $path, 0755, $original_owner + 1 );
		$owner   = fileowner( $path );

		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		if ( $path !== self::$file_structure['test_dir']['path'] && @is_dir( $path ) ) {
			@rmdir( $path );
		}
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged

		$this->assertTrue( $created, 'The directory was not created.' );
		$this->assertSame( $original_owner + 1, $owner, 'The owner is incorrect.' );
	}

	/**
	 * Tests that `WP_Filesystem_Direct::mkdir()` sets the group.
	 */
	public function test_should_set_group() {
		$path = self::$file_structure['test_dir']['path'] . 'directory-to-create';

		// Get the default group.
		self::$filesystem->mkdir( $path );
		$original_group = filegroup( $path );
		rmdir( $path );

		$created = self::$filesystem->mkdir( $path, 0755, false, $original_group + 1 );
		$group   = filegroup( $path );

		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
		if ( $path !== self::$file_structure['test_dir']['path'] && @is_dir( $path ) ) {
			@rmdir( $path );
		}
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged

		$this->assertTrue( $created, 'The directory was not created.' );
		$this->assertSame( $original_group + 1, $group, 'The group is incorrect.' );
	}

}
