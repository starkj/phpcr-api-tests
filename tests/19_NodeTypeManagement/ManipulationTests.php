<?php

require_once(dirname(__FILE__) . '/../../inc/baseCase.php');

/**
 * Covering jcr-2.8.3 spec $19
 *
 * (only a few tests, lots is tested by unit tests)
 */
class NodeTypeManagement_19_MoveMethodsTest extends phpcr_suite_baseCase
{

    protected function setUp()
    {
        $this->renewSession();
        parent::setUp();
    }


    /**
     * registerNodeTypesCnd is implementation specific.
     * tests that test that method should only be executed when testing jackalope
     */
    protected function checkJackalope()
    {
        if (! $this->sharedFixture['session']->getWorkspace() instanceof \Jackalope\Workspace) {
            $this->markTestSkipped('This is a test for jackalope specific functionality');
        }
    }

    /**
     * @covers Jackalope\NodeTypeManager::registerNodeTypesCnd
     */
    public function testRegisterNodeTypesCnd()
    {
        $this->checkJackalope();
        $workspace = $this->sharedFixture['session']->getWorkspace();
        $ntm = $workspace->getNodeTypeManager();

        $types = $ntm->registerNodeTypesCnd($this->cnd, true);
        $this->assertEquals(2, count($types), 'Wrong number of nodes registered');
        list($name, $type) = each($types);
        $this->assertEquals('phpcr:managed', $name);
        $this->assertInstanceOf('PHPCR\NodeType\NodeTypeDefinitionInterface', $type);
        list($name, $type) = each($types);
        $this->assertEquals('phpcr:test', $name);
        $this->assertInstanceOf('PHPCR\NodeType\NodeTypeDefinitionInterface', $type);
        $props = $type->getDeclaredPropertyDefinitions();
        $this->assertEquals(1, count($props), 'Wrong number of properties in phpcr:test');
        $this->assertEquals('phpcr:prop', $props[0]->getName());

        /* we could test if all options of cdn are properly translated, but that
         * is jackrabbit code and tested over there.
         * we just read the created nodes from the server. reading everything
         * properly is to be tested in node type read tests.
         */
    }

    /**
     * @covers Jackalope\NodeTypeManager::registerNodeTypesCnd
     * @expectedException \PHPCR\NodeType\NodeTypeExistsException
     */
    public function testRegisterNodeTypesCndNoUpdate()
    {
        $this->checkJackalope();
        $workspace = $this->sharedFixture['session']->getWorkspace();
        $ntm = $workspace->getNodeTypeManager();
        $types = $ntm->registerNodeTypesCnd($this->cnd, false);
        $types = $ntm->registerNodeTypesCnd($this->cnd, false);
    }

    public function testPrimaryItem()
    {
        $this->checkJackalope();

        // Create the node type
        $session = $this->sharedFixture['session'];
        $ntm = $session->getWorkspace()->getNodeTypeManager();
        $ntm->registerNodeTypesCnd($this->primary_item_cnd, true);

        // Create a node of that type
        $root = $session->getRootNode();

        if ($root->hasNode('test_node')) {
            $node = $root->getNode('test_node');
            $node->remove();
            $session->save();
        }

        $node = $root->addNode('test_node', 'phpcr:primary_item_test');
        $node->setProperty("phpcr:content", 'test');
        $session->save();

        // Check the primary item of the new node
        $primary = $node->getPrimaryItem();
        $this->assertInstanceOf('PHPCR\ItemInterface', $node);
        $this->assertEquals('phpcr:content', $primary->getName());
    }

    private $cnd = "
        <'phpcr'='http://www.doctrine-project.org/projects/phpcr_odm'>
         [phpcr:managed]
          mixin
          - phpcr:alias (string)
          [phpcr:test]
          mixin
          - phpcr:prop (string)
          ";

    private $primary_item_cnd = "
        <'phpcr'='http://www.doctrine-project.org/projects/phpcr_odm'>
        [phpcr:primary_item_test]
        - phpcr:content (string)
        primary
        ";
}
