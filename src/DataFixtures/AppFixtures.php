<?php

namespace App\DataFixtures;

use App\Entity\Accessory;
use App\Entity\CategoryAccessory;
use App\Entity\Illustration;
use App\Entity\Promotion;
use App\Entity\Trottinette;
use App\Entity\TrottinetteDescriptionSection;
use App\Entity\Tva;
use App\Entity\User;
use App\Entity\Weight;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // 0. Utilisateur Administrateur (admin@admin.com / Admin)
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'Admin'));

        if (method_exists($admin, 'setTel')) {
            $admin->setTel('0600000000');
        }
        if (method_exists($admin, 'setFirstname')) {
            $admin->setFirstname('Admin');
        }
        if (method_exists($admin, 'setLastname')) {
            $admin->setLastname('MHvolt');
        }
        if (method_exists($admin, 'setIsVerified')) {
            $admin->setIsVerified(true);
        }
        $manager->persist($admin);

        // 0bis. Utilisateur Client standard (user@user.com / User)
        $user = new User();
        $user->setEmail('user@user.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'User'));

        if (method_exists($user, 'setTel')) {
            $user->setTel('0611223344');
        }
        if (method_exists($user, 'setFirstname')) {
            $user->setFirstname('Client');
        }
        if (method_exists($user, 'setLastname')) {
            $user->setLastname('Test');
        }
        if (method_exists($user, 'setIsVerified')) {
            $user->setIsVerified(true);
        }
        $manager->persist($user);

        // 1. Grille tarifaire de livraison par poids (Weight)
        $weights = [
            ['kg' => 2.0, 'price' => 5.90],
            ['kg' => 5.0, 'price' => 9.90],
            ['kg' => 15.0, 'price' => 19.90],
            ['kg' => 30.0, 'price' => 29.90],
            ['kg' => 60.0, 'price' => 49.90],
        ];
        foreach ($weights as $w) {
            $weightEntity = new Weight();
            $weightEntity->setKg($w['kg']);
            $weightEntity->setPrice($w['price']);
            $manager->persist($weightEntity);
        }

        // 2. TVA standard
        $tvaStandard = new Tva('Taux standard', 21.0);
        $manager->persist($tvaStandard);

        $tvaReduite = new Tva('Taux réduit', 6.0);
        $manager->persist($tvaReduite);

        // 3. Catégories d'accessoires
        $categories = [];
        $catData = [
            'Casques & Protections' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
            'Antivols & Sécurité' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80',
            'Pneumatiques & Roues' => 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800&auto=format&fit=crop&q=80',
        ];

        foreach ($catData as $name => $img) {
            $cat = new CategoryAccessory();
            $cat->setName($name);
            $cat->setDescription('Tous les équipements certifiés pour la catégorie ' . $name);
            $cat->setIllustration($img);
            $manager->persist($cat);
            $categories[$name] = $cat;
        }

        // 4. Accessoires avec plusieurs photos
        $accessories = [];
        $accData = [
            [
                'name' => 'Casque Urbain LED Intégrée',
                'price' => 59.99,
                'promoPrice' => 49.99,
                'stock' => 15,
                'weight' => 0.5,
                'category' => $categories['Casques & Protections'],
                'images' => [
                    'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1576435728678-68d0fbf94e91?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'name' => 'Antivol U Haute Sécurité Acier Trempé',
                'price' => 45.00,
                'promoPrice' => null,
                'stock' => 20,
                'weight' => 1.2,
                'category' => $categories['Antivols & Sécurité'],
                'images' => [
                    'https://images.unsplash.com/photo-1558981803-35f5c9c9b4e1?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'name' => 'Pneu Plein Anti-crevaison 8.5 Pouces',
                'price' => 29.90,
                'promoPrice' => 24.90,
                'stock' => 30,
                'weight' => 0.8,
                'category' => $categories['Pneumatiques & Roues'],
                'images' => [
                    'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800&auto=format&fit=crop&q=80',
                ],
            ],
        ];

        foreach ($accData as $data) {
            $acc = new Accessory();
            $acc->setName($data['name']);
            $acc->setDescription('Accessoire haut de gamme conçu pour une durabilité maximale au quotidien.');
            $acc->setPrice($data['price']);
            $acc->setPromoPrice($data['promoPrice']);
            $acc->setStock($data['stock']);
            $acc->setWeight($data['weight']);
            $acc->setCategory($data['category']);
            $acc->setTva($tvaStandard);
            $acc->setIsBest(true);

            foreach ($data['images'] as $imgUrl) {
                $illustration = new Illustration();
                $illustration->setImage($imgUrl);
                $acc->addIllustration($illustration);
            }

            $manager->persist($acc);
            $accessories[] = $acc;
        }

        // 5. Trottinettes avec galerie complète et sections
        $trottData = [
            [
                'name' => 'MHvolt City Pro 350W',
                'nameShort' => 'City Pro',
                'price' => 499.00,
                'promoPrice' => 449.00,
                'stock' => 10,
                'weight' => 13.5,
                'images' => [
                    'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1511994298241-608e28f14fde?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1558981408-db0ecd8a1ee4?w=800&auto=format&fit=crop&q=80',
                ],
                'sections' => [
                    ['title' => 'Châssis en aluminium aéronautique', 'content' => 'Léger, ultra résistant et pliable en 3 secondes pour vous accompagner dans tous les transports.'],
                    ['title' => 'Batterie lithium haute endurance', 'content' => 'Cellules protégées par BMS intelligent offrant jusqu\'à 35 km d\'autonomie en conditions réelles.'],
                ],
            ],
            [
                'name' => 'MHvolt Ultra GT Dual Motor 1000W',
                'nameShort' => 'Ultra GT',
                'price' => 1099.00,
                'promoPrice' => null,
                'stock' => 5,
                'weight' => 24.0,
                'images' => [
                    'https://images.unsplash.com/photo-1558981408-db0ecd8a1ee4?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1511994298241-608e28f14fde?w=800&auto=format&fit=crop&q=80',
                ],
                'sections' => [
                    ['title' => 'Double motorisation 2 x 500W', 'content' => 'Accélération instantanée et franchissement de côtes jusqu\'à 30% sans perte de couple.'],
                    ['title' => 'Suspension hydraulique avant et arrière', 'content' => 'Absorption optimale des chocs sur pavés et chaussées dégradées pour un confort inégalé.'],
                ],
            ],
        ];

        foreach ($trottData as $data) {
            $trott = new Trottinette();
            $trott->setName($data['name']);
            $trott->setNameShort($data['nameShort']);
            $trott->setDescription('Trottinette électrique moderne alliant performance urbaine, autonomie et sécurité certifiée.');
            $trott->setDescriptionShort('Puissance et compacité pour vos trajets quotidiens.');
            $trott->setPrice($data['price']);
            $trott->setPromoPrice($data['promoPrice']);
            $trott->setStock($data['stock']);
            $trott->setWeight($data['weight']);
            $trott->setTva($tvaStandard);
            $trott->setIsBest(true);

            foreach ($data['images'] as $imgUrl) {
                $illustration = new Illustration();
                $illustration->setImage($imgUrl);
                $trott->addIllustration($illustration);
            }

            foreach ($data['sections'] as $idx => $sec) {
                $section = new TrottinetteDescriptionSection();
                $section->setTitle($sec['title']);
                $section->setContent($sec['content']);
                $section->setSectionOrder($idx + 1);
                $trott->addDescriptionSection($section);
            }

            foreach ($accessories as $acc) {
                $trott->addAccessory($acc);
            }

            $manager->persist($trott);
        }

        // 6. Code promotionnel
        $promo = new Promotion();
        $promo->setTitre('Offre de Bienvenue');
        $promo->setCode('WELCOME10');
        $promo->setDiscountType(Promotion::TYPE_PERCENTAGE);
        $promo->setDiscountPercent(10.0);
        $promo->setTargetType(Promotion::TARGET_ALL);
        $promo->setQuantity(200);
        $promo->setAutoApply(false);
        $manager->persist($promo);

        $manager->flush();
    }
}
