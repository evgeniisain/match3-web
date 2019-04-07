<?php


namespace App\Tests\functional\Controller;


use App\Controller\UserController;
use App\Model\RegisterForm;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\SecurityBundle\Tests\Functional\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    const TEST_USERNAME = 'testusername';
    const TEST_PASSWORD = 'testpassword';

    public function testRegister() {
        $entityManagerMock = $this->createMock(EntityManager::class);

        $client = static::createClient();

        $form = new RegisterForm();
        $form->username = static::TEST_USERNAME;
        $form->password = static::TEST_PASSWORD;
        $form->confirmPassword = static::TEST_PASSWORD;

        $client->request('POST', '/user/register', (array) $form);

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testAuth() {

    }

    public function testCheckAuth() {

    }
}