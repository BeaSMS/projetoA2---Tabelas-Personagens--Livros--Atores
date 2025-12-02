<?php
header("Content-Type: application/json; charset=utf-8");

// Lê dados JSON enviados pelo fetch()
$dados = json_decode(file_get_contents("php://input"), true);

// Verificação básica de campos obrigatórios
if (empty($dados["nome"]) || empty($dados["local"])) {
    echo json_encode(["sucesso" => false, "mensagem" => "Campos obrigatórios ausentes."]);
    exit;
}

// Captura e normaliza os dados
$nome       = trim($dados["nome"]);
$local      = trim($dados["local"]);
$idade      = isset($dados["idade"]) ? trim($dados["idade"]) : null;
$altura     = isset($dados["altura"]) ? trim($dados["altura"]) : null;


// CONEXÃO PDO COM O BANCO DE DADOS Atividade2

$host = "localhost";
$dbname = "Atividade2";
$usuario = "root";
$senha = "root";

try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    
    $sql = "INSERT INTO Personagens (nome, local, idade, altura)
            VALUES (:nome, :local, :idade, :altura)";

    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":local", $local);
    $stmt->bindParam(":idade", $idade);
    $stmt->bindParam(":altura", $altura);

    $stmt->execute();

    echo json_encode(["sucesso" => true, "mensagem" => "Personagem cadastrado com sucesso!"]);
    exit;

} catch (PDOException $e) {
    
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro no banco de dados: ".$e->getMessage()
    ]);
    exit;
}
?>
