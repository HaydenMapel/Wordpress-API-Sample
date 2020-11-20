<?php
namespace Rigo\Controller;

use Rigo\Types\Game;
use \WP_REST_Request;

class GameController{
    
    public function getHomeData(){
        return [
            'name' => 'Rigoberto'
        ];
    }
    
    public function getSingle(WP_REST_Request $request){
        $id = (string) $request['id'];
        $query = Game::get($id);
        $game = [
                "ID" => $query->ID,
                "post_title" => $query->post_title,
                "Company" => get_field('company', $query->ID),
                "Genre" => get_field('genre', $query->ID),
                "Description" => get_field('description', $query->ID)
            ];
        return $game;

        // return Game::get($id);
    }
    
    // public function getAllGame(WP_REST_Request $request){
        
    //     //get all posts
    //     $query = Game::all();
    //     return $query;//Always return an Array type
    // }

    public function createGame(WP_REST_Request $request){

        $body = json_decode($request->get_body());
        
        $id = Game::create([
            "post_title"    => $body->title,
            ]);  
        update_field( 'Description', $body->description, $id );
        update_field( 'Company', $body->company, $id );
        update_field( 'Genre', $body->genre, $id );
        return $id;
    }

    
    public function deleteGame(WP_REST_Request $request){
        $id = (string) $request['id'];
        // result is true on success, false on failure
        $result = Game::delete($id);
        return $result;
    }
        
        
    /**
     * Using Custom Post types to add new properties to the course
     */
    public function getAllGames (WP_REST_Request $request){
        
        $games = [];
        $query = Game::all([ 'status' => 'draft' ]);
        foreach($query->posts as $game){
            $games[] = array(
                "ID" => $game->ID,
                "post_title" => $game->post_title,
                "Company" => get_field('company', $game->ID),
                "Genre" => get_field('genre', $game->ID),
                "Description" => get_field('description', $game->ID)
            );
        }
        return $games;
    }
}
?>