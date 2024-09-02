<?php
include '../../database/db_connect.php';

// Coletando dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$celular = $_POST['celular'];
$data_nasc = $_POST['data_nasc'];

// Inserindo telefone
$stmt_telefone = $conn->prepare("INSERT INTO telefoneDoador (telefoneDoador) VALUES (?)");
$stmt_telefone->bind_param("s", $celular);
$stmt_telefone->execute();
$codTelefoneDoador = $stmt_telefone->insert_id;
$stmt_telefone->close();

// Inserindo doador
$stmt_doador = $conn->prepare("INSERT INTO cadastroDoador (nomeDoador, emailDoador, senhaDoador, codTelefoneDoador, dataNasc) VALUES (?, ?, ?, ?, ?)");
$stmt_doador->bind_param("sssss", $nome, $email, $senha, $codTelefoneDoador, $data_nasc);

if ($stmt_doador->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro: " . $stmt_doador->error;
}

$stmt_doador->close();
$conn->close();
?>
