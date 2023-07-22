<?php
/*
 * This file is part of Part-DB (https://github.com/Part-DB/Part-DB-symfony).
 *
 *  Copyright (C) 2019 - 2023 Jan Böhmer (https://github.com/jbtronics)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Tests\Repository;

use App\Entity\Attachments\Attachment;
use App\Entity\Attachments\PartAttachment;
use App\Entity\Parts\Category;
use App\Entity\Parts\Part;
use App\Entity\UserSystem\Group;
use App\Entity\UserSystem\User;
use App\Repository\DBElementRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DBElementRepositoryTest extends KernelTestCase
{

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')->getManager();
    }

    public function testFindByIDInMatchingOrder(): void
    {
        $repo = $this->entityManager->getRepository(Category::class);

        $elements = $repo->findByIDInMatchingOrder([2, 1, 5, 3]);

        //Elements are ordered the same way as the ID array
        $this->assertCount(4, $elements);
        $this->assertSame(2, $elements[0]->getId());
        $this->assertSame(1, $elements[1]->getId());
        $this->assertSame(5, $elements[2]->getId());
        $this->assertSame(3, $elements[3]->getId());
    }

    public function testChangeID(): void
    {
        $repo = $this->entityManager->getRepository(User::class);

        $noread_user = $repo->findOneBy(['name' => 'noread']);

        $this->assertNotNull($noread_user);
        $old_id = $noread_user->getId();

        $repo->changeID($noread_user, 999999);
        $this->assertSame(999999, $noread_user->getId());
        $this->assertNotSame($old_id, $noread_user->getId());


        //ID should be persistent
        $this->entityManager->refresh($noread_user);
        $this->assertSame(999999, $noread_user->getId());
    }

    public function testGetElementsFromIDArray(): void
    {
        $repo = $this->entityManager->getRepository(Category::class);

        $elements = $repo->getElementsFromIDArray([2, 1, 3]);

        //Elements are ordered by ID or
        $this->assertCount(3, $elements);
        $this->assertSame(1, $elements[0]->getId());
        $this->assertSame(2, $elements[1]->getId());
        $this->assertSame(3, $elements[2]->getId());
    }
}
