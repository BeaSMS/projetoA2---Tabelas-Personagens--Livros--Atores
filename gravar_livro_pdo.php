<?php
header("Content-Type: application/json; charset=utf-8");

$dados = json_decode(file_get_contents("php://input"), true);

if (empty($dados["nome"]) || empty($dados["genero"])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Campos obrigatórios (Nome e Gênero) ausentes."]);
    exit;
}

$nome      = trim($dados["nome"]);
$descricao = isset($dados["descricao"]) ? trim($dados["descricao"]) : "";
$autor     = isset($dados["autor"]) ? trim($dados["autor"]) : "";
$genero    = trim($dados["genero"]);

$host = "localhost";
$dbname = "Atividade2"; 
$usuario = "root";
$senha = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $sql = "INSERT INTO livros (nome, descricao, autor, genero)
            VALUES (:nome, :descricao, :autor, :genero)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":descricao", $descricao);
    $stmt->bindParam(":autor", $autor);
    $stmt->bindParam(":genero", $genero);

    $stmt->execute();

    echo json_encode(["sucesso" => true, "mensagem" => "Livro cadastrado com sucesso!"]);

} catch (PDOException $e) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro no banco de dados: " . $e->getMessage()
    ]);
}
?>