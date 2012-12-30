<?php

require __DIR__ . '/../resizer.php';

use Resizer\Resizer;
use Laravel\File;

/**
 * Class tests all functionality of the Resizer class.
 *
 * Running the tests:
 *  - place this bundle in a local laravel installation
 *  - execute in the project root: php artisan test resizer
 *
 *
 * Things TODO better here:
 *  - test all options of the Resizer class, only "exact" and "crop" tested yet
 *  - more & better tests
 *  - mock the filesystem ?
 */
class TestResizer extends PHPUnit_Framework_TestCase {

    /**
     * Resizer instance to work with in each test
     *
     * @var Resizer\Resizer
     */
    private $resizer;

    /**
     * Setup before each test
     *
     * Initialize a fresh Resizer instance for every test using a 600x600 sample image
     */
    protected function setUp() {
        $this->resizer = new Resizer(__DIR__ . '/images/sample600x600.jpeg');
    }

    /**
     * Tear down after each test
     */
    protected function tearDown() {
        unset($this->resizer);
    }

    /**
     * Image not found should throw a ResizerException when instanciating a new Resizer object
     *
     * @expectedException Resizer\ResizerException
     */
    public function test_image_not_found_should_throw_exception1() {
        new Resizer('not/found.png');
    }

    /**
     * Image not found should throw a ResizerException using static open() method
     *
     * @expectedException Resizer\ResizerException
     */
    public function test_image_not_found_should_throw_exception2() {
        Resizer::open(false);
    }

    /**
     * Test get_width() and get_height()
     */
    public function test_width_and_height_getters() {
        // test image is 600x600
        $this->assertEquals(600, $this->resizer->get_width());
        $this->assertEquals(600, $this->resizer->get_height());
    }

    /**
     * Test the "exact" option
     */
    public function test_resize_exact() {
        $w        = 800;
        $h        = 600;
        $img_path = __DIR__ . '/images/resized_exact.png';
        $this->resizer->resize($w, $h, 'exact');
        $this->resizer->save($img_path, 100);

        // check new image dimension
        $resized = new Resizer($img_path);
        $this->assertEquals($w, $resized->get_width());
        $this->assertEquals($h, $resized->get_height());
        File::delete($img_path);

        $w = 10;
        $h = 10;
        $this->resizer->resize($w, $h, 'exact');
        $this->resizer->save($img_path, 100);

        // check new image dimension
        $resized = new Resizer($img_path);
        $this->assertEquals($w, $resized->get_width());
        $this->assertEquals($h, $resized->get_height());
        File::delete($img_path);
    }

    /**
     * Test the "crop" option
     */
    public function test_resize_crop() {
        // crop to simple 16/9 format
        $w = 200;
        $h = 112; //.50;

        // override some resizer configs on the fly
        // we want to test top/left and center/left
        Config::set('resizer::defaults.crop_vertical_start_point', 'top');
        Config::set('resizer::defaults.crop_horizontal_start_point', 'left');
        $img_path = __DIR__ . '/images/resized_cropped_top_left.png';
        $this->resizer->resize($w, $h, 'crop');
        $this->resizer->save($img_path, 100);

        // check new image dimension
        $resized = new Resizer($img_path);
        $this->assertEquals($w, $resized->get_width());
        $this->assertEquals($h, $resized->get_height());
        // File::delete($img_path);

        Config::set('resizer::defaults.crop_vertical_start_point', 'center');
        Config::set('resizer::defaults.crop_horizontal_start_point', 'left');
        $img_path = __DIR__ . '/images/resized_cropped_center_left.png';
        $this->resizer->resize($w, $h, 'crop');
        $this->resizer->save($img_path, 100);

        // check new image dimension
        $resized = new Resizer($img_path);
        $this->assertEquals($w, $resized->get_width());
        $this->assertEquals($h, $resized->get_height());
        // File::delete($img_path);
    }

    /**
     * @expectedException Resizer\ResizerException
     */
    public function test_resize_not_known_option_should_throw_exception() {
        $unsupported_option = 'unsupported_option';
        $this->resizer->resize(1, 1, $unsupported_option);
    }
}
