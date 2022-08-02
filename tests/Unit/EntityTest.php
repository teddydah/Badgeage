<?php

namespace App\Tests\Unit;

use App\Entity\Printer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityTest extends KernelTestCase
{
    public function getEntity(): Printer
    {
        return (new Printer())
            ->setNom('printer')
            ->setIp('192.000.00.000')
            ->setPort(9100);
    }

    public function assertHasErrors(Printer $printer, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($printer);
        $this->assertCount($number, $error);
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankNomEntity()
    {
        $this->assertHasErrors($this->getEntity()->setNom(''), 1);
    }

    public function testInvalidBlankIpEntity()
    {
        $this->assertHasErrors($this->getEntity()->setIp(''), 1);
    }
}
