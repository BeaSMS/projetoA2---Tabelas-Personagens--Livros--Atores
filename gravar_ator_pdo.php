<?php
header("Content-Type: application/json; charset=utf-8");


$dados = json_decode(file_get_contents("php://input"), true);


if (empty($dados["nome"])) {
    echo json_encode(["sucesso" => false, "mensagem" => "O nome do ator é obrigatório."]);
    exit;
}

// Captura e normaliza os dados
$nome       = trim($dados["nome"]);
$num_oscars = isset($dados["num_oscars"]) ? (int)$dados["num_oscars"] : 0;
$idade      = isset($dados["idade"]) ? (int)$dados["idade"] : null;


   //CONEXÃO COM O BANCO DE DADOS Atividade2

$host = "localhost";
$dbname = "Atividade2"; 
$usuario = "root";
$senha = "root";

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Comando SQL
    $sql = "INSERT INTO atores (nome, num_oscars, idade)
            VALUES (:nome, :num_oscars, :idade)";


    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":num_oscars", $num_oscars);
    $stmt->bindParam(":idade", $idade);

    $stmt->execute();

    echo json_encode(["sucesso" => true, "mensagem" => "Ator cadastrado com sucesso!"]);

} catch (PDOException $e) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro no banco de dados: " . $e->getMessage()
    ]);
}
?>