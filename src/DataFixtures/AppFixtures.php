<?php

namespace App\DataFixtures;

use App\Entity\BoxType;
use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\PaperType;
use App\Entity\Product;
use App\Entity\Provider;
use App\Entity\QualityPreset;
use App\Entity\Release;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. Create Providers
        $provider = new Provider("Công ty cổ phần Vinh Phát", "3300859528", "0935831369", "ctyvinhphat.jsc@gmail.com", "Khu công nghiệp Phú Bài, Phường Phú Bài, Thành phố Huế, Việt Nam");
        $manager->persist($provider);

        $provider2 = new Provider("Công ty cổ phần Bắc Trung Bộ", "3200133771", "0903516159", "bactrungboquangtri@gmail.com", "Km4, tỉnh lộ 571 cụm Công nghiệp Làng Nghề Bắc Hồ Xá, xã Vĩnh Linh, tỉnh Quảng Trị, Việt Nam");
        $manager->persist($provider2);

        $provider3 = new Provider("Công ty cổ phần Giấy Thành Công II", "0400364587", "(+84)2363796996", "ktgiaythanhcong@gmail.com", "Đường số 6, Khu công nghiệp Hòa Khánh, Phường Liên Chiểu ,Thành phố Đà Nẵng, Việt Nam");
        $manager->persist($provider3);

        // 2. Create PaperTypes
        $paperType = new PaperType("Vinh phát xeo", 155, 180, 1027, $provider);
        $manager->persist($paperType);

        $paperType2 = new PaperType("Vinh phát gia keo", 155, 200, 1027, $provider);
        $manager->persist($paperType2);

        $paperType3 = new PaperType("Bắc Trung Bộ xeo", 155, 180, 21264, $provider2);
        $manager->persist($paperType3);

        $paperType4 = new PaperType("Thành Công gia keo", 165, 140, 2000, $provider3);
        $manager->persist($paperType4);

        // 3. Create Customers
        $customer = new Customer("Công ty cổ phần Kim Sora", "0401698328", "(+84)2363719172", "huept@kimsora.com.vn", "Lô 27A1, Đường Nguyễn Sinh Sắc, Phường Hòa Khánh, Thành phố Đà Nẵng, Việt Nam");
        $manager->persist($customer);

        $customer2 = new Customer("Công ty cổ phần HIFILL", "0400594693", "(+84)2363736577", "thonghv@hifill.com.vn", "Lô G, Đường số 10, Khu công nghiệp Hòa Khánh, Quận Liên Chiểu, Thành phố Đà Nẵng, Việt Nam");
        $manager->persist($customer2);

        $customer3 = new Customer("Công ty cổ phần Vinatex Đà Nẵng", "0400410498", "0511863757", "mailt@vinatexdn.com.vn", "25 Đường Trần Quý Cáp, Thành phố Đà Nẵng, Việt Nam");
        $manager->persist($customer3);

        // 4. Create QualityPresets
        $qualityPreset = new QualityPreset("Thùng Kim Sora 5 lớp", $customer);
        $manager->persist($qualityPreset);

        $qualityPreset2 = new QualityPreset("Thùng HIFILL 3 lớp", $customer2);
        $manager->persist($qualityPreset2);

        $qualityPreset3 = new QualityPreset("Thùng HIFILL 5 lớp", $customer2);
        $manager->persist($qualityPreset3);

        $qualityPreset4 = new QualityPreset("Thùng Vinatex 5 lớp", $customer3);
        $manager->persist($qualityPreset4);

        // 5. Create BoxTypes
        $boxType = new BoxType("Thùng xếp", "W+H, (L+W)*2+30");
        $manager->persist($boxType);

        $boxType2 = new BoxType("Thùng nắp chồm", "W+H+150, (L+W)*2+30");
        $manager->persist($boxType2);

        $boxType3 = new BoxType("Thùng nắp chồm (2 tấm/thùng)", "W+H+150, L+W+30");
        $manager->persist($boxType3);

        $boxType4 = new BoxType("Thùng NCD", "W+H*2, (L+H)*2+30");
        $manager->persist($boxType4);

        $boxType5 = new BoxType("Thùng âm dương", "W+H*2, L+H*2");
        $manager->persist($boxType5);

        // 6. Create Orders
        $order = new Order($customer);
        $order->setManufacturingNumber("01/04"); // Manual sample for fixtures!
        $manager->persist($order);

        $order2 = new Order($customer2);
        $order2->setManufacturingNumber("02/04");
        $manager->persist($order2);

        $order3 = new Order($customer3);
        $order3->setManufacturingNumber("03/04");
        $manager->persist($order3);

        // 7. Create Products
        $product = new Product("Thùng Kim Sora 5 lớp", "L x W x H", 100, $order, $boxType, $qualityPreset);
        $manager->persist($product);

        $product2 = new Product("Thùng HIFILL 3 lớp", "L x W x H", 100, $order2, $boxType2, $qualityPreset2);
        $manager->persist($product2);

        $product3 = new Product("Thùng HIFILL 5 lớp", "L x W x H", 200, $order2, $boxType3, $qualityPreset3);
        $manager->persist($product3);

        $product4 = new Product("Thùng Vinatex 5 lớp", "L x W x H", 150, $order3, $boxType4, $qualityPreset4);
        $manager->persist($product4);

        // 8. Create Releases
        $release = new Release(new DateTimeImmutable("2026-04-01"), 100, $product);
        $manager->persist($release);

        $release2 = new Release(new DateTimeImmutable("2026-04-02"), 50, $product2);
        $manager->persist($release2);

        $release3 = new Release(new DateTimeImmutable("2026-04-03"), 100, $product3);
        $manager->persist($release3);

        $release4 = new Release(new DateTimeImmutable("2026-04-04"), 150, $product4);
        $manager->persist($release4);

        $manager->flush();
    }
}
