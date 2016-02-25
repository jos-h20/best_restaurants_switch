<?php

    /**
   * @backupGlobals disabled
   * @backupStaticAttributes disabled
   */

   require_once "src/Cuisine.php";

   $server = 'mysql:host=localhost;dbname=best_restaurants_test';
   $username = 'root';
   $password = 'root';
   $DB = new PDO($server, $username, $password);

   class CuisineTest extends PHPUnit_Framework_TestCase
   {
       protected function tearDown()
       {
           Cuisine::deleteAll();
       }

       function test_getType()
       {
           $type = "Chinese";
           $id = null;
           $test_Cuisine = new Cuisine($type, $id);

           $result = $test_Cuisine->getType();

           $this->assertEquals($type, $result);
       }

       function test_getId()
       {
           $type = "Chinese";
           $id = 1;
           $test_id = new Cuisine($type, $id);

           $result = $test_id->getId();

           $this->assertEquals(true, is_numeric($result));
       }

       function testSave()
       {
           $type = "Chinese";
           $id = null;
           $test_save = new Cuisine($type, $id);
           $test_save->save();

           $result = Cuisine::getAll();

           $this->assertEquals($test_save, $result[0]);
       }

       function test_Update()
       {
           $type = "China";
           $id = null;
           $test_cuisine = new Cuisine($type, $id);
           $test_cuisine ->save();

           $new_type = "Chinese";

           $test_cuisine->update($new_type);

           $this->assertEquals("Chinese", $test_cuisine->getType());
       }

       function test_getAll()
       {
           $type = "Chinese";
           $id = null;
           $test_get1 = new Cuisine($type, $id);
           $test_get1->save();

           $type2 = "Indian";
           $test_get2 = new Cuisine($type2, $id);
           $test_get2->save();

           $result = Cuisine::getAll();

           $this->assertEquals([$test_get1, $test_get2], $result);
       }

       function test_deleteAll()
       {
           $type = "Chinese";
           $id = null;
           $test_save = new Cuisine($type, $id);
           $test_save->save();

           Cuisine::deleteAll();
           $result = Cuisine::getAll();

           $this->assertEquals([], $result);
       }

       function test_find()
       {
           $type = "Chinese";
           $id = null;
           $cuisine1 = new Cuisine($type, $id);
           $cuisine1->save();

           $type2 = "Indian";
           $cuisine2 = new Cuisine($type2, $id);
           $cuisine2->save();

           $result = Cuisine::find($cuisine1->getId());

           $this->assertEquals($cuisine1, $result);
       }

       function test_getRestaurants()
       {
           $type = "Chinese";
           $id = null;
           $test_cuisine = new Cuisine($type, $id);
           $test_cuisine->save();

           $test_cuisine_id = $test_cuisine->getId();

           $name = "Noodle House";
           $test_restaurant = new Restaurant($name, $test_cuisine_id, $id);
           $test_restaurant->save();

           $name2 = "China House";
           $test_restaurant2 = new Restaurant($name2, $test_cuisine_id, $id);
           $test_restaurant2->save();

           $result = $test_cuisine->getRestaurants();

           $this->assertEquals([$test_restaurant, $test_restaurant2], $result);
       }

   }
 ?>
