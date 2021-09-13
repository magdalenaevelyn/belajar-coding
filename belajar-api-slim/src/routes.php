<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get("/friends/", function (Request $request, Response $response){
        $sql = "SELECT * FROM teman";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/friends/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM teman WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/friends/search/", function (Request $request, Response $response, $args){
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM teman WHERE email LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR phone LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->post("/addFriends/", function (Request $request, Response $response){

        $new_friends = $request->getParsedBody();
    
        $sql = "INSERT INTO teman (email, nama, phone) VALUE (:email, :nama, :phone)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":email" => $new_friends["email"],
            ":nama" => $new_friends["nama"],
            ":phone" => $new_friends["phone"]
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->put("/updateFriends/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $new_friends = $request->getParsedBody();
        $sql = "UPDATE teman SET email=:email, nama=:nama, phone=:phone WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":id" => $id,
            ":email" => $new_friends["email"],
            ":nama" => $new_friends["nama"],
            ":phone" => $new_friends["phone"]
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->delete("/deleteFriends/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "DELETE FROM teman WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":id" => $id
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    
};
