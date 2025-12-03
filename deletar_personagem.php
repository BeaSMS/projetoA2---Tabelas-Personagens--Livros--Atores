<?php
header("Content-Type: application/json; charset=utf-8");


$dados = json_decode(file_get_contents("php://input"), true);


if (empty($dados["id_personagem"])) {
    echo json_encode(["sucesso" => false, "mensagem" => "ID do Personagem ausente."]);
    exit;
}

$id = (int)$dados["id_personagem"];


$host = "localhost";
$dbname = "Atividade2";
$usuario = "root";
$senha = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

 
    $sql = "DELETE FROM Personagens WHERE id_personagem = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["sucesso" => true, "mensagem" => "Personagem ID {$id} removido com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Nenhum registro encontrado com o ID {$id}."]);
    }
    exit;

} catch (PDOException $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro no banco de dados: " . $e->getMessage()]);
    exit;
}