<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Cuisine.php";
    require_once __DIR__."/../src/Restaurant.php";
    require_once __DIR__."/../src/Review.php";

    $app = new Silex\Application();

    $server = 'mysql:host=localhost;dbname=best_restaurants';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
       'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    /*****INDEX PAGE*****/
    $app->get('/', function() use ($app) {
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });
    /*adding a cuisine*/
    $app->post("/cuisine", function() use ($app) {
        $cuisine = new Cuisine($_POST['type']);
        $cuisine->save();
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });
    /*deletes all restaurants and cuisines*/
    $app->delete("/delete_cuisines", function() use ($app) {
        Cuisine::deleteAll();
        Restaurant::deleteAll();
        return $app['twig']->render('index.html.twig');
    });
    /*****END OF INDEX PAGE*****/
    /*****CUISINE PAGE*****/
    /*display single cuisine and its restaurants*/
    $app->get("/cuisines/{id}", function($id) use ($app) {
        $cuisine = Cuisine::find($id);
        return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
    });
    /*add a restaurant in cuisines*/
    $app->post("/restaurant", function() use ($app) {
        $name = $_POST['name'];
        $cuisine_id = $_POST['cuisine_id'];
        $restaurant = new Restaurant($name, $cuisine_id);
        $restaurant->save();
        $cuisine = Cuisine::find($cuisine_id);
        return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
    });
    /*edit cuisine*/
    $app->patch("/cuisines/{id}", function($id) use ($app) {
        $type = $_POST['type'];
        $cuisine = Cuisine::find($id);
        $cuisine->update($type);
        return $app['twig']->render('cuisine.html.twig', array('cuisine' => $cuisine, 'restaurants' => $cuisine->getRestaurants()));
    });
    /*delete cuisines*/
    $app->delete("/cuisines/{id}", function($id) use ($app) {
        $cuisine = Cuisine::find($id);
        $cuisine->delete();
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });
    /*****END OF CUISINE PAGE*****/
    /*****SINGLE RESTAURANT PAGE*****/
    $app->get("/restaurant/{id}", function($id) use($app) {
        $restaurant = Restaurant::find($id);
        return $app['twig']->render('restaurant.html.twig', array('restaurants' => $restaurant));
    });

    /*edit restaurant by id*/
    $app->patch("/restaurants/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $restaurant = Restaurant::find($id);
        $restaurant->update($name);
        return $app['twig']->render('restaurant.html.twig', array('restaurants' => $restaurant));
    });
    /*delete restaurant by id*/
    $app->delete("/restaurants/{id}", function($id) use ($app) {
        $restaurant = Restaurant::find($id);
        $restaurant->delete();
        return $app['twig']->render('index.html.twig', array('cuisines' => Cuisine::getAll()));
    });
    /*****END OF SINGLE RESTAURANT PAGE*****/
    /*****TOTAL*****/
    /*display all*/
    $app->get("/total", function() use ($app){
        $cuisine = Cuisine::getAll();
        $restaurants = Restaurant::getAll();
        return $app['twig']->render('total.html.twig', array('cuisines'=> $cuisine, 'restaurants' => $restaurants));
    });

    /*****END OF TOTAL*****/
    /*****Review*****/
    $app->get("/review/{id}", function($id) use($app) {
        $restaurant = Restaurant::find($id);
        return $app['twig']->render('review.html.twig', array('restaurant' => $restaurant, 'reviews' => $restaurant->getReviews()));
    });

    /*add a restaurant in cuisines*/
    $app->post("/review", function() use ($app) {
        $rating = $_POST['rating'];
        $restaurant_id = $_POST['restaurant_id'];
        $review= new Review($rating, $restaurant_id);
        $review->save();
        $restaurant = Restaurant::find($restaurant_id);
        return $app['twig']->render('review.html.twig', array('restaurant' => $restaurant, 'reviews' => $restaurant->getReviews()));
    });

    /*edit restaurant by id*/
    $app->patch("/reviews/{id}", function($id) use ($app) {
        $rating = $_POST['rating'];
        $review = Review::find($id);
        $review->update($rating);
        return $app['twig']->render('total_reviews.html.twig', array('reviews' => Review::getAll(), 'restaurants' => Restaurant::getAll()));
    });
    /*delete restaurant by id*/
    $app->delete("/reviews/{id}", function($id) use ($app) {
        $review = Review::find($id);
        $review->delete();
        return $app['twig']->render('total_reviews.html.twig', array('reviews' => Review::getAll(),'restaurants' => Restaurant::getAll()));
    });

    /*****END OF Review*****/
    /*****TOTALReviewS*****/
    $app->get("/total_reviews", function() use ($app){
        $restaurant = Restaurant::getAll();
        $reviews = Review::getAll();
        return $app['twig']->render('total_reviews.html.twig', array('restaurants'=> $restaurant, 'reviews' => $reviews));
    });
    $app->get("/reviews/{id}", function($id) use($app) {
        $review = Review::find($id);
        return $app['twig']->render('total.html.twig', array('reviews' => $review));
    });
    /*****END OF TOTALReviewS*****/



    return $app;

 ?>
