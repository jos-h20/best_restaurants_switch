<?php

    /**
   * @backupGlobals disabled
   * @backupStaticAttributes disabled
   */
   require_once "src/Review.php";
   require_once "src/Restaurant.php";
   require_once "src/Cuisine.php";

   $server = 'mysql:host=localhost;dbname=best_restaurants_test';
   $username = 'root';
   $password = 'root';
   $DB = new PDO($server, $username, $password);

   class ReviewTest extends PHPUnit_Framework_TestCase
   {
       protected function tearDown()
       {
           Cuisine::deleteAll();
           Restaurant::deleteAll();
           Review::deleteAll();
       }

       function test_getRating()
       {
           $rating = "Wangs Grill";
           $restaurant_id = "Chinese";
           $id = null;
           $test_review = New Review($rating, $restaurant_id, $id);

           $result = $test_review->getRating();

           $this->assertEquals($rating, $result);
       }

       function test_getId()
       {
           $rating = "Wang's Grill";
           $restaurant_id = "Chinese";
           $id = 1;
           $test_review = new Review($rating, $restaurant_id, $id);

           $result = $test_review->getId();

           $this->assertEquals(true, is_numeric($result));
       }

       function test_save()
       {
           $name = "Chinese";
           $id = null;
           $cuisine_id = 1;
           $new_restaurant = new Restaurant($name, $cuisine_id, $id);
           $new_restaurant->save();

           $rating = "Wangs Grill";
           $restaurant_id = $new_restaurant->getId();
           $new_review = new Review($rating, $restaurant_id, $id);
           $new_review->save();

           $result = Review::getAll();

           $this->assertEquals([$new_review], $result);

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
           $name = "Chinese";
           $id = null;
           $cuisine_id = 1;
           $new_restaurant = new Restaurant($name, $cuisine_id, $id);
           $new_restaurant->save();

           $rating = "Wangs Grill";
           $restaurant_id = $new_restaurant->getId();
           $new_review = new Review($rating, $restaurant_id, $id);
           $new_review->save();

           $rating2 = "Noodle House";
           $new_review2 = new Review($rating2, $restaurant_id, $id);
           $new_review2->save();

           $result = Review::getAll();

           $this->assertEquals([$new_review, $new_review2], $result);
       }

       function test_delete_all()
       {
           $name = "Chinese";
           $id = null;
           $cuisine_id = 1;
           $new_restaurant = new Restaurant($name, $cuisine_id, $id);
           $new_restaurant->save();

           $rating = "Wangs Grill";
           $restaurant_id = $new_restaurant->getId();
           $new_review = new Review($rating, $restaurant_id, $id);
           $new_review->save();

           $rating2 = "Noodle House";
           $new_review2 = new Review($rating2, $restaurant_id, $id);
           $new_review2->save();

           Review::deleteAll();

           $result = Review::getAll();
           $this->assertEquals([], $result);
       }

   }

?>
