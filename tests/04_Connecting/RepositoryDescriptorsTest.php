<?php
require_once(dirname(__FILE__) . '/../../inc/baseCase.php');

class Connecting_4_RepositoryDescriptorsTest extends phpcr_suite_baseCase
{
    //don't care about fixtures

    //Those constants need to be defined in the bootstrap file
    protected $expectedDescriptors = array(
        SPEC_VERSION_DESC,
        SPEC_NAME_DESC,
        REP_VENDOR_DESC,
        REP_VENDOR_URL_DESC,
        REP_NAME_DESC,
        REP_VERSION_DESC,
        LEVEL_1_SUPPORTED,
        LEVEL_2_SUPPORTED,
        OPTION_TRANSACTIONS_SUPPORTED,
        OPTION_VERSIONING_SUPPORTED,
        OPTION_OBSERVATION_SUPPORTED,
        OPTION_LOCKING_SUPPORTED,
        OPTION_QUERY_SQL_SUPPORTED,
        QUERY_XPATH_POS_INDEX,
        QUERY_XPATH_DOC_ORDER
    );

    // 24.2 Repository Descriptors
    public function testDescriptorKeys()
    {
        $rep = getRepository($this->sharedFixture['config']);
        $keys = $rep->getDescriptorKeys();
        $this->assertInternalType('array', $keys);
        $this->assertNotEmpty($keys);
        foreach ($this->expectedDescriptors as $descriptor) {
            $this->assertContains($descriptor, $keys);
        }
    }

    //TODO: Check if the values are compatible to the spec
    public function testDescription()
    {
        $rep = getRepository($this->sharedFixture['config']);
        foreach ($this->expectedDescriptors as $descriptor) {
            $str = $rep->getDescriptor($descriptor);
            $this->assertInternalType('string', $str);
            $this->assertNotEmpty($str);
        }
    }

    public function testGetDescriptorValue()
    {
        $this->markTestSkipped('TODO: implement');
    }
    public function testGetDescriptorValues()
    {
        $this->markTestSkipped('TODO: implement');
    }
    public function testIsSingleValueDescriptor()
    {
        $this->markTestSkipped('TODO: implement');
    }
    public function testIsStandardDescriptor()
    {
        $this->markTestSkipped('TODO: implement');
    }
}
