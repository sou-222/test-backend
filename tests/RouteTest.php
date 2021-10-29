<?php declare(strict_types=1);

use App\Entity\Channel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RouteTest extends WebTestCase
{
    public function test_route_tv(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $em = $container->get('doctrine')->getManager();
        $channel = new Channel();
        $channel->setName('test_tf1');
        $channel->setNum(1);
        $channel->setLanguage('fr');
        $em->persist($channel);
        $em->flush();

        $client = static::createClient();

        $client->request('GET', '/tv/en');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $client->request('GET', '/tv/fr');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $response = $client->getResponse();
        $json = json_decode($response->getContent(), true);
        $found = false;
        foreach($json as $item)
        {
            if($item['name'] == $channel->getName())
            {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);

        $em->remove($channel);
        $em->flush();
    }
}

