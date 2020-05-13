<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProjectsStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProjectsStatusesTable Test Case
 */
class ProjectsStatusesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.projects_statuses',
        'app.projects',
        'app.clients',
        'app.groups',
        'app.users',
        'app.modgroups',
        'app.usertypes',
        'app.user_projects',
        'app.project_types',
        'app.users_projects',
        'app.user',
        'app.project'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ProjectsStatuses') ? [] : ['className' => 'App\Model\Table\ProjectsStatusesTable'];
        $this->ProjectsStatuses = TableRegistry::get('ProjectsStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProjectsStatuses);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
