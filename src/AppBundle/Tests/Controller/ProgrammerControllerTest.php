<?php
namespace AppBundle\Tests\Controller;

use AppBundle\Tests\ApiTestCase;

class ProgrammerControllerTest extends ApiTestCase
{
    public function testPOSTProgrammer()
    {
        $nickname = 'ObjectOrienter';
        $data = array(
            'nickname' => $nickname,
            'avatarNumber' => 5,
            'tagLine' => 'a test dev!'
        );

        $response = $this->client->post('/api/programmers', [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $this->assertEquals('/api/programmers/ObjectOrienter', $response->getHeader('Location'));
        $finishedData = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('nickname', $finishedData);
        $this->assertEquals('ObjectOrienter', $finishedData['nickname']);
    }

    public function testGETProgrammer()
    {
        $this->createProgrammer(array(
            'nickname' => 'UnitTester',
            'tagLine' => 'I\'m a tester',
            'avatarNumber' => 3
        ));
        $response = $this->client->get('/api/programmers/UnitTester');
        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertiesExist($response, array(
            'nickname',
            'avatarNumber',
            'powerLevel',
            'tagLine'
        ));
        $this->asserter()->assertResponsePropertyEquals($response, 'nickname', 'UnitTester');
        $this->asserter()->assertResponsePropertyEquals($response, 'avatarNumber', 3);
        $this->asserter()->assertResponsePropertyEquals($response, 'tagLine', 'I\'m a tester');
    }

    public function testGETProgrammersCollection()
    {
        $this->createProgrammer(array(
            'nickname' => 'UnitTester',
            'tagLine' => 'I\'m a tester',
            'avatarNumber' => 3
        ));
        $this->createProgrammer(array(
            'nickname' => 'CowboyCoder',
            'tagLine' => 'I\'m a tester',
            'avatarNumber' => 5
        ));

        $response = $this->client->get('/api/programmers');
        $this->assertEquals(200, $response->getStatusCode());

        $this->asserter()->assertResponsePropertyIsArray($response, 'programmers');
        $this->asserter()->assertResponsePropertyCount($response, 'programmers', 2);
        $this->asserter()->assertResponsePropertyEquals($response, 'programmers[1].nickname', 'CowboyCoder');
    }

    public function testPUTProgrammer()
    {
        $this->createProgrammer(array(
            'nickname' => 'CowboyCoder',
            'tagLine' => 'I\'m a tester',
            'avatarNumber' => 5
        ));
        $data = array(
            'nickname' => 'CowgirlCoder',
            'tagLine' => 'I\'m a tester',
            'avatarNumber' => 2
        );
        $response = $this->client->put('/api/programmers/CowboyCoder', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'avatarNumber', 2);
        $this->asserter()->assertResponsePropertyEquals($response, 'nickname', 'CowboyCoder');
    }

    public function testDELETEProgrammer()
    {
        $this->createProgrammer(array(
            'nickname' => 'UnitTester',
            'tagLine' => 'I\'m a tester',
            'avatarNumber' => 5
        ));
        $response = $this->client->delete('/api/programmers/UnitTester');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testPATCHProgrammer()
    {
        $this->createProgrammer(array(
            'nickname' => 'CowboyCoder',
            'tagLine' => 'I\'m a tester',
            'avatarNumber' => 5
        ));
        $data = array(
            'tagLine' => 'foo'
        );
        $response = $this->client->patch('/api/programmers/CowboyCoder', [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'avatarNumber', 5);
        $this->asserter()->assertResponsePropertyEquals($response, 'tagLine', 'foo');
    }

    protected function setUp()
    {
        parent::setUp();

        $this->createUser('weaverryan', 'foo');
    }
}
