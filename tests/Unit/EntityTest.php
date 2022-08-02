<?php

namespace App\Tests\Unit;

use App\Entity\Printer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

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
        $errors = self::$container->get('validator')->validate($printer);
        $messages = [];

        /**
         * @var ConstraintViolation $error
         */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
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
