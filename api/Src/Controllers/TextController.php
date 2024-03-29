<?php


namespace Src\Controllers;


use Src\App\Database\Connection;

class TextController
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $title = $data['title'];
        $text = $data['text'];
        $public = $data['public'];

        $sql = "INSERT INTO texts(`id_user`, `title`, `text`, `public`) VALUES (:id_user, :title, :text, :public)";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':title' => $title,
            ':text' => $text,
            ':public' => $public
        ]);

        return [
            'id_user' => $id_user,
            'id_text' => $this->connection->lastInsertId(),
            'title' => $title,
            'text' => $text,
            'public' => $public
        ];
    }

    public function createAdmin(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $title = $data['title'];
        $text = $data['text'];
        $public = $data['public'];

        $sql = "INSERT INTO texts(`id_user`, `title`, `text`, `public`) VALUES (:id_user, :title, :text, :public)";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':title' => $title,
            ':text' => $text,
            ':public' => $public
        ]);
    }

    public function getAll(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];

        $sql = "SELECT id_user, id_text, title, public, SUBSTRING(text, 1, 1000) as text, date 
                    FROM texts 
                        WHERE id_user = :id_user AND public = 0
                            ORDER BY id_text DESC";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user
        ]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPublic(): array
    {
        $sql = "SELECT id_user, id_text, title, public, SUBSTRING(text, 1, 1000) as text, date 
                    FROM texts 
                        WHERE public = 1
                            ORDER BY id_text DESC";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function checkPublic(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_text = $data['id_text'];

        $sql = "SELECT public
                    FROM texts 
                        WHERE id_text = :id_text";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_text' => $id_text
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function getById(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_text = $data['id_text'];

        $sql = "SELECT id_user, id_text, title, text, date FROM texts WHERE id_user = :id_user AND id_text = :id_text";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':id_text' => $id_text
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByIdWithoutUser(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_text = $data['id_text'];

        $sql = "SELECT id_user, id_text, title, text, date FROM texts WHERE public = '1' AND id_text = :id_text";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_text' => $id_text
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByIdPublic(): array
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_text = $data['id_text'];

        $sql = "SELECT id_user, id_text, title, text, date FROM texts WHERE id_text = :id_text";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_text' => $id_text
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_text = $data['id_text'];
        $text = $data['text'];

        $sql = "UPDATE texts SET text = :text WHERE id_user = :id_user AND id_text = :id_text";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':text' => $text,
            ':id_user' => $id_user,
            ':id_text' => $id_text
        ]);
    }

    public function delete(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $id_user = $data['id_user'];
        $id_text = $data['id_text'];

        $sql = "DELETE FROM texts WHERE id_user = :id_user AND id_text = :id_text";
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id_user' => $id_user,
            ':id_text' => $id_text
        ]);
    }
}