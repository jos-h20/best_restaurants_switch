<?php

    /**
   * @backupGlobals disabled
   * @backupStaticAttributes disabled
   */

   require_once "src/Restaurant.php";
   require_once "src/Cuisine.php";

   $server = 'mysql:host=localhost;dbname=best_restaurants_test';
   $username = 'root';
   $password = 'root';
   $DB = new PDO($server, $username, $password);

   class RestaurantTest extends PHPUnit_Framework_TestCase
   {
       protected function tearDown()
       {
           Cuisine::deleteAll();
           Restaurant::deleteAll();
       }

       function test_getName()
       {
           $name = "Wangs Grill";
           $cuisine_id = "Chinese";
           $id = null;
           $test_restaurant = New Restaurant($name, $cuisine_id, $id);

           $result = $test_restaurant->getName();

           $this->assertEquals($name, $result);
       }

       function test_getId()
       {
           $name = "Wang's Grill";
           $cuisine_id = "Chinese";
           $id = 1;
           $test_restaurant = new Restaurant($name, $cuisine_id, $id);

           $result = $test_restaurant->getId();

           $this->assertEquals(true, is_numeric($result));
       }

       function test_save()
       {
           $type = "Chinese";
           $id = null;
           $new_cuisine = new Cuisine($type, $id);
           $new_cuisine->save();

           $name = "Wangs Grill";
           $cuisine_id = $new_cuisine->getId();
           $new_restaurant = new Restaurant($name, $cuisine_id, $id);
           $new_restaurant->save();

           $result = Restaurant::getAll();

           $this->assertEquals([$new_restaurant], $result);

       }

       //This test ensures stings containing apostrophes will be reflected correctly in the database.
       function test_adjustPunctuation()
       {
           $type = "Chinese";
           $id = null;
           $new_cuisine = new Cuisine($type, $id);
           $new_cuisine->save();

           $name = "Wang's Grill and Matt's Burgers";
           $cuisine_id = $new_cuisine->getId();
           $new_restaurant = new Restaurant($name, $cuisine_id, $id);
           $new_restaurant->save();

           $result = Restaurant::getAll();

           $this->assertEquals("Wang's Grill and Matt's Burgers", $result[0]->getName());
       }

       function test_getAll()
       {
           $type = "Chinese";
           $id = null;
           $new_cuisine = new Cuisine($type, $id);
           $new_cuisine->save();

           $name = "Wangs Grill";
           $cuisine_id = $new_cuisine->getId();
           $new_restaurant = new Restaurant($name, $cuisine_id, $id);
           $new_restaurant->save();

           $name2 = "Noodle House";
           $new_restaurant2 = new Restaurant($name2, $cuisine_id, $id);
           $new_restaurant2->save();

           $result = Restaurant::getAll();

           $this->assertEquals([$new_restaurant, $new_restaurant2], $result);
       }

       function test_delete_all()
       {
           $type = "Chinese";
           $id = null;
           $new_cuisine = new Cuisine($type, $id);
           $new_cuisine->save();

           $name = "Wangs Grill";
           $cuisine_id = $new_cuisine->getId();
           $new_restaurant = new Restaurant($name, $cuisine_id, $id);
           $new_restaurant->save();

           $name2 = "Noodle House";
           $new_restaurant2 = new Restaurant($name2, $cuisine_id, $id);
           $new_restaurant2->save();

           Restaurant::deleteAll();

           $result = Restaurant::getAll();
           $this->assertEquals([], $result);
       }

   }

?>
