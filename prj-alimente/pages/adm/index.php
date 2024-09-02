<?php
include '../../database/db_connect.php';

// Verifica se a conexão foi estabelecida
if (!$conn) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// Processa a inserção de um novo item de doação, se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $categoriaItem = $_POST['categoriaItem'];
    $descItem = $_POST['descItem'];

    // Prepara e executa a inserção
    $stmt = $conn->prepare("INSERT INTO itemDoacao (categoriaItem, descItem) VALUES (?, ?)");
    $stmt->bind_param("ss", $categoriaItem, $descItem);

    if (!$stmt->execute()) {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

// Obtém contagens
$ong_count_query = "SELECT COUNT(*) AS total FROM ongs";
$ong_count_result = $conn->query($ong_count_query);
$ong_count = $ong_count_result->fetch_assoc()['total'];

$doador_count_query = "SELECT COUNT(*) AS total FROM cadastroDoador";
$doador_count_result = $conn->query($doador_count_query);
$doador_count = $doador_count_result->fetch_assoc()['total'];

$publicacoes_count = 0; // Valor fixo
$contas_banidas_count = 0; // Valor fixo

// Array para armazenar os dados
$usuariosCadastrados = array_fill(0, 12, 0);
$ongs = array_fill(0, 12, 0);

// Consulta para contar os doadores cadastrados por mês
$queryDoadores = "
    SELECT MONTH(dataCadastroDoador) AS mes, COUNT(*) AS total
    FROM cadastrodoador
    WHERE YEAR(dataCadastroDoador) = YEAR(CURDATE())
    GROUP BY MONTH(dataCadastroDoador);
";

$resultDoadores = mysqli_query($conn, $queryDoadores);

while($row = mysqli_fetch_assoc($resultDoadores)) {
    $usuariosCadastrados[$row['mes'] - 1] = $row['total'];
}

// Consulta para contar as ONGs cadastradas por mês
$queryOngs = "
    SELECT MONTH(dataCadastroOng) AS mes, COUNT(*) AS total
    FROM ongs
    WHERE YEAR(dataCadastroOng) = YEAR(CURDATE())
    GROUP BY MONTH(dataCadastroOng);
";

$resultOngs = mysqli_query($conn, $queryOngs);

while($row = mysqli_fetch_assoc($resultOngs)) {
    $ongs[$row['mes'] - 1] = $row['total'];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" href="img/icon.png" type="image/icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Mês', 'Doadores', 'Ongs'],
                ['Janeiro', <?= $usuariosCadastrados[0]?>, <?= $ongs[0]?> ],
                ['Fevereiro', <?= $usuariosCadastrados[1]?>, <?= $ongs[1]?> ],
                ['Março', <?= $usuariosCadastrados[2]?>, <?= $ongs[2]?> ],
                ['Abril', <?= $usuariosCadastrados[3]?>, <?= $ongs[3]?> ],
                ['Maio', <?= $usuariosCadastrados[4]?>, <?= $ongs[4]?> ],
                ['Junho', <?= $usuariosCadastrados[5]?>, <?= $ongs[5]?> ],
                ['Julho', <?= $usuariosCadastrados[6]?>, <?= $ongs[6]?> ],
                ['Agosto', <?= $usuariosCadastrados[7]?>, <?= $ongs[7]?> ],
                ['Setembro', <?= $usuariosCadastrados[8]?>, <?= $ongs[8]?> ],
                ['Outubro', <?= $usuariosCadastrados[9]?>, <?= $ongs[9]?> ],
                ['Novembro', <?= $usuariosCadastrados[10]?>, <?= $ongs[10]?> ],
                ['Dezembro', <?= $usuariosCadastrados[11]?>, <?= $ongs[11]?> ]
            ]);


            var options = {
            title: 'Performance 2024',
            curveType: 'function',
            legend: { position: 'bottom' },
            chartArea: { width: '95%'},
            width: '80%',
            height: 900,
            hAxis: {
                textStyle: { color: '#333' }
            },
            vAxis: {
                textStyle: { color: '#333' }
            },
            width: '100%',
            height: '100%'
};


            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="img/alimente.png" alt="Logo">
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Pesquisar...">
            <i class="fas fa-search search-icon"></i>
        </div>
        <div class="nav-items">
            <div class="nav-item" id="notifications-tab"><i class="fas fa-bell"></i> Notificações</div>
            <div class="nav-item" id="history-tab"><i class="fas fa-history"></i> Histórico</div>
            <div class="nav-item" id="filters-tab"><i class="fas fa-filter"></i> Filtros</div>
            <a href="contas.php"><div class="nav-item" id="filters-tab"><i class="fas fa-user"></i> Contas</div></a>
        </div>
    </nav>
    <div class="dashboard">
        <div class="card-container">
            <div class="card">ONGs cadastradas na plataforma: <?php echo $ong_count; ?></div>
            <div class="card">Usuários na plataforma: <?php echo $doador_count; ?></div>
            <div class="card">Publicações na plataforma: <?php echo $publicacoes_count; ?></div>
            <div class="card">Contas banidas: <?php echo $contas_banidas_count; ?></div>
        </div>

        <!-- Gráfico Novo -->
        <div class="container">
            <div class="card">
                <div id="curve_chart"></div>
            </div>
        </div>
        <!-- Fim do Gráfico Novo -->
    </div>
    <div id="overlay" class="overlay"></div>
    <div class="mini-screens">
        <div id="notifications" class="mini-screen">
            <span class="close-btn">&times;</span>
            <h3>Notificações</h3>
            <p>Usuário cadastrado.</p> 
        </div>
        <div id="history" class="mini-screen">
            <span class="close-btn">&times;</span>
            <h3>Histórico</h3>
            <button>Visualizar</button>
        </div>
        <div id="filters" class="mini-screen">
            <span class="close-btn">&times;</span>
            <h3>Filtros</h3>
            <form method="POST" action="">
                <input type="text" name="categoriaItem" placeholder="Categoria do Item" required>
                <input type="text" name="descItem" placeholder="Descrição do Item" required>
                <button type="submit" name="add_item">Adicionar Item</button>
            </form>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
