<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ApplicationTest extends WebTestCase
{
    public function testLogIn()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Log in')->form();
        $client->submit($form,
            array('_username' => 'user',
                '_password' => 'user'));


        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }


    public function testRegister()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getManager();

        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form();
        $client->submit($form,
            array('form[firstName]' => 'userReg',
                'form[lastName]' => 'userReg',
                'form[username]' => 'userReg',
                'form[email]' => 'user',
                'form[password][first]' => 'user321',
                'form[password][second]' => 'user321'));

        // test and clean up

        $user = $em->getRepository('AppBundle:User')
            ->loadUserByUsername('userReg');

        $this->assertEquals('userReg', $user->getUsername());

        if ($user != null) {
            $em->remove($user);
            $em->flush();
        }
    }
}
