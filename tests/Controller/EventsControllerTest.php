<?php

namespace App\Tests\Controller;

use App\Entity\Events;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EventsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $eventRepository;
    private string $path = '/events/';

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);
    
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event index');
    }
    
    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));
    
        self::assertResponseStatusCodeSame(200);
    
        $this->client->submitForm('Save', [
            'event[title]' => 'Testing',
            'event[description]' => 'Testing',
            'event[lieu]' => 'Testing',
            'event[date]' => '2025-02-13', // valid date format
        ]);
    
        self::assertResponseRedirects($this->path);
        self::assertSame(1, $this->eventRepository->count([]));
    }
    
    public function testShow(): void
    {
        $fixture = new Events();
        $fixture->setTitle('My Title');
        $fixture->setDescription('My Description');
        $fixture->setLieu('My Place');
        $fixture->setDate(new \DateTime('2025-02-13')); // Use DateTime object for date
    
        $this->manager->persist($fixture);
        $this->manager->flush();
    
        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
    
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event');
    
        self::assertSelectorTextContains('.event-title', $fixture->getTitle());
        self::assertSelectorTextContains('.event-description', $fixture->getDescription());
        self::assertSelectorTextContains('.event-lieu', $fixture->getLieu());
    }
    
    public function testEdit(): void
    {
        $fixture = new Events();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setLieu('Value');
        $fixture->setDate(new \DateTime('2025-02-13')); // Use DateTime object for date
    
        $this->manager->persist($fixture);
        $this->manager->flush();
    
        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));
    
        $this->client->submitForm('Update', [
            'event[title]' => 'Something New',
            'event[description]' => 'Something New',
            'event[lieu]' => 'Something New',
            'event[date]' => '2025-02-14', // Update date
        ]);
    
        self::assertResponseRedirects('/events/');
    
        $fixture = $this->eventRepository->findAll();
    
        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getLieu());
        self::assertSame('2025-02-14', $fixture[0]->getDate()->format('Y-m-d'));
    }
    
    public function testRemove(): void
    {
        $fixture = new Events();
        $fixture->setTitle('Value');
        $fixture->setDescription('Value');
        $fixture->setLieu('Value');
        $fixture->setDate(new \DateTime('2025-02-13')); // Use DateTime object for date
    
        $this->manager->persist($fixture);
        $this->manager->flush();
    
        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');
    
        self::assertResponseRedirects('/events/');
        self::assertSame(0, $this->eventRepository->count([]));
    }
}
