<?php
session_start();

// Inicializa a lista de produtos como array, caso não exista
if (!isset($_SESSION['produtos']) || !is_array($_SESSION['produtos'])) {
    $_SESSION['produtos'] = [];
}

// Remove produto se solicitado
if (isset($_GET['remove'])) {
    unset($_SESSION['produtos'][$_GET['remove']]);
    $_SESSION['produtos'] = array_values($_SESSION['produtos']);
    header('Location: lista_produtos.php');
    exit;
}

// Paleta de cores padrão
$coresPerfis = [
    ['nome' => 'OCÃ',     'hex' => '#6e7c2a'],
    ['nome' => 'WALNUT',  'hex' => '#7a2236'],
    ['nome' => 'NOGAL',   'hex' => '#827c6a'],
    ['nome' => 'CINZA',   'hex' => '#6f7272'],
    ['nome' => 'ANGELIM', 'hex' => '#c59a44'],
    ['nome' => 'MOGNO',   'hex' => '#a47f3a'],
    ['nome' => 'IPÊ',     'hex' => '#87613f'],
    ['nome' => 'BRANCO',  'hex' => '#ffffff'],
    ['nome' => 'PRETO',   'hex' => '#111111'],
    ['nome' => 'TEKA',    'hex' => '#a3783b'],
    ['nome' => 'TAUARI',  'hex' => '#ebc46f']
];

// Caminho base das imagens dos perfis (relativo à raiz do servidor)
$imagesBasePath = 'orcamento-php/assets/images/profiles/';
$imagensPerfis = [
    'Ripado 11cm' => 'ripado_11cm.png',
    'Deck 14cm'   => 'deck_14cm.png',
    'Brise'       => 'brise.png',
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Lista de Produtos</title>
<style>
body {
    background: #f5f6fa;
    font-family: 'Segoe UI', Arial, sans-serif;
    min-height: 99vh;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.table-box {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px #0002;
    padding: 22px 20px;
    margin: 40px auto;
    max-width: 960px;
    width: 90vw;
    overflow-x: auto;
}
.table-box h2 {
    text-align: center;
    font-weight: 600;
    margin-bottom: 24px;
}
table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
}
th, td {
    padding: 14px 10px;
    border-bottom: 1px solid #ececec;
    text-align: center;
    vertical-align: middle;
}
thead th {
    background: #f8fafb;
    color: #444;
    font-size: 1.03rem;
}
tr:last-child td {
    border-bottom: none;
}
.circle-color {
    display: inline-block;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #e5e5e5;
    margin-right: 11px;
    vertical-align: middle;
    box-shadow: 0 2px 8px #0001;
}
.profile-img {
    max-height: 80px;
    max-width: 100px;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 10px;
    background: #fff;
    border: 1px solid #e0e0e0;
    display: inline-block;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: transform .18s;
}
.profile-img:hover {
    transform: scale(1.04);
}
.btn-action {
    background: #388e3c;
    color: white;
    padding: 10px 32px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 17px;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 6px #0001;
    text-decoration: none;
    margin-right: 18px;
    display: inline-block;
    transition: background-color 0.25s ease, box-shadow 0.25s ease, transform 0.15s ease;
}
.btn-action:hover {
    background: #2e7d32;
    box-shadow: 0 6px 18px #0004;
    text-decoration: none;
    transform: scale(1.03);
}
.btn-action:active {
    transform: scale(0.97);
}
.btn-action.blue {
    background: #1976d2;
    margin-right: 0;
}
.btn-action.blue:hover {
    background: #0d47a1;
    box-shadow: 0 6px 18px #052157;
    transform: scale(1.03);
}
.btn-remove {
    background: #d32f2f;
    color: #fff;
    border-radius: 6px;
    padding: 8px 16px;
    text-decoration: none;
    font-size: 15px;
    font-weight: 600;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 2px 6px #a12222;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-remove:hover {
    background: #b71c1c;
    box-shadow: 0 4px 12px #801111;
}
.btn-remove span {
    font-size: 18px;
    line-height: 1;
}
.text-secondary {
    color: #777 !important;
}
.small {
    font-size: 12px !important;
}
.actions-container {
    display: flex;
    justify-content: center;
    gap: 32px;
    margin-top: 28px;
}
@media (max-width: 749px) {
    .table-box {
        padding: 14px 10px;
    }
    th, td {
        font-size: 13px;
        padding: 7px 6px;
    }
    .btn-action {
        font-size: 15px;
        padding: 10px 24px;
        margin-right: 12px;
    }
    .actions-container {
        flex-direction: column;
        gap: 16px;
        margin-top: 18px;
    }
    .btn-action {
        margin-right: 0;
        width: 100%;
        text-align: center;
        padding: 14px 0;
    }
}
</style>
</head>
<body>
<div class="table-box">
    <h2>Lista de Produtos</h2>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Cor</th>
                <th>Imagem Perfil</th>
                <th>Metragem (m²)</th>
                <th>Quantidade de Peças</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($_SESSION['produtos']) === 0): ?>
            <tr>
                <td colspan="6" class="text-secondary">Nenhum produto adicionado até o momento.</td>
            </tr>
            <?php else: foreach ($_SESSION['produtos'] as $i => $item): 
                $corHex = '#777';
                foreach ($coresPerfis as $cor) {
                    if ($cor['nome'] === $item['cor']) {
                        $corHex = $cor['hex'];
                        break;
                    }
                }
                $imagemProduto = isset($imagensPerfis[$item['nome']]) ? $imagesBasePath . $imagensPerfis[$item['nome']] : null;
                $fileFisico = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagemProduto;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td>
                    <span class="circle-color" style="background:<?= $corHex ?>"></span>
                    <?= htmlspecialchars($item['cor']) ?>
                </td>
                <td>
                    <?php if ($imagemProduto && file_exists($fileFisico)): ?>
                        <img src="/<?= $imagemProduto ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="profile-img" />
                    <?php else: ?>
                        <span class="text-secondary">—</span>
                    <?php endif; ?>
                </td>
                <td><strong><?= number_format($item['metragem'], 2, ',', '.') ?></strong></td>
                <td>
                    <?php 
                    if (isset($item['quantidade']) && $item['quantidade'] !== null) {
                        $altura = (isset($item['altura_parede']) && $item['altura_parede'] !== null) ? number_format($item['altura_parede'], 2, ',', '.') . "m" : "—";
                        echo '<strong>' . $item['quantidade'] . " réguas</strong><br><span class='text-secondary small'>c/ $altura</span>";
                    } else {
                        echo "—";
                    }
                    ?>
                </td>
                <td>
                    <a href="?remove=<?= $i ?>" class="btn-remove" title="Remover Produto">
                        <span>&#128465;</span> Remover
                    </a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
    <div class="actions-container">
        <a href="produtos.php" class="btn-action">Adicionar Mais</a>
        <a href="resumo.php" class="btn-action blue">Finalizar Orçamento</a>
    </div>
</div>
</body>
</html>
