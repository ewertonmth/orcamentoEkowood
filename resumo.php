<?php
error_reporting(0);
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
    $peso_total += 180;
    $valor_total += $item['total'] ?? 0;
}

include 'templates/header.php';
?>

<link rel="stylesheet" href="assets/css/resumo.css">

<div class="container">
  <div class="card" style="margin: 38px auto; padding: 36px 30px 25px 30px; max-width: 600px;">
    <div class="resumo-header">Resumo do Orçamento</div>
    <div class="resumo-cliente"><b>Cliente:</b> <?= safe($cliente['nome'] ?? '') ?></div>
    <table class="table table-resumo">
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
        <tr>
          <td colspan="4" style="color:#838383;font-weight:500;">Nenhum produto cadastrado no orçamento.</td>
        </tr>
      <?php else: foreach ($produtos as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['nome']) ?></td>
          <td><?= number_format($item['metragem'] ?? 0, 2, ',', '.') ?></td>
          <td><?= safe($item['quantidade'] ?? '') ?></td>
          <td>R$ <?= number_format($item['total'] ?? 0, 2, ',', '.') ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
    <div class="resumo-linha"></div>
    <div class="resumo-totais">
      <div><b>Metragem total:</b> <span><?= number_format($metragem_total, 2, ',', '.') ?> m²</span></div>
      <div><b>Quantidade total de peças:</b> <span><?= $qtd_pecas_total ?></span></div>
      <div><b>Peso total:</b> <span><?= $peso_total ?></span> kg</div>
      <div><b>Valor total:</b> <span class="valor-total">R$ <?= number_format($valor_total, 2, ',', '.') ?></span></div>
    </div>
    <div class="resumo-actions">
      <a href="produtos.php" class="btn-action green">Adicionar Mais</a>
      <a href="exportar_pdf.php" class="btn-action" target="_blank">Exportar Orçamento em PDF</a>
      <a href="novo_orcamento.php" class="btn-action gray">Novo Orçamento</a>
    </div>
  </div>
</div>

<?php
include 'templates/footer.php';
?>
