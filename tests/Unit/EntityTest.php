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
            ->setNom('Printer')
            ->setIp('192.168.35.000')
            ->setPort(9100);
    }

    public function assertHasErrors(Printer $printer, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($printer);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    /**
     * @return void
     * Teste la validité de l'entité Printer
     */
    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity());
    }

    /**
     * @return void
     * Teste le remplissage de la propriété "nom"
     */
    public function testInvalidBlankNom()
    {
        $this->assertHasErrors($this->getEntity()->setNom(''), 1);
    }

    /**
     * @return void
     * Teste la longueur de la propriété "ip"
     */
    public function testInvalidLengthIp()
    {
        $this->assertHasErrors($this->getEntity()->setIp('192.168.35.000.000.000'), 1);
    }

    /**
     * @return void
     *  Teste l'unicité de la propriété "nom"
     */
    public function testInvalidUsedNom()
    {
        $this->assertHasErrors($this->getEntity()->setNom('Chef Atelier'), 1);
    }
}
