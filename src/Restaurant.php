<?php

    class Restaurant {
        private $name;
        private $cuisine_id;
        private $id;

        function __construct($name, $cuisine_id, $id = null)
        {
            $this->name = $name;
            $this->cuisine_id = $cuisine_id;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function setCuisineId($new_cuisine_id)
        {
            $this->cuisine_id = $new_cuisine_id;
        }

        function getCuisineId()
        {
            return $this->cuisine_id;
        }

        function getId()
        {
            return $this->id;
        }

        function getReviews()
        {
            $reviews = array();
            $returned_reviews = $GLOBALS['DB']->query("SELECT * FROM reviews  WHERE restaurant_id = {$this->getId()}");
            foreach($returned_reviews as $review) {
                $rating = $review['rating'];
                $restaurant_id = $review['restaurant_id'];
                $id = $review['id'];
                $new_review = new Review($rating, $restaurant_id, $id);
                array_push($reviews, $new_review);
            }

            return $reviews;
        }

        function save()
        {
            $this->setName($this->adjustPunctuation($this->getName()));
            $GLOBALS['DB']->exec("INSERT INTO restaurants (name, cuisine_id) VALUES('{$this->getName()}', {$this->getCuisineId()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        //adjustPunctuation funciton adds a backslash before any apostrophes in the restaurant name to prevent the save method from closing the name string too soon. It is necessary for tests to pass and for site to be functional.
        function adjustPunctuation($name)
        {
            $search = "/(\')/";
            $replace = "\'";
            $clean_name = preg_replace($search, $replace, $name);
            return $clean_name;
        }

        static function getAll()
        {
            $returned_restaurants = $GLOBALS['DB']->query("SELECT * FROM restaurants;");
            $restaurants = array();
            foreach ($returned_restaurants as $restaurant)
            {
                $name = $restaurant['name'];
                $cuisine_id = $restaurant['cuisine_id'];
                $id = $restaurant['id'];
                $new_restaurant = new Restaurant($name, $cuisine_id, $id);
                array_push($restaurants, $new_restaurant);
            }
            return $restaurants;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM restaurants;");
        }

        static function find($search_id)
        {
            $found_restaurant = null;
            $restaurants = Restaurant::getAll();
            foreach ($restaurants as $restaurant){
                $restaurant_id = $restaurant->getId();
                if ($restaurant_id == $search_id){
                    $found_restaurant = $restaurant;
                }
            }
            return $found_restaurant;
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE restaurants SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }
        function delete()
       {
           $GLOBALS['DB']->exec("DELETE FROM restaurants WHERE id = {$this->getId()};");
       }
    }




?>
