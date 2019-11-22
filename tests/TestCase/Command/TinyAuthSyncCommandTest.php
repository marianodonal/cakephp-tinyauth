<?php
namespace TinyAuth\Test\TestCase\Command;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\TestSuite\ConsoleIntegrationTestCase;

class TinyAuthSyncCommandTest extends ConsoleIntegrationTestCase {

	/**
	 * setup method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		Configure::write('Roles', [
			'user' => 1,
			'moderator' => 2,
			'admin' => 3,
		]);

		$this->setAppNamespace();
		$this->useCommandRunner();
	}

	/**
	 * @return void
	 */
	public function testSync() {
		Configure::write('TinyAuth.aclFilePath', TESTS . 'test_files/subfolder/');
		Configure::write('TinyAuth.allowFilePath', TESTS . 'test_files/');

		$folder = new Folder();
		$folder->copy(['from' => TESTS . 'test_app' . DS . 'Controller' . DS, 'to' => '/tmp' . DS . 'src' . DS . 'Controller' . DS]);

		$this->exec('tiny_auth_sync foo,bar -d -v');

		$this->assertExitCode(Shell::CODE_SUCCESS);
		$this->assertOutputContains('index = admin');
		$this->assertOutputContains('[Offers]');
		$this->assertOutputContains('* = foo,bar');
	}

}
