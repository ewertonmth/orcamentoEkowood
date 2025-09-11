<?php
session_start();

$produtosDisponiveis = [
    ['nome' => "Ripado 11cm", 'largura_perfil' => 0.11, 'valor_unitario' => 150, 'tipo' => 'Ripado'],
    ['nome' => "Deck 14cm", 'largura_perfil' => 0.14, 'valor_unitario' => 180, 'tipo' => 'Deck'],
    ['nome' => "Brise", 'largura_perfil' => 0.10, 'valor_unitario' => 200, 'tipo' => 'Brise'],
];

$coresPerfis = [
    ['nome' => 'OCÃ', 'hex' => '#6e7c2a'],
    ['nome' => 'WALNUT', 'hex' => '#7a2236'],
    ['nome' => 'NOGAL', 'hex' => '#827c6a'],
    ['nome' => 'CINZA', 'hex' => '#6f7272'],
    ['nome' => 'ANGELIM', 'hex' => '#c59a44'],
    ['nome' => 'MOGNO', 'hex' => '#a47f3a'],
    ['nome' => 'IPÊ', 'hex' => '#87613f'],
    ['nome' => 'BRANCO', 'hex' => '#ffffff'],
    ['nome' => 'PRETO', 'hex' => '#111111'],
    ['nome' => 'TEKA', 'hex' => '#a3783b'],
    ['nome' => 'TAUARI', 'hex' => '#ebc46f'],
];

function getIpiPercentual($tipo) {
    switch ($tipo) {
        case 'Ripado':
            return 3.25;
        case 'Deck':
            return 0.00;
        case 'Brise':
            return 3.25;
        default:
            return 0.00;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_indx = $_POST['produto_indx'] ?? null;
    $cor_perfil = $_POST['cor_perfil'] ?? null;

    if (!is_numeric($produto_indx) || !isset($produtosDisponiveis[$produto_indx])) {
        $error = "Selecione um produto válido.";
    } elseif (!$cor_perfil) {
        $error = "Selecione uma cor para o perfil antes de adicionar o produto!";
    } else {
        $produto = $produtosDisponiveis[$produto_indx];

        $metragem_quadrada = isset($_POST['metragem_quadrada']) ? floatval($_POST['metragem_quadrada']) : 0;
        $largura_parede = isset($_POST['largura_parede']) ? floatval($_POST['largura_parede']) : 0;
        $altura_parede = isset($_POST['altura_parede']) ? floatval($_POST['altura_parede']) : 0;

        if ($largura_parede > 0 && $altura_parede > 0 && $produto['largura_perfil'] > 0) {
            $quantidade_base = $largura_parede / $produto['largura_perfil'];
            $quantidade_final = ceil($quantidade_base * 1.05);
            $area_total = $quantidade_final * $altura_parede * $produto['largura_perfil'];
        } elseif ($metragem_quadrada > 0) {
            $area_total = $metragem_quadrada;
            $quantidade_final = null;
        } else {
            $error = 'Preencha corretamente a metragem ou largura e altura da parede.';
        }

        if (!$error) {
            if ($produto['tipo'] === 'Ripado') {
                $valor_unitario = 354.48;
                $ipi_percentual = 3.25;
            } elseif ($produto['tipo'] === 'Deck') {
                $valor_unitario = 530.07;
                $ipi_percentual = 0.00;
            } else {
                $valor_unitario = $produto['valor_unitario'];
                $ipi_percentual = getIpiPercentual($produto['tipo']);
            }

            $ipi_decimal = ($ipi_percentual / 100);
            $valor_total = ($valor_unitario * $ipi_decimal) * $area_total + ($valor_unitario * $area_total);

            $tipo_cliente = $_SESSION['cliente']['tipo'] ?? 'final';
            if ($tipo_cliente === 'revendedor') {
                $valor_total = $valor_total + ($valor_total * 0.08);
            }

            // Busca o nome da cor pelo hex
            $nome_cor = 'Desconhecida';
            foreach ($coresPerfis as $cor) {
                if ($cor['hex'] === $cor_perfil) {
                    $nome_cor = $cor['nome'];
                    break;
                }
            }

            $_SESSION['produtos'][] = [
                'nome' => $produto['nome'],
                'cor_nome' => $nome_cor,
                'cor_hex' => $cor_perfil,
                'metragem' => $area_total,
                'quantidade' => $quantidade_final,
                'altura_parede' => ($altura_parede > 0) ? $altura_parede : null,
                'preco_unitario' => $valor_unitario,
                'ipi' => $ipi_percentual,
                'total' => $valor_total,
            ];

            header('Location: lista_produtos.php');
            exit;
        }

    }
}

include 'templates/header.php';
?>

<div class="card">
  <div class="card-header">Produtos</div>
  <div class="card-body">
    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="produtos.php" novalidate>
      <label for="produto_indx">Produto:</label>
      <select class="custom-select" id="produto_indx" name="produto_indx" required>
        <option value="" disabled selected>Selecione um produto</option>
        <?php foreach ($produtosDisponiveis as $i => $produto): ?>
          <option value="<?= $i ?>"><?= htmlspecialchars($produto['nome']) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="cor_perfil" style="margin-top:12px;">Cor do Perfil:</label>
      <div class="color-options">
        <?php foreach ($coresPerfis as $i => $cor): ?>
          <input type="radio" id="cor_<?= $i ?>" name="cor_perfil" value="<?= htmlspecialchars($cor['hex']) ?>" />
          <label for="cor_<?= $i ?>" class="color-label" title="<?= htmlspecialchars($cor['nome']) ?>" style="background-color: <?= htmlspecialchars($cor['hex']) ?>;"></label>
        <?php endforeach; ?>
      </div>

      <label for="metragem_quadrada" style="margin-top:12px;">Metragem Quadrada (opcional):</label>
      <input type="number" step="0.01" class="form-control" id="metragem_quadrada" name="metragem_quadrada" placeholder="Exemplo: 10.50" />

      <label for="largura_parede" style="margin-top:12px;">Largura da Parede (metros):</label>
      <input type="number" step="0.01" class="form-control" id="largura_parede" name="largura_parede" placeholder="Exemplo: 3.20" />

      <label for="altura_parede" style="margin-top:12px;">Altura da Parede (metros):</label>
      <input type="number" step="0.01" class="form-control" id="altura_parede" name="altura_parede" placeholder="Exemplo: 2.5" />

      <button type="submit" class="btn-submit" style="margin-top: 20px;">Adicionar Produto</button>
    </form>
  </div>
</div>

<?php
include 'templates/footer.php';
?>
