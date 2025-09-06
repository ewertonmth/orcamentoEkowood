<?php
error_reporting(0); // Oculta warnings na tela do usuário
ini_set('display_errors', 0);

session_start();
$produtos = $_SESSION['produtos'] ?? [];
$cliente = $_SESSION['cliente'] ?? [];

function safe($val) { return isset($val) && $val !== '' ? $val : '—'; }

$metragem_total = 0;
$qtd_pecas_total = 0;
$peso_total = 0;
$valor_total = 0;
foreach ($produtos as $item) {
    $metragem_total += $item['metragem'] ?? 0;
    $qtd_pecas_total += $item['quantidade'] ?? 0;
    $peso_total += 180; // Exemplo fixo, ajuste seus cálculos
    $valor_total += $item['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Resumo do Orçamento</title>
<link rel="stylesheet" href="assets/style.css">
<style>
.resumo-box {
    background:#fff;
    border-radius:16px;
    box-shadow:0 3px 20px #0002;
    max-width:960px;
    margin:38px auto;
    padding:36px 30px 25px 30px;
}
.resumo-blocos {
    display:flex;
    gap:40px;
    align-items: flex-start;
    flex-wrap: wrap;
    justify-content: flex-start;
    margin-bottom:24px;
}
.resumo-table {
    width:62%;
    min-width:350px;
    border-collapse:collapse;
    background:#fafafd;
    border-radius:10px;
    overflow:hidden;
}
.resumo-table th, .resumo-table td {
    padding:12px 9px;
    border-bottom:1px solid #f0f0f0;
    text-align:center;
    font-size:16px;
    vertical-align:middle
}
.resumo-table th {
    background:#f2f4f7;
    color:#222;
    font-weight:600;
    font-size:17px;
}
.resumo-table tr:last-child td { border-bottom:none; }
.resumo-card {
    min-width:200px;
    background:#f5f6fa;
    padding:18px 24px;
    border-radius:10px;
    font-size:16px;
    font-weight:500;
    margin-left:20px;
    box-shadow:0 1px 4px #0001;
    flex:1;
}
.resumo-card div { margin-bottom:9px;}
.resumo-card span { color:#1976d2;font-weight:600;}
@media (max-width:950px){
    .resumo-blocos { flex-direction:column;gap:13px;}
    .resumo-card{margin:20px 0 0 0;}
    .resumo-table{width:100%;}
}
</style>
</head>
<body>
<div class="resumo-box">
    <h2>Resumo do Orçamento</h2>
    <p><b>Cliente:</b> <?= safe($cliente['nome'] ?? '') ?></p>
    <div class="resumo-blocos">
        <table class="resumo-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Metragem (m²)</th>
                    <th>Peças</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
            <?php if(count($produtos) == 0): ?>
                <tr><td colspan="4" style="color:#aaa;font-weight:500;">Nenhum produto cadastrado no orçamento.</td></tr>
            <?php else: foreach ($produtos as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td><strong><?= number_format($item['metragem'] ?? 0,2,',','.') ?></strong></td>
                    <td><?= safe($item['quantidade'] ?? '') ?></td>
                    <td>R$ <strong><?= number_format($item['total'] ?? 0,2,',','.') ?></strong></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
        <div class="resumo-card">
            <div><b>Metragem total:</b> <span><?= number_format($metragem_total,2,',','.') ?> m²</span></div>
            <div><b>Quantidade total de peças:</b> <span><?= $qtd_pecas_total ?></span></div>
            <div><b>Peso total:</b> <span><?= $peso_total ?></span> kg</div>
            <div><b>Valor total:</b> <span>R$ <?= number_format($valor_total,2,',','.') ?></span></div>
        </div>
    </div>
    <div class="actions-container" style="margin-top:1.8em;">
        <a href="produtos.php" class="btn-action">Adicionar Mais</a>
        <a href="exportar_pdf.php" class="btn-action blue" target="_blank">Exportar Orçamento em PDF</a>
        <a href="index.php" class="btn-action" style="background:#555;">Novo Orçamento</a>
    </div>
</div>
</body>
</html>
