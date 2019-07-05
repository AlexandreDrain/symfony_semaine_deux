<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Produit;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $images = [
          'ballon.jpg',
          'frigo.jpg',
          'hamac.jpg',
          'oiseau.jpg',
          'parasol.jpg',
          'tomate.jpg',
          'ventilo.jpg'
        ];

        // $product = new Product();
        $generator = Factory::create('fr_FR');

        $populator = new Populator($generator, $manager);

        // Création des category
        $populator->addEntity(Category::class, 10);
        $populator->addEntity(Tag::class, 20);
        $populator->addEntity(User::class, 20);
        $populator->addEntity(Produit::class, 197, [
                'price' => function () use ($generator) {
                    return $generator->randomFloat(2,0, 9999999.99);
                },
                'imageName' => function () use ($images) {
                    return $images[rand(0,sizeof($images) - 1)];
                }
            ]);
        // Flush
        $populator->execute();
    }

}
