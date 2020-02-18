<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Course;
use App\Entity\Student;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadAuthor($manager);
        $this->loadStudent($manager);
        $this->loadCourse($manager);
        $this->loadUser($manager);
    }

    public function loadCourse(ObjectManager $manager){


        for($i=0; $i<300; $i++){
            $student = $this->getReference('student_' . rand(1, 49));
            $author = $this->getReference('author_'. rand(1, 99));
            $course = new Course();
            $course->setName($this->faker->realText(30));
            $course->setAuthor($author);
            $course->setStudent($student);
            $this->setReference('course_' . $i, $course);
            $manager->persist($course);
        }
        $manager->flush();

    }

    public function loadStudent(ObjectManager $manager){


        for($i=0; $i<50; $i++) {
            $student = new Student();
            $student->setFirstName($this->faker->firstName());
            $student->setLastName($this->faker->lastName());
            $this->addReference('student_' . $i, $student);
            $manager->persist($student);
        }
        $manager->flush();


    }

    public function loadAuthor(ObjectManager $manager){
        for($i=0; $i<100; $i++) {
            $author = new Author();
            $author->setName($this->faker->name());
            $this->addReference('author_' . $i, $author);
            $manager->persist($author);
        }
        $manager->flush();
    }

    public function loadUser(ObjectManager $manager){
        $user = new User();

        $user->setUsername('Admin');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Admin123'
            ));
        $user->setEmail('admin@edrisa.com');
        $user->setStatus(true);

        $manager->persist($user);
        $manager->flush();
    }
}
