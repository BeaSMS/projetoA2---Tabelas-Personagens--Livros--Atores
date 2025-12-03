<?php
header("Content-Type: application/json; charset=utf-8");

$dados = json_decode(file_get_contents("php://input"), true);


if (empty($dados["id_livro"])) {
    echo json_encode(["sucesso" => false, "mensagem" => "ID do Livro ausente."]);
    exit;
}

$id = (int)$dados["id_livro"];

/*CONEXÃO PDO COM O BANCO DE DADOS Atividade2 */
$host = "localhost";
$dbname = "Atividade2";
$usuario = "root";
$senha = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);


    $sql = "DELETE FROM LIVROS WHERE id_livro = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["sucesso" => true, "mensagem" => "Livro ID {$id} removido com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Nenhum registro encontrado com o ID {$id}."]);
    }
    exit;

} catch (PDOException $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro no banco de dados: " . $e->getMessage()]);
    exit;
}