<?php

header('Content-Type: application/json; charset=utf-8');

// Configuração da conexão
$host = 'localhost';
$dbname = 'atividade2'; 
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro na conexão: ' . $e->getMessage()]);
    exit;
}

/
// ROTAS

$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

switch ($acao) {
    case 'listar_personagens':
        consultarPersonagens($pdo);
        break;
    
    case 'listar_livros':
        consultarLivros($pdo);
        break;

    case 'listar_atores':
        consultarAtores($pdo);
        break;

    default:
        echo json_encode(['status' => 'erro', 'mensagem' => 'Ação inválida ou não informada. Use ?acao=...']);
        exit;
}


// FUNÇÕES DE CONSULTA


function consultarPersonagens($pdo) {
    
    $sql = "SELECT * FROM Personagens"; 
    try {
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'sucesso', 'dados' => $dados]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
    }
    exit;
}

function consultarLivros($pdo) {
    $sql = "SELECT * FROM livros"; 
    try {
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'sucesso', 'dados' => $dados]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
    }
    exit;
}

function consultarAtores($pdo) {
    $sql = "SELECT * FROM atores";
    try {
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'sucesso', 'dados' => $dados]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
    }
    exit;
}
?>