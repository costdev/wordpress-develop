<?php

/**
 * Test the wp_upload_bits function
 *
 * @group Functions
 * @group Upload
 * @covers ::wp_check_filetype
 */
class Test_wp_check_filetype extends WP_UnitTestCase {

	/**
	 * Tests that wp_check_filetype() returns the correct extension and MIME type.
	 *
	 * @ticket 57151
	 *
	 * @dataProvider data_wp_check_filetype
	 *
	 * @param string     $filename   The filename to check.
	 * @param array|null $mimes An array of MIME types, or null.
	 * @param array      $expected   An array containing the expected extension and MIME type.
	 */
	public function test_wp_check_filetype( $filename, $mimes, $expected ) {
		$this->assertSame( $expected, wp_check_filetype( $filename, $mimes ) );
	}

	/**
	 * Data provider.
	 *
	 * @return[]
	 */
	public function data_wp_check_filetype() {
		return array(
			'default'     => array(
				'filename' => 'canola.jpg',
				'mimes'    => null,
				'expected' => array(
					'ext'  => 'jpg',
					'type' => 'image/jpeg',
				),
			),
			'short_mines' => array(
				'filename' => 'canola.jpg',
				'mimes'    => array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'gif'          => 'image/gif',
				),
				'expected' => array(
					'ext'  => 'jpg',
					'type' => 'image/jpeg',
				),
			),
			'.jpeg filename and jpg|jpeg|jpe'         => array(
				'filename' => 'canola.jpeg',
				'mimes'    => array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'gif'          => 'image/gif',
				),
				'expected' => array(
					'ext'  => 'jpeg',
					'type' => 'image/jpeg',
				),
			),
			'.jpe filename and jpg|jpeg|jpe'          => array(
				'filename' => 'canola.jpe',
				'mimes'    => array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'gif'          => 'image/gif',
				),
				'expected' => array(
					'ext'  => 'jpe',
					'type' => 'image/jpeg',
				),
			),
			'uppercase filename and jpg|jpeg|jpe'     => array(
				'filename' => 'canola.JPG',
				'mimes'    => array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'gif'          => 'image/gif',
				),
				'expected' => array(
					'ext'  => 'JPG',
					'type' => 'image/jpeg',
				),
			),
				'filename' => 'canola.XXX',
				'mimes'    => array(
					'jpg|jpeg|jpe' => 'image/jpeg',
					'gif'          => 'image/gif',
				),
				'expected' => array(
					'ext'  => false,
					'type' => false,
				),
			),
			'bad_mines'   => array(
				'filename' => 'canola.jpg',
				'mimes'    => array(
					'gif' => 'image/gif',
				),
				'expected' => array(
					'ext'  => false,
					'type' => false,
				),
			),

		);
	}
}
