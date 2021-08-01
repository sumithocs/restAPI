<?php

class Post
{
    private $conn;
    private $table = "posts";

    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "Select c.name as category_name,
        p.id,
        p.category_id,
        p.title,
        p.body,
        p.author,
        p.created_at
        FROM " .
            $this->table .
            " as p
        LEFT JOIN
        categories c ON p.category_id = c.id
        ORDER BY
        p.created_at DESC";

        $statement = $this->conn->prepare($query);
        $statement->execute();
        return $statement;
    }

    public function read_single()
    {
        $query = "Select c.name as category_name,
        p.id,
        p.category_id,
        p.title,
        p.body,
        p.author,
        p.created_at
        FROM " .
            $this->table .
            " as p
        LEFT JOIN
        categories c ON p.category_id = c.id
        WHERE
        p.id = ?
        LIMIT 0,1";

        //prepare the statement
        $statement = $this->conn->prepare($query);
        // Bind the ID
        $statement->bindParam(1, $this->id);
        $statement->execute();
        return $statement;
    }

    public function create()
    {
        // $query = "INSERT INTO" .
        //     $this->table .
        //     "(category_id, title, body, author) 
        //     VALUES
        //     (" .
        //     $this->category_id . "," .
        //     $this->title . "," .
        //     $this->body . "," .
        //     $this->author .
        //     ")";

        $query = "INSERT INTO " .
            $this->table .
            " SET 
            title = :title,
            body = :body,
            author = :author,
            category_id = :category_id";

        // prepare
        $statement = $this->conn->prepare($query);

        // clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $statement->bindParam(':title', $this->title);
        $statement->bindParam(':body', $this->body);
        $statement->bindParam(':author', $this->author);
        $statement->bindParam(':category_id', $this->category_id);

        //var_dump($statement);

        if ($statement->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $statement->error);
            return false;
        }
    }

    public function update()
    {
        $query = "UPDATE " .
            $this->table .
            " SET 
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id 
             WHERE id = :id";

        // prepare
        $statement = $this->conn->prepare($query);

        // clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $statement->bindParam(':title', $this->title);
        $statement->bindParam(':body', $this->body);
        $statement->bindParam(':author', $this->author);
        $statement->bindParam(':category_id', $this->category_id);
        $statement->bindParam(':id', $this->id);

        //var_dump($statement);

        if ($statement->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $statement->error);
            return false;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM " .
            $this->table .
            " WHERE id = :id";

        // prepare
        $statement = $this->conn->prepare($query);

        //clean
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind
        $statement->bindParam(':id', $this->id);

        //var_dump($statement);

        if ($statement->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $statement->error);
            return false;
        }
    }
}
