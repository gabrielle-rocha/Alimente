<?php
include '../../database/db_connect.php';

$email = $_POST['email'];
$senha = $_POST['password'];

// Verificar se o email e senha pertencem a um doador
$sql_doador = "SELECT * FROM cadastroDoador WHERE emailDoador = ? AND senhaDoador = ?";
$stmt_doador = $conn->prepare($sql_doador);
$stmt_doador->bind_param("ss", $email, $senha);
$stmt_doador->execute();
$result_doador = $stmt_doador->get_result();

if ($result_doador->num_rows > 0) {
    // Se for um doador, redireciona para a página do feed do doador
    header("Location: ../../pages/feed/index.html");
    exit();
}

// Verificar se o email e senha pertencem a uma ONG
$sql_ong = "SELECT * FROM Ongs WHERE emailOng = ? AND senhaOng = ?";
$stmt_ong = $conn->prepare($sql_ong);
$stmt_ong->bind_param("ss", $email, $senha);
$stmt_ong->execute();
$result_ong = $stmt_ong->get_result();

if ($result_ong->num_rows > 0) {
    // Se for uma ONG, redireciona para a página do feed da ONG
    header("Location: ../../pages/feed/index_ong.html");
    exit();
}

// Verificar se o email e senha pertencem a um administrador
$sql_admin = "SELECT * FROM administrador WHERE email = ? AND senha = ?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("ss", $email, $senha);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin->num_rows > 0) {
    // Se for um administrador, redireciona para a página de administração
    header("Location: ../../pages/adm/index.php");
    exit();
}

// Se não for encontrado um doador, ONG ou administrador com o email e senha fornecidos
echo "Email ou senha incorretos.";

$conn->close();
?>
