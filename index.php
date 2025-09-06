<?php
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome        = trim($_POST['nome'] ?? '');
    $cnpj        = trim($_POST['cnpj'] ?? '');
    $endereco    = trim($_POST['endereco'] ?? '');
    $entrega     = trim($_POST['endereco_entrega'] ?? '');
    $telefone    = trim($_POST['telefone'] ?? '');
    $consultor   = trim($_POST['consultor'] ?? '');
    $tipo_cliente = $_POST['tipo_cliente'] ?? '';

    if (!$nome || !$cnpj || !$endereco || !$entrega || !$telefone || !$consultor || !$tipo_cliente) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        $_SESSION['cliente'] = [
            'nome'              => $nome,
            'cnpj'              => $cnpj,
            'endereco'          => $endereco,
            'endereco_entrega'  => $entrega,
            'telefone'          => $telefone,
            'consultor'         => $consultor,
            'tipo'              => $tipo_cliente,
        ];
        header('Location: produtos.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Cadastro de Cliente</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  min-height: 100vh;
  display: flex;
  overflow-x: hidden;
}
.sidebar {
  width: 250px;
  background-color: #343a40;
  min-height: 100vh;
  padding-top: 1rem;
}
.sidebar a {
  color: #ddd;
  text-decoration: none;
  display: block;
  padding: 12px 20px;
  border-left: 4px solid transparent;
  transition: all 0.2s;
}
.sidebar a:hover, .sidebar a.active {
  background-color: #495057;
  color: #fff;
  border-left-color: #0d6efd;
}
.sidebar .sidebar-header {
  color: white;
  font-weight: bold;
  font-size: 1.3rem;
  padding: 15px 20px;
  border-bottom: 1px solid #495057;
  margin-bottom: 1rem;
}
.main-content {
  flex-grow: 1;
  padding: 20px;
  background-color: #f8f9fa;
  min-height: 100vh;
}
@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    height: auto;
    min-height: auto;
  }
  body {
    flex-direction: column;
  }
}
</style>

<div class="sidebar">
  <div class="sidebar-header">Orçamento</div>
  <a href="index.php" class="<?= ($_SERVER['SCRIPT_NAME'] === '/index.php' ? 'active' : '') ?>">Cadastro Cliente</a>
  <a href="produtos.php" class="<?= ($_SERVER['SCRIPT_NAME'] === '/produtos.php' ? 'active' : '') ?>">Adicionar Produtos</a>
  <a href="lista_produtos.php" class="<?= ($_SERVER['SCRIPT_NAME'] === '/lista_produtos.php' ? 'active' : '') ?>">Lista de Produtos</a>
  <a href="resumo.php" class="<?= ($_SERVER['SCRIPT_NAME'] === '/resumo.php' ? 'active' : '') ?>">Resumo</a>
</div>

<div class="main-content">
  <!-- Aqui o conteúdo principal da página -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<div class="card">
  <h2 class="mb-4 text-center">Cadastro do Cliente</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST" autocomplete="off" novalidate>
    <input type="text" class="form-control" name="nome" placeholder="Nome do Cliente" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required />

    <input type="text" class="form-control" name="cnpj" placeholder="CNPJ" value="<?= htmlspecialchars($_POST['cnpj'] ?? '') ?>" required />

    <input type="text" class="form-control" name="endereco" placeholder="Endereço" value="<?= htmlspecialchars($_POST['endereco'] ?? '') ?>" required />

    <input type="text" class="form-control" name="endereco_entrega" placeholder="Endereço de Entrega" value="<?= htmlspecialchars($_POST['endereco_entrega'] ?? '') ?>" required />

    <input type="text" class="form-control" name="telefone" placeholder="Telefone" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required />

    <input type="text" class="form-control" name="consultor" placeholder="Nome do Consultor" value="<?= htmlspecialchars($_POST['consultor'] ?? '') ?>" required />

    <select name="tipo_cliente" class="form-select" required>
      <option value="">Tipo de Cliente</option>
      <option value="final" <?= (isset($_POST['tipo_cliente']) && $_POST['tipo_cliente'] === 'final') ? 'selected' : '' ?>>Cliente Final</option>
      <option value="revendedor" <?= (isset($_POST['tipo_cliente']) && $_POST['tipo_cliente'] === 'revendedor') ? 'selected' : '' ?>>Revendedor</option>
    </select>

    <button type="submit" class="btn btn-primary w-100 mt-2">Avançar</button>
  </form>
</div>
</body>
</html>
