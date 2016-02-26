<?php

    class Review {
        private $rating;
        private $restaurant_id;
        private $id;

        function __construct($rating, $restaurant_id, $id = null)
        {
            $this->rating = $rating;
            $this->restaurant_id = $restaurant_id;
            $this->id = $id;
        }

        function setRating($new_rating)
        {
            $this->rating = $new_rating;
        }

        function getRating()
        {
            return $this->rating;
        }

        function setRestaurantId($new_restaurant_id)
        {
            $this->restaurant_id = $new_restaurant_id;
        }

        function getRestaurantId()
        {
            return $this->restaurant_id;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $this->setRating($this->adjustPunctuation($this->getRating()));
            $GLOBALS['DB']->exec("INSERT INTO reviews (rating, restaurant_id) VALUES('{$this->getRating()}', {$this->getRestaurantId()});");
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
            $returned_reviews = $GLOBALS['DB']->query("SELECT * FROM reviews;");
            $reviews = array();
            foreach ($returned_reviews as $review)
            {
                $rating = $review['rating'];
                $restaurant_id = $review['restaurant_id'];
                $id = $review['id'];
                $new_review = new Review($rating, $restaurant_id, $id);
                array_push($reviews, $new_review);
            }
            return $reviews;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM reviews;");
        }

        static function find($search_id)
        {
            $found_review = null;
            $reviews = Review::getAll();
            foreach ($reviews as $review){
                $review_id = $review->getId();
                if ($review_id == $search_id){
                    $found_review = $review;
                }
            }
            return $found_review;
        }

        function update($new_rating)
        {
            $GLOBALS['DB']->exec("UPDATE reviews SET rating = '{$new_rating}' WHERE id = {$this->getId()};");
            $this->setRating($new_rating);
        }
        function delete()
       {
           $GLOBALS['DB']->exec("DELETE FROM reviews WHERE id = {$this->getId()};");
       }
    }




?>
