<?php

namespace Tests\Services;

use CubeSystems\Leaf\Services\Stub;
use CubeSystems\Leaf\Services\StubRegistry;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

class StubRegistryTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp()
    {
        $this->stubRegistry = Mockery::mock( StubRegistry::class )->makePartial();
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     * @return void
     */
    public function itShouldMakeSimpleStub()
    {
        $content = "simple\n\tstub";

        $registry = $this->getStubRegistry( $content );

        $registry->make( 'name', [] );

        $this->assertEquals( $content, $registry->make( 'name', [] ) );
    }

    /**
     * @test
     * @return void
     */
    public function itShouldMakeStubWithArguments()
    {
        $content = "I like to {{firstAction}} and {{secondAction}}";
        $expected = "I like to sing and dance";

        $registry = $this->getStubRegistry( $content );

        $result = $registry->make( 'name', [
            'firstAction' => 'sing',
            'secondAction' => 'dance'
        ] );

        $this->assertEquals( $expected, $result );
    }

    /**
     * @param string $content
     * @return StubRegistry|Mock
     */
    private function getStubRegistry( $content )
    {
        $registry = Mockery::mock( StubRegistry::class )->makePartial();
        $stub = Mockery::mock( Stub::class );

        $stub->shouldReceive( 'getContents' )->andReturn( $content );

        $registry->shouldReceive( 'findByName' )->andReturn( $stub );

        return $registry;
    }
}