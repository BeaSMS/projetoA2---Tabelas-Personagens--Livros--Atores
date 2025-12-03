<?php
header("Content-Type: application/json; charset=utf-8");

$dados = json_decode(file_get_contents("php://input"), true);

if (empty($dados["id_personagem"]) || empty($dados["nome"])) {
    echo json_encode(["sucesso" => false, "mensagem" => "ID e Nome são obrigatórios para alteração."]);
    exit;
}

$id = (int)$dados["id_personagem"]; 
$nome = trim($dados["nome"]);
$local = trim($dados["local"]);
$idade = isset($dados["idade"]) ? (int)$dados["idade"] : null; 
$altura = isset($dados["altura"]) ? (float)str_replace(',', '.', $dados["altura"]) : null; 

$host = "localhost";
$dbname = "Atividade2";
$usuario = "root";
$senha = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);


    $sql = "UPDATE Personagens SET 
                nome = :nome, 
                local = :local, 
                idade = :idade, 
                altura = :altura 
            WHERE id_personagem = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":local", $local);
    $stmt->bindParam(":idade", $idade);
    $stmt->bindParam(":altura", $altura);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["sucesso" => true, "mensagem" => "Personagem ID {$id} alterado com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Nenhum dado alterado (ID não encontrado ou dados iguais)."]);
    }
    exit;

} catch (PDOException $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro no banco de dados: " . $e->getMessage()]);
    exit;
}