// Faz a requisição para o PHP que retorna os dados do gráfico
fetch('../js/dados_grafico.php')
    .then(response => response.json())
    .then(data => {
        const chartData = {
            labels: ['Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro'],
            datasets: [{
                label: 'Número de Cadastros',
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    })
    .catch(error => console.error('Erro ao carregar os dados do gráfico:', error));



// Adiciona event listeners aos itens da navbar
document.getElementById('notifications-tab').addEventListener('click', function() {
    toggleMiniScreen('notifications');
});

document.getElementById('history-tab').addEventListener('click', function() {
    toggleMiniScreen('history');
});

document.getElementById('filters-tab').addEventListener('click', function() {
    toggleMiniScreen('filters');
});

// Função para exibir ou ocultar a mini tela correspondente
function toggleMiniScreen(screenId) {
    hideAllMiniScreens(); // Esconde todas as mini telas

    // Mostra o overlay e a tela correspondente
    document.getElementById('overlay').style.display = 'block';
    document.getElementById(screenId).style.display = 'block';
    document.querySelector('.mini-screens').style.display = 'flex';
}

// Esconde todas as mini telas e o overlay
function hideAllMiniScreens() {
    // Esconde o overlay
    document.getElementById('overlay').style.display = 'none';

    // Esconde todas as telas
    document.querySelectorAll('.mini-screen').forEach(screen => {
        screen.style.display = 'none';
    });

    // Esconde a área de mini telas
    document.querySelector('.mini-screens').style.display = 'none';
}

// Event listeners para os botões de fechar
document.querySelectorAll('.close-btn').forEach(button => {
    button.addEventListener('click', function() {
        hideAllMiniScreens(); // Esconde todas as mini telas ao clicar no botão de fechar
    });
});
