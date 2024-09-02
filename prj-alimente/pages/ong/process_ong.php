<?php
include '../../database/db_connect.php';

// Coletando dados do formulário
$nome_responsavel = $_POST['responsavel'];
$razao_social = $_POST['razao-social'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$bairro = $_POST['bairro'];
$forma_doacao = $_POST['doacao'];

// Inserindo dados na tabela Ongs
$stmt = $conn->prepare("INSERT INTO Ongs (nomeResponsavel, razaoSocialOng, emailOng, senhaOng, cidadeOng, estadoOng, bairroOng, formaDoacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $nome_responsavel, $razao_social, $email, $senha, $cidade, $estado, $bairro, $forma_doacao);

if ($stmt->execute()) {
    echo "Cadastro da ONG realizado com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
