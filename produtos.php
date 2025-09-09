<?php
session_start();

$produtosDisponiveis = [
    ['nome' => "Ripado 11cm", 'largura_perfil' => 0.11, 'valor_unitario' => 150, 'tipo' => 'Ripado'],
    ['nome' => "Deck 14cm", 'largura_perfil' => 0.14, 'valor_unitario' => 180, 'tipo' => 'Deck'],
    ['nome' => "Brise", 'largura_perfil' => 0.10, 'valor_unitario' => 200, 'tipo' => 'Brise']
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
    ['nome' => 'TAUARI', 'hex' => '#ebc46f']
];

function getIpiPercentual($tipo) {
    switch ($tipo) {
        case 'Ripado': return 3.25;
        case 'Deck': return 0.00;
        case 'Brise': return 3.25;
        default: return 0.00;
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
            } else {
                $valor_unitario = $produto['valor_unitario'];
                $ipi_percentual = getIpiPercentual($produto['tipo']);
            }

            $ipi_decimal = ($ipi_percentual/100);
            $valor_total = ($valor_unitario * $ipi_decimal) * $area_total + ($valor_unitario * $area_total);

            $tipo_cliente = $_SESSION['cliente']['tipo'] ?? 'final';
            if ($tipo_cliente === 'revendedor') {
                $valor_total = $valor_total + ($valor_total*0.08); // acréscimo de 8%
            }

            $_SESSION['produtos'][] = [
                'nome' => $produto['nome'],
                'cor' => $cor_perfil,
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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Adicionar Produto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    .cor-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        user-select: none;
        transition: transform 0.2s ease;
    }
    .cor-label:hover, .cor-label:focus-within {
        transform: scale(1.1);
        z-index: 2;
    }
    .cor-radio {
        opacity: 0;
        position: absolute;
        width: 0;
        height: 0;
    }
    .cor-circle {
        display: inline-block;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #333;
        box-shadow: 0 2px 8px #0001;
        margin-bottom: 6px;
        transition: border-color 0.3s ease;
    }
    .cor-radio:checked + .cor-circle {
        border: 4px solid #0d6efd;
        box-shadow: 0 0 10px #0d6efd;
    }
</style>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card p-4 shadow-sm" style="width: 100%; max-width: 460px;">
    <h2 class="card-title mb-4 text-center">Cadastro de Produtos</h2>
    <?php if ($error): ?>
        <div class="alert alert-warning" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form method="POST" novalidate>
        <div class="mb-3">
            <label for="produtoSelect" class="form-label">Produto</label>
            <select class="form-select" id="produtoSelect" name="produto_indx" required>
                <option value="">Selecione...</option>
                <?php foreach ($produtosDisponiveis as $i => $p): ?>
                    <option value="<?= $i ?>" <?= (isset($_POST['produto_indx']) && $_POST['produto_indx'] == $i) ? "selected" : "" ?>>
                        <?= htmlspecialchars($p['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <label class="form-label">Cor do Perfil</label>
        <div class="d-flex flex-wrap gap-3 mb-3">
            <?php foreach($coresPerfis as $cor):
                $checked = (isset($_POST['cor_perfil']) && $_POST['cor_perfil'] === $cor['nome']) ? true : false;
            ?>
                <label class="cor-label" tabindex="0">
                    <input type="radio" name="cor_perfil" value="<?= htmlspecialchars($cor['nome']) ?>"
                        class="cor-radio" <?= $checked ? 'checked' : '' ?> required />
                    <span class="cor-circle" style="background:<?= $cor['hex'] ?>;"></span>
                    <span style="color:#212529; font-family:sans-serif; font-size:14px;">
                        <?= htmlspecialchars($cor['nome']) ?>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label for="metragemQuadrada" class="form-label">Metragem Quadrada (m²)</label>
            <input type="number" step="any" min="0" class="form-control" id="metragemQuadrada" name="metragem_quadrada" value="<?= htmlspecialchars($_POST['metragem_quadrada'] ?? '') ?>">
        </div>

        <p class="text-center text-muted" style="margin-bottom: 1rem;">Ou</p>

        <div class="mb-3">
            <label for="larguraParede" class="form-label">Largura da Parede (m)</label>
            <input type="number" step="any" min="0" class="form-control" id="larguraParede" name="largura_parede" value="<?= htmlspecialchars($_POST['largura_parede'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="alturaParede" class="form-label">Altura da Parede (m)</label>
            <input type="number" step="any" min="0" class="form-control" id="alturaParede" name="altura_parede" value="<?= htmlspecialchars($_POST['altura_parede'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-primary w-100">Adicionar Produto</button>
    </form>
    <form action="lista_produtos.php" class="mt-3 text-center">
        <button type="submit" class="btn btn-success w-75">Ir para lista de produtos</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

