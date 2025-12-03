<?php
header("Content-Type: application/json; charset=utf-8");


$dados = json_decode(file_get_contents("php://input"), true);


if (empty($dados["id_ator"]) || empty($dados["nome"])) {
    echo json_encode(["sucesso" => false, "mensagem" => "ID do Ator e Nome são obrigatórios para alteração."]);
    exit;
}


$id         = (int)$dados["id_ator"];
$nome       = trim($dados["nome"]);

$num_oscars = isset($dados["num_oscars"]) ? (int)$dados["num_oscars"] : 0; 
$idade      = isset($dados["idade"]) ? (int)$dados["idade"] : null; 

$host = "localhost";
$dbname = "Atividade2";
$usuario = "root";
$senha = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $sql = "UPDATE atores SET 
                nome = :nome, 
                num_oscars = :num_oscars, 
                idade = :idade 
            WHERE id_ator = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":num_oscars", $num_oscars);
    $stmt->bindParam(":idade", $idade);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["sucesso" => true, "mensagem" => "Ator ID {$id} alterado com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Nenhum dado alterado (ID não encontrado ou dados iguais)."]);
    }
    exit;

} catch (PDOException $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro no banco de dados: " . $e->getMessage()]);
    exit;
}